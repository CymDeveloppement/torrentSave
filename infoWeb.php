<?php 
/**
 * PHP Version 7
 * Verif Class Doc Comment
 *
 * @category Class
 * @package  Package
 * @author   SCHRODER Bastien <bastien.schroder@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://10.0.10.145/infoDisk.php
 */
session_start();
session_regenerate_id();

if (empty($_SESSION['username'])) {

    header('Location: login.php');
    exit();

}
/**
 * Require the error log
 */
require "errorLog.php";

/**
 * InfoWeb Class Doc Comment
 *
 * @category Class
 * @package  Package
 * @author   SCHRODER Bastien <bastien.schroder@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://10.0.10.145/infoDisk.php
 */
Class InfoWeb
{
    private $_db;
    private static $_db_dir = '/data/DB/database.sqlite';

    /**
     * Construct
     *
     * @return object
     */
    public function __construct()
    {
        $db = self::_dbConnect(self::$_db_dir); 
        $this->_setDb($db);
     
    }

    /**
     * Db setter
     *
     * @param object $db DAO
     *
     * @return object
     */
    private function _setDb(PDO $db)
    {
        $this->_db = $db;
    }
    /**
     * Connection to the database 
     *
     * @param string $db_dir Database directory
     *
     * @return object
     */
    private function _dbConnect($db_dir)
    {
        try{
            $db = new PDO('sqlite:'.$db_dir);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(Exception $e) {
            echo "Impossible d'accéder à la base de données: ".$e->getMessage();
            die();
        }
        return $db;
    }

    /**
     * Convert octets
     *
     * @param Array $number int
     *
     * @return string
     **/
    private function _convert($number)
    {
        if (is_numeric($number)) {

            switch(true) {

            case $number > 1000000000000:
                $number = round($number/1000000000000, 2);
                return "$number To";
                break; 
            case $number > 1000000000 && $number < 1000000000000:
                $number = round($number/1000000000, 2);
                return "$number Go";
                break; 

            case $number < 1000000000 && $number > 1000000:
                $number = round($number/1000000, 2);
                return "$number Mo";
                break; 
            case $number < 1000000:
                return "$number Octets";
                break;
            }
        } else {
            return null;
        }
    }
    /**
     * Send torrent to the pair
     *
     * @param Array $torrent array of data
     *
     * @return file
     **/
    public function sendTorrent($torrent)
    {
        if (file_exists($torrent['libelle'])) {
            $baseName = basename($torrent['libelle']);
            $mime = mime_content_type($torrent['libelle']);
            header("Content-disposition: attachment; filename='$baseName'");
            header("Content-Type: application/force-download");
            header("Content-Transfer-Encoding: $mime\n");
            header("Content-Length: ".filesize($torrent['libelle']));
            header("Pragma: no-cache");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public");
            header("Expires: 0");
            readfile($torrent['libelle']);
        }
       
        return;
    }

    /**
     * Validate a new pair
     *
     * @param Array $value get value 
     *
     * @return string
     **/
    public function validPair($value)
    {
        
        self::_pairUpdate($value);
         
    }

    /**
     * Pair state update
     *
     * @param Array $value get value 
     *
     * @return array
     */
    private function _pairUpdate($value)
    {

        $req = $this->_db->prepare(
            'INSERT INTO ID
            SELECT *
            FROM tempID
            WHERE Key = :key
            AND PC_name = :name'
        );
        $req->execute(
            array(
                'key' => $value['Key'],
                'name' => $value['PC_name']
            )
        );

        $req2 = $this->_db->prepare(
            'DELETE FROM tempID
            WHERE Key = :key
            AND PC_name = :name'
        );
        $req2->execute(
            array(
                'key' => $value['Key'],
                'name' => $value['PC_name']
            )
        );
       
        
    }

    /**
     * List of torrent 
     *
     * @return array
     */
    private function _torrentListFromDb()
    {

        $req = $this->_db->prepare(
            'SELECT idTorrent, libelle, taille, statut, idSource, PC_name  
			FROM torrent 
			INNER JOIN ID 
			ON torrent.idSource = ID.Key'
        );
        $req->execute();
        $data = $req->fetchAll();
        return $data;
    }

    /**
     * List of share 
     *
     * @return array
     */
    private function _shareListFromDb()
    {

        $req = $this->_db->prepare(
            'SELECT idShare, libelle, PC_name, left 
            FROM torrent 
            INNER JOIN shareList
            ON torrent.idTorrent = shareList.idTorrent
            INNER JOIN ID 
            ON shareList.idPair = ID.Key
            '
        );
        $req->execute();
        $data = $req->fetchAll();
        return $data;
    }

    /**
     * List of Pair 
     *
     * @return array
     */
    private function _pairListFromDb()
    {

        $name = $this->_db->prepare(
            'SELECT PC_name, Key
            FROM ID'
        );
        $name->execute();
        $dataName = $name->fetchAll();
        foreach ($dataName as $key => $value) {
            
            $info = $this->_db->prepare(
                'SELECT count(idShare) AS Share
                FROM shareList 
                WHERE idPair = :pair'
            );
            $info->execute(
                array(
                    'pair' => $value['Key']
                )
            );
            $dataShare = $info->fetch(PDO::FETCH_ASSOC);
            
            $send = $this->_db->prepare(
                'SELECT count(idSource) AS Source
                FROM torrent 
                WHERE idSource = :pair'
            );
            $send->execute(
                array(
                    'pair' => $value['Key']
                )
            );
            $dataSource = $send->fetch(PDO::FETCH_ASSOC);


            $diskN = $this->_db->prepare(
                'SELECT pcKey, count(idDisk) AS Disk, MAX(lastUpdate) AS lastUpdate
                FROM infoDisk 
                WHERE pcKey = :pair
                GROUP BY pcKey'
            );
            $diskN->execute(
                array(
                    'pair' => $value['Key']
                )
            );
            $dataDisk = $diskN->fetch(PDO::FETCH_ASSOC);

            $dataName[$key]['info'] = array(
                'share' => $dataShare["Share"],
                'source' => $dataSource["Source"],
                'disk'  => $dataDisk["Disk"],
                'lastUpdate' => $dataDisk["lastUpdate"]
            );

        }
        return $dataName;
    }

    /**
     * List of new Pair 
     *
     * @return array
     */
    private function _newPairListFromDb()
    {

        $req = $this->_db->prepare(
            'SELECT Key, PC_name 
            FROM tempID'
        );
        $req->execute();
        $data = $req->fetchAll();
        return $data;
    }

    /**
     * Share List
     *
     * @return string
     **/
    public function shareList()
    {
        
        $data = self::_shareListFromDb();
 

        foreach ($data as $key => $ligne) {

            echo "<tr>";
            echo "<td>".$ligne['idShare']."</td>";
            echo "<td><b>".basename($ligne['libelle'], ".torrent")."</b></td>";
            echo "<td>".$ligne['PC_name']."</td>";
            echo "<td><div class=\"progress-container\">
                  <div class=\"progress-color\" style=\"width:".$ligne['left']."%\"></div>
                  <div class=\"percent\">".$ligne['left']."%</div>
                  </div></td>";
            echo "</tr>";
        }
    }

    /**
     * Pair List
     *
     * @return string
     **/
    public function pairList()
    {
        
        $data = self::_pairListFromDb();
        
       
        foreach ($data as $key => $ligne) {
            
            echo "<tr>";
            echo "<td>".$ligne['PC_name']."</td>";
            echo "<td>".$ligne['info']['share']."</td>";
            echo "<td>".$ligne['info']['source']."</td>";
            echo "<td>".$ligne['info']['disk']."</td>";
            echo "<td>".$ligne['info']['lastUpdate']."</td>";
            echo "</tr>";
        }
    }

    /**
     * New Pair List
     *
     * @return string
     **/
    public function newPairList()
    {
        
        $data = self::_newPairListFromDb();

        foreach ($data as $key => $ligne) {

            echo "<tr>
                        <td>".$ligne['PC_name']."</td>
                        <td>".$ligne['Key']."</td>
                        <td>
                            <button onclick=\"document.getElementById('".$ligne['Key']."').style.display='block'\" >Ajouter aux pairs</button>
                            <div id=\"".$ligne['Key']."\" class=\"modal\">
                                <div class=\"modal-content\">
                                    <div class=\"modal-container\">
                                        <span onclick=\"document.getElementById('".$ligne['Key']."').style.display='none'\" class=\"display-topright\">&times;</span>
                                        <h4>Ajouter aux pairs ?</h4>
                                        <a href=\"infoWeb.php?addPair&PC_name=".$ligne['PC_name']."&Key=".$ligne['Key']."\">Valider</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>";
        }
        echo "
                </tbody>
            </table>";
        if (count($data) === 0) {
           
            echo "<p class=\"center\">Aucune nouvelle demande d'ajout.</p>";
          
        }
    }

    /**
     * Torrent info list
     *
     * @return string
     **/
    public function torrentList()
    {

        $data = self::_torrentListFromDb();
        
        foreach ($data as $key => $ligne) {

            if ($ligne['statut'] === '0') {

                $statut = "<b class = \"red\">En attente de Pair</b>";

            } else { 

                    $statut = "<b class = \"green\">Sauvegardé</b>";

            }

            echo "<tr>";
            echo "<td>".$ligne['idTorrent']."</td>";
            echo "<td><b>".basename($ligne['libelle'], ".torrent")."</b></td>";
            echo "<td>".self::_convert($ligne['taille'])."</td>";
            echo "<td>".$statut."</td>";
            echo "<td>".$ligne['PC_name']."</td>";
            echo "<td><a href=\"infoWeb.php?getTorrent&libelle=".$ligne['libelle']."\"><i class=\"fas fa-download\"></i></a></td>";
            echo "</tr>";
        }
    }
}


switch(true)
{
case isset($_GET['getTorrent']):


        $infoWeb = new InfoWeb();
        echo $infoWeb->sendTorrent($_GET);
    

    break;

case isset($_GET['addPair']):


        $infoWeb = new InfoWeb();
        echo $infoWeb->validPair($_GET);
        header('Location: newPairs.php');
    

    break;
}