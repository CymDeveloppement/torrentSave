#!/bin/env php
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
 * Command Class Doc Comment
 *
 * @category Class
 * @package  Package
 * @author   SCHRODER Bastien <bastien.schroder@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://10.0.10.145/infoDisk.php
 */
Class Commands
{
    private static $_keyLength = 50 ;
    //private static $_linkInfoDisk = "http://10.0.10.145/infoDisk.php";
    /**
     * Verify Args
     *
     * @param Array $argc number of array line
     * @param Array $argv array of data
     *
     * @return boolean
     **/
    public function verifArgs($argc,$argv)
    {
        if (file_exists(self::_getUserDir())) {

            switch(true)
                {
                case $argc > 2 && in_array($argv[1], array('--addDisk', '-add')):
                        echo "=adddisk\n";
                        self::_addDisk($argv[2]);
                    break;
                case $argc > 2 && in_array($argv[1], array('--removeDisk', '-rm')):
                        echo "=rmdisk\n";
                        self::_removeDisk($argv[2]);
                    break; 
                case $argc > 2 && in_array($argv[1], array('--link', '-l')):
                        self::_linkServer($argv[2]);
                    break; 
                case $argc = 2 && in_array($argv[1], array('--unLink', '-ul')):
                        self::_unLinkServer();
                    break;
                case $argc = 2 && in_array($argv[1], array('--send', '-s')):
                        echo self::_updateDisk();
                    break; 
                 case $argc = 2 && in_array($argv[1], array('--infoDisk', '-i')):
                        echo file_get_contents($config['server']."?info");
                    break;  
                case $argc <= 2 || in_array($argv[1], array('--help', '-h', '-?')):
                        echo self::_help($argv);
                    break;
                
                }    

        } else {

            
            switch(true)
                {
                case $argc > 2 && in_array($argv[1], array('--link', '-l')):
                        self::_linkServer($argv[2]);
                    break; 
                case $argc <= 2 && in_array($argv[1], array('--help', '-h', '-?')):
                        echo self::_help($argv);
                    break;
                default :
                    echo "\nVous devez lier un serveur avant de commencer tout autre action, utilisez l'option --link http://www.exemple.com/exemple \n\n";
                
                }    

        }
        
    }

    /**
     * Get the name of current user   
     *
     * @return string
     **/
    private static function _getUserDir() 
    {
        $currUser = get_current_user();
        return "/home/$currUser/.config/torrentSave/Id.txt";
    }

    /**
     * Get Id info 
     *
     * @return array
     **/
    private function _getIdInfo() 
    {
        $data = file_get_contents(self::_getUserDir());
        $dataArray = json_decode($data, true);
        return $dataArray;
    }
    /**
     * Check if key exist 
     *
     * @return boolean
     **/
    private function _keyCheck() 
    {
        if (file_exists(self::_getUserDir())) {
            echo "clé existante";
            return true;


        } else {

            $key["key="] = self::_random();
            if (file_put_contents(self::_getUserDir(), json_encode($key))) {
                echo "nouvelle clé";
                return true;
            }

        }
        return fasle;
    }
    /**
     * Add Disk 
     *
     * @param Array $arg array of data
     *
     * @return boolean
     **/
 
      private function _addDisk($argv) 
    {
        
        if (self::_keyCheck()) {
            echo $argv;
            $config=self::_getIdInfo();
            $fsName= explode("\n", shell_exec("df --output=source $argv"));
            $i = self::_diskCheck($argv);
            if ($i != false) {

                echo "Disk already exist\n";

            } else {

                    $config['disk'][]= array(
                                        "diskKey" => self::_diskKey($argv),
                                        "total" => disk_total_space("$argv"),
                                        "dir"   =>  $argv,
                                        "fsName" => $fsName[1],
                                    );
                    file_put_contents(self::_getUserDir(), json_encode($config));
                    echo "Added disk";

            }
        } else {

            echo "erreur";
        }

    }

    /**
     * Update Disk 
     *
     * @param Array $arg array of data
     *
     * @return boolean
     **/
 
      private function _updateDisk() 
    {
        
        $idInfo = self::_getIdInfo();
        $count = count($idInfo['disk']);

        for ($i = 0; $i < $count; $i++) {
                $ss=$idInfo['disk'][$i]['dir'];
                $saveSpace = explode("\t", shell_exec("du -sk $ss"));
                $usedSpace = explode("\t", shell_exec("du -sk $ss"));
                $hostname =gethostname();
                $diskFreeSpace = disk_free_space($idInfo['disk'][$i]['dir']);
                echo file_get_contents(
                 $idInfo['server']."?update&key=".$idInfo['key'].
                "&dir=".$idInfo['disk'][$i]['dir'].
                "&total=".$idInfo['disk'][$i]['total'].
                "&fsname=".$idInfo['disk'][$i]['fsName'].
                "&saveSpace=".$saveSpace[0].
                "&usedSpace=".$usedSpace[0].
                "&hostname=".$hostname.
                "&freeSpace=".$diskFreeSpace."", "r"

            );    
            echo "Envoi $i fait \n";       
        }  
    }

    /**
     * Remove Disk 
     *
     * @param Array $arg array of data
     *
     * @return boolean
     **/
 
      private function _removeDisk($argv) 
    {

            $config=self::_getIdInfo();
            $i = self::_diskCheck($argv);
            if ($i != false) {
                unset($config['disk'][$i]);
                echo "Removed disk";

            } else {

                    echo "Disk doesn't exist";
                    
            }
    }


    /**
     * Check if disk exist 
     *
     * @param Array $arg array of data
     *
     * @return boolean 
     **/
    private function _diskCheck($argv) 
    {
        $idInfo = self::_getIdInfo();
        $idInfo2 = $idInfo['disk'];
        $count = count($idInfo2);

        for ($i = 0; $i < $count; $i++) {

            if ($idInfo['disk'][$i]['diskKey'] == self::_diskKey($argv)) {

                return $i;

            }
        }
        return false;

    }

 

    /**
     * Generate a randomKey
     *
     * @return string
     **/
    private function _random() 
    {

        $characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $random = '';
        for ($i = 0; $i < self::$_keyLength; $i++) {
            $random .= $characters[rand(0, $charactersLength - 1)];
        }
        $randomKey = hash('sha256', $random);
        return $randomKey;
    }

    /**
     * Generate a new key
     *
     * @param Array $argv array of data
     *
     * @return string
     **/
    private function _diskKey($argv) 
    {

        $fsName= explode("\n", shell_exec("df --output=source $argv"));
        $uuid= explode("\n", shell_exec("blkid --output=value $fsName[1]"));
        $diskKey = hash('sha256', $uuid[0]);
        return $diskKey;
    }

    /**
     * Link the server address 
     *
     * @return boolean
     **/
    private function _linkServer($address) 
    {
        
        if (!file_exists(self::_getUserDir())) {
             $link = array("server" => "$address");
            //$link["server"]= "$address";
            if (file_put_contents(self::_getUserDir(), json_encode($link))) {
                echo "\nServeur lié\n\n";

                return true;
            }
        } else {

             echo "\nUn serveur est déjà lié \n\n";
            return true;

        }
        return fasle;
    }

    /**
     * unLink the server address 
     *
     * @return boolean
     **/
    private function _unLinkServer() 
    {
       
        if (file_exists(self::_getUserDir())) {

            echo "\nSi vous continuez, le serveur ne sera plus lié et les information sur les disque dur \nainsi que la clé d'identification seront detruite. Pour continuer tapez 'yes' : ";
            $handle = fopen ("php://stdin","r");
            $line = fgets($handle);
            if(strtolower(trim($line)) != 'yes'){
                echo "\nAction annulé !\n\n";
                exit;
            }
            fclose($handle);
            echo "\n"; 
            echo "Le serveur n'est plus lié, les informations des disques \ndur et la clé d'identification ont été supprimer aussi\n \n";



            if (unlink(self::_getUserDir())) {
            
                  return true;
            } else {

                echo "\nune erreur est survenue \n\n";
                return false;

            }

        } else {
                echo "\nAucun lien serveur à supprimer \n\n";
                return true;

        }
        return fasle;
    }
    /**
     * Command help
     *
     * @param Array $argv array of data
     *
     * @return string
     **/
    private function _help($argv) 
    {
        $help =
            "
            NAME
                
                --addDisk, -add    ----> used to add a disk.
                --removeDisk, -rm  ----> used to remove disk.
                --send, -s         ----> used to send key and disk data to server
                --infoDisk, -i     ----> used to get disk info from server
                --link, -l         ----> used to link server address
                --unLink, -ul      ----> used to unlink server address
                --help, -h, -?     ----> used to show this help.

            SYNOPSIS
                
                $argv[0]             <option>                      <dir>
                                        ^                            ^
                                        |                            |
                               (add, remove or help)           (Disk directory)

            DESCRIPTION

                1 - You need to link the server address ( --link ).
                2 - Add one Disk or more.
                3 - 

            EXAMPLES
                
                $argv[0] --addDisk /disk1

                $argv[0] --removeDisk /disk1

                $argv[0] --send 

                $argv[0] --infoDisk 

                $argv[0] --link http://wwww.exemple.com/exemple

                $argv[0] --unLink

                $argv[0] -help\n";
        return $help;
    }

}

/**
 * Disk Class Doc Comment
 *
 * @category Class
 * @package  Package
 * @author   SCHRODER Bastien <bastien.schroder@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://10.0.10.145/infoDisk.php
 */
Class Disk
{
    /**
     * Add Disk 
     *
     * @param Array $arg array of data
     *
     * @return boolean
     **/
    private function _addDisk($arg) 
    {


    }

    /**
     * Get Disk 
     *
     * @param Array $arg array of data
     *
     * @return boolean
     **/
    private function _getDisk($arg) 
    {


    }
}
Commands::verifArgs($argc,$argv);