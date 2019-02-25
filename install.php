<?php
/**
 * PHP Version 7
 * TorrentSave installer Doc Comment
 *
 * @category Installer
 * @package  TorrentSave
 * @author   SCHRODER Bastien <bastien.schroder@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://10.0.10.145/infoDisk.php
 */
$linkTmp = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
$link= str_replace("install.php", "TorrentSave.php.script", $linkTmp);
echo " 
	scriptDir=\"/home/\$USER/.config/torrentSave/TorrentSave.php.script\"
	torrentDir=\"/home/\$USER/.config/torrentSave\"
	torrentCommandDir=\"/usr/local/bin/torrentSave\"
	# Check if  php is installed
	if [ ! -e /usr/bin/php ]
	then
		 echo \"Vous devez installer php.\"
		 exit 0
	fi
	echo \"PHP est bien installé \"
	res=$(php -v | grep \"PHP 7\")
	
	# Check if php is at the right version
	if [ -z \"\$res\" ]
	then
		echo \"Veuillez mettre à jours votre version de php.\"
		exit 0
		
	fi
	 echo \"PHP est à la bonne version\"

	# Check if the dir torrentSave exist
	if [ ! -d \$torrentDir ]
	then
		mkdir \$torrentDir
	else   
	    if [  -e \$scriptDir ]
		then
			rm \$scriptDir   			
		fi
	fi

	cd $torrentDir
	wget -q $link
	sudo mv TorrentSave.php.script \$torrentCommandDir
	sudo chmod +x \$torrentCommandDir
	sudo chmod +r \$torrentCommandDir
	echo \"Fichier téléchargé et installé\"




	";