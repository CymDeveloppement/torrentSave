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

/**
 * Require the error log
 */
require "errorLog.php";

if (!isset($_POST['key'])) {
    if (!isset($_GET['key']) || strlen($_GET['key'])!=64 ) {
        echo "non";
       
        exit;
    }
}

$db_dir = '/data/DB/database.sqlite';

/**
 * UpdateInfoDisk Class Doc Comment
 *
 * @category Class
 * @package  Package
 * @author   SCHRODER Bastien <bastien.schroder@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://10.0.10.145/infoDisk.php
 */
Class UpdateInfoDisk
{
    private static $_keyLength = 64 ;
    private $_db;
    private static $_db_dir = '/data/DB/database.sqlite';
    private $_getInfo;
    private static $_iniConfig;
   
    /**
     * DAO
     *
     * @param array $getInfo 
     *
     * @return object
     */
    public function __construct($getInfo)
    {
        $db = self::_dbConnect(self::$_db_dir); 
        $this->_setDb($db);
        $this->_setIniConfig();
        $this->_getInfo = $getInfo;
     
    }
    /**
     * _db setter
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
     * _iniConfig setter
     *
     * @return string
     */
    private function _setIniConfig()
    {
        if (file_exists(__DIR__."/config/config.ini")) {

            self::$_iniConfig = parse_ini_file(__DIR__."/config/config.ini");

        } 

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
     * Check the value of Get
     *
     * @param array $getValue 
     *
     * @return boolean
     */
    public static function verifGet($getValue)
    {

        if (isset($getValue['key']) 
            //&& isset($getValue['hostname']) 
            //&& isset($getValue['total_space']) 
            //&& isset($getValue['free_space_data']) 
            //&& isset($getValue['use_space_data']) 
            //&& isset($getValue['use_space_save'])
            && self::keyLenCheck($getValue)
        ) {
            return true;
        }
        return false; 

    }

    /**
     * Check the length of the key
     *
     * @param array $getValue get value
     *
     * @return boolean
     **/    
    public static function keyLenCheck($getValue)
    {
      
        if (strlen($getValue['key'])==self::$_keyLength) {
            return true;
        }

        return false;

    }

    /**
     * Add data in db --> infoDisk
     *
     * @param Array $Disk array of data
     *
     * @return boolean
     **/
    private function _addDisk($Disk)
    {
        $req = $this->_db->prepare(
            'INSERT INTO infoDisk(
                pcKey,
                pcName,
                totalSpace,
                dir,
                diskKey,
                saveSpace,
                usedSpace,
                freeSpace,
                lastUpdate
                ) 
                VALUES(
                :pcKey,
                :pcName,
                :totalSpace,
                :dir,
                :diskKey,
                :saveSpace,
                :usedSpace,
                :freeSpace,
                :lastUpdate
                )'
        );

        $req->execute(
            array(
            'pcKey' => $Disk['key'],
            'pcName' => $Disk['hostname'],
            'totalSpace' => $Disk['total'],
            'dir' => $Disk['dir'],
            'diskKey' => $Disk['diskKey'],
            'saveSpace' => $Disk['saveSpace'],
            'usedSpace' => $Disk['usedSpace'],
            'freeSpace' => $Disk['freeSpace'],
            'lastUpdate' => date("Y-m-d H:i:s"))
        );
        return true;
    }
    /**
     * Remove data in db --> infoDisk
     *
     * @param Array $argv array of data
     *
     * @return boolean
     **/
    public function removeDisk($argv)
    {
        $req = $this->_db->prepare(
            "DELETE FROM 'infoDisk'
             WHERE idDisk = :idDisk"
        );

        $req->execute(
            array('idDisk' => $argv[2])
        );
        return true;
    }
    /**
     * Update data in db --> infoDisk
     *
     * @param Array $Disk array of data
     *
     * @return boolean
     **/
    private function _updateDisk($Disk)
    {
        $req = $this->_db->prepare(
            "UPDATE infoDisk SET 
                saveSpace = :saveSpace,
                usedSpace = :usedSpace,
                freeSpace = :freeSpace,
                lastUpdate = :lastUpdate
                WHERE pcKey=:pcKey AND dir=:dir"
        );

        $req->execute(
            array(
            'pcKey' => $Disk['key'],
            'dir' => $Disk['dir'],
            'saveSpace' => $Disk['saveSpace'],
            'usedSpace' => $Disk['usedSpace'],
            'freeSpace' => $Disk['freeSpace'],
            'lastUpdate' => date("Y-m-d H:i:s"))
        );
        return true;
    }

    /**
     * Check if the key exist in the database
     *
     * @param Array $key array of data
     *
     * @return boolean
     **/
    private function _existKey($key)
    {
        $req = $this->_db->prepare('SELECT Key FROM ID WHERE Key = :key');

        $req->execute(
            array(
                'key' => $key['key']
            )
        );

        $data = $req->fetch(PDO::FETCH_ASSOC);
        

        if ($data===false) {

            $reqTemp = $this->_db->prepare('SELECT Key FROM tempID WHERE Key=:key');

            $reqTemp->execute(
                array(
                    'key' => $key['key']
                )
            );

            $dataTemp = $reqTemp->fetch(PDO::FETCH_ASSOC);

            if ($dataTemp===false) {

                $reqInsert = $this ->_db->prepare(
                    'INSERT INTO ID( /** remettre temp id verification  **/
                    Key,
                    PC_name
                    ) 
                    VALUES(
                    :pcKey,
                    :pcName
                    )'
                );

                $reqInsert->execute(
                    array(
                    'pcKey' => $key['key'],
                    'pcName' => $key['hostname'])
                );
                echo "Clé ajouté mais en cours de validation, Essayez plus tard";
                return false;
            } else {
                echo "Clé en cours de validation. Essayez de nouveau plus tard";
                return false;
            }
        }

        return true;
    }
    /**
     * Check if the disk exist in the database
     *
     * @param Array $disk array of data
     *
     * @return integer
     **/
    private function _existDisk($disk)
    {
        $req = $this->_db->prepare(
            'SELECT pcKey,dir FROM infoDisk WHERE pcKey = :key AND dir = :dir'
        );
        $req->execute(
            array(
                'key' => $disk['key'],
                'dir' => $disk['dir']
            )
        );
        $data = $req->fetch(PDO::FETCH_ASSOC);
        
        if ($data===false) {
            return false;
        } else {

            return true;
        }
    }

    /**
     * Add or update disk
     *
     * @return string
     **/
    public function disk()
    {
        $disk= $this->_getInfo;

        if (self::_existKey($disk)) {
            if (!self::_existDisk($disk)) {

                self::_addDisk($disk);


                
                return "disque ajouté";

            } else {
                self::_updateDisk($disk);
                return "disque mis à jour";
            }
        }

        return "insertion/mise à jour";
    }

    /**
     * All disk info
     *
     * @return string
     **/
    private function _allDiskInfo()
    {
        $req = $this->_db->prepare('SELECT * FROM infoDisk');
        $req->execute();
        $data = $req->fetchAll();
        return $data;
    }

    /**
     * Save space
     *
     * @return string
     **/
    private function _totalSaveUsedSpace()
    {
        $req = $this->_db->prepare('SELECT SUM(saveSpace) AS save FROM infoDisk');
        $req->execute();
        $data = $req->fetch(PDO::FETCH_ASSOC);
        return $data["save"];
    }
    /**
     * Total free space
     *
     * @return string
     **/
    private function _totalFreeSpace()
    {
        $req = $this->_db->prepare('SELECT SUM(freeSpace) AS free FROM infoDisk');
        $req->execute();
        $data = $req->fetch(PDO::FETCH_ASSOC);
        return $data["free"];
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
                $number = intval($number/1000000000000);
                return "$number To";
                break; 
            case $number > 1000000000 && $number < 1000000000000:
                $number = intval($number/1000000000);
                return "$number Go";
                break; 

            case $number < 1000000000 && $number > 1000000:
                $number = intval($number/1000000);
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
     * Info  List
     *
     * @return string
     **/
    public  function infosDisk()
    {

        $total = self::_totalFreeSpace();
        $totalSave = self::_totalSaveUsedSpace();
        $all = self::_allDiskInfo();
        echo "-------------------------------------------------------------------\n";
        foreach ($all as $key => $ligne) {
               
            echo "\n Disque "
                 .$ligne["idDisk"]." :\n\n  nom du pc = "
                 .$ligne["pcName"].", Espace total du disque = "
                 .self::_convert($ligne["totalSpace"]).", Espace libre du disque = "
                 .self::_convert($ligne["freeSpace"]).", Espace utilisé par save = "
                 .self::_convert($ligne["saveSpace"]).", Espace utilisé par data = "
                 .self::_convert($ligne["usedSpace"])."\n\n";

            echo "---------------------------------------------------------------\n";
        }

        echo "Espace libre total des disques : ".self::_convert($total)." \n";
        echo "Espace utilisé total du cluster : ".self::_convert($totalSave)." \n";
 
      


    }

    /**
     * Command help
     *
     * @param Array $argv array of data
     *
     * @return string
     **/
    public static function help($argv) 
    {
        $help ="
                NAME
                    
                    
                    --removeDisk, -rm  ----> used to remove disk.
                    --infoDisk, -i     ----> used to get disk info from server
                    --help, -h, -?     ----> used to show this help.

                SYNOPSIS
                    
                    $argv[0]             <option>                      <value>
                                            ^                            ^
                                            |                            |
                                   (info, remove or help)             (Value)

                DESCRIPTION

                EXAMPLES

                    $argv[0] --removeDisk <idDisk>

                    $argv[0] --infoDisk 

                    $argv[0] -help\n";
        return $help;
    }

    /**
     * Return the 3 disk with the more free space
     *
     * @return array
     **/
    private function _topFreeSpace()
    {
        if (!isset(self::$_iniConfig['SourceNumber'])) {
            echo "Erreur : fichier de configuration inexistant.";
            exit;
        }
        $limit = self::$_iniConfig['SourceNumber'];
       
        $req = $this->_db->prepare(
            'SELECT pcName, SUM(totalSpace) AS totalSpace, 
                            SUM(saveSpace) AS saveSpace,
                            SUM(usedSpace) AS usedSpace, 
                            SUM(freeSpace ) AS freeSpace 
            FROM infoDisk  GROUP BY pcKey 
            ORDER BY freeSpace DESC LIMIT 0,'. $limit.''
        );
        $req->execute();
        $data = $req->fetchAll();
        
        if ($data===false) {
            return false;
        } else {
                
            return $data;
        }
    }

    /**
     * Check if we have enougth space to save new torrent
     *
     * @param Array $argv array of data
     *
     * @return boolean
     **/
    public function spaceCheck($argv)
    {
        if (!isset(self::$_iniConfig['freeSpaceForUser'])) {
            echo "Erreur : fichier de configuration inexistant.";
            exit;
        }
        $userSpace = self::$_iniConfig['freeSpaceForUser'];
        $data = self::_topFreeSpace();
        if ($data===false || !isset($argv)) {
            echo "erreur\n\n\n";
            var_dump($data);
            return false;
        } 
        $res=true;
        foreach ($data as $key => $ligne) {
            $freeSpace = $data[$key]['freeSpace'];
            $totalSpace = $data[$key]['totalSpace'];
            $saveSpace = $data[$key]['saveSpace'];
            $usedSpace = $data[$key]['usedSpace'];
            $userFreeSpace = $totalSpace*$userSpace;
            $totForSave= $totalSpace - $userFreeSpace;
            $freeForSave = $totForSave-$saveSpace;
            $freeForSave = $freeForSave/1000000000;
            
            if (round($freeForSave, 2) < round($argv, 2)) {
                $res=false;

            } 
         
        }
        return $res;       
    }

    /**
     * Check if we have enougth space to save new torrent
     *
     * @param Array $argv array of data
     *
     * @return boolean
     **/
    public function addTorrent($FILES)
    {
        $target_dir = "/torrentSave/";
        $target_file = $target_dir . basename($FILES["fileToUpload"]["name"]);
        $uploadOk = 1; 
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $tmp_name = $FILES["fileToUpload"]["tmp_name"];
        $uploaddir = '/srv/http/torrentSave/';
        $uploadfile = $uploaddir . basename($FILES['fileToUpload']['name']);

        if (move_uploaded_file($FILES['fileToUpload']['tmp_name'], $uploadfile.'.torrent')) {
            echo "fonctionne\n";
        } else {
            echo "fonctionne pas\n";
        }

        echo 'Voici quelques informations de débogage :';
        var_dump($FILES);
        $uploadOk = 1;
    }
}


switch(true)
{
case isset($_GET['update']):

    if (UpdateInfoDisk::verifGet($_GET)) {

        $infoDisk = new UpdateInfoDisk($_GET);
        echo $infoDisk->disk();

    }

    break;
case isset($_GET['info']):

    $infoDisk = new UpdateInfoDisk($_GET);
    echo $infoDisk->infosDisk();

    break; 
case isset($_GET['check']):

    $infoDisk = new UpdateInfoDisk($_GET);
     echo $infoDisk->spaceCheck($_GET['size']);


    break; 
case isset($_FILES["fileToUpload"]["tmp_name"]) && is_file($_FILES["fileToUpload"]["tmp_name"]):

        $infoDisk = new UpdateInfoDisk($_GET);
        echo $infoDisk->addTorrent($_FILES);

    

    break;
case $argc > 2 && in_array($argv[1], array('--removeDisk', '-rm')):
                        
    $removeDisk = new UpdateInfoDisk($_GET);
    echo $removeDisk->removeDisk($argv);
    echo "remove";

    break;
case $argc = 2 && in_array($argv[1], array('--install', '-i')):

    $dirInfo = __FILE__;
    shell_exec("ln -s $dirInfo /usr/local/bin/torrentSaveServer");
    echo "\n Installation terminé, torrentSaveServer --help pour affiché l'aide";

    break;
case $argc = 2 && in_array($argv[1], array('--infoDisk', '-info')):

    $infoDisk = new UpdateInfoDisk($_GET);
    echo $infoDisk->infosDisk();
    break;
case $argc = 2 && in_array($argv[1], array('--insert', '-insert')):

    $infoDisk = new UpdateInfoDisk($_GET);
     $infoDisk->spaceCheck($argv[2]);
    break;
case $argc <= 2 || in_array($argv[1], array('--help', '-h', '-?')):
    echo UpdateInfoDisk::help($argv);
    break;
}

