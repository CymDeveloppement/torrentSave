# Intitulé : Réalisation d'un système de sauvegarde/partage de fichier via torrent dans un réseau privé/public.

Deux scripts sont utilisé pour l'authentification des sources ainsi que le stockage des données de ceux-ci :

    -torrentSave.php 
    -infoDisk.php
    
-----------------------------------------------------------------------------------------------------------
  
  **ARCHITECTURE DU SYSTEME :**  Chaque pair(source) envoie son identifiant ainsi que les informations sur son/ses 
                                 disque(s) quotidiennement, ceci est fait automatiquement(en cour).
                                 Un pair peut également récupérer les informations conscernant l'espace total des disques                                      enregistrés sur le serveur.
                                 Le serveur s'occupe de la réception des données provenant des différentes sources afin de                                    les enregistrer dans la base de donnée ou de mettre à jours les données déjà présentes.
                                 Un système de vérification des clé d'identifiction est présent sur le serveur ainsi qu'un                                    système de validation/non-validation d'un nouveau pair.
                                 
 **SERVER :**
 
 Class UpdateInfoDisk
{
  
    public function __construct($getInfo)
    {
       
    }
   
    private function _setDb(PDO $db)
    {
       
    }

    private function _dbConnect($db_dir)
    {
       
    }
    
    public static function verifGet($getValue)
    {

    }
    
    public static function keyLenCheck($getValue)
    {
     
    }

    private function _addDisk($Disk)
    {
      
    }

    private function _updateDisk($Disk)
    {
        
    }

    private function _existKey($key)
    {
      
    }
    
    private function _existDisk($disk)
    {
     
    }

    public function Disk()
    {
     
    }

    private function _allDiskInfo()
    {
      
    }

    private function _totalSaveUsedSpace()
    {
      
    }
    
    private function _totalFreeSpace()
    {
       
    }
    
    private function _convert($number)
    {
     
    }
    
    public  function infosDisk()
    {

    }

 
 
 
 **SOURCE/PAIR :** 
                                 
  Class Commands
    
    public function verifArgs($argc,$argv)
    {
    
    }
    
    private static function _getUserDir() 
    {
      
    }
    private function _getIdInfo() 
    {
       
    }
   
    private function _keyCheck() 
    {
        
    }
    
    private function _addDisk($argv) 
    {
    
    }

    private function _updateDisk() 
    {
     
    }
 
    private function _removeDisk($argv) 
    {

    }
    
    private function _diskCheck($argv) 
    {
    
    }

    private function _random() 
    {

    }

    private function _diskKey($argv) 
    {

    }

    private function _help($argv) 
    {
    
    }


  
  
  
  
  
  
  
  
  
  
  
  
  
  
    
   **torrentSave** : se trouve sur la source et permet d'ajouter(--addDisk),supprimer(--remodeDisk à complèter),
                 mettre à joursun disque(--updateDisk) ainsi que d'afficher de l'aide pour les commandes à utiliser(--help).
                 il sert aussi à  créer une clé d'identification unique à ce poste.
                 La clé et les disque ton enregistré au format JSON dans un fichier TXT.
                  
                  Ceci était la partie locale du script mais le but de celui ci est de communiquer avec le serveur.
                  Pour cela nous avons deux commande :
                  
                        --send : Permet d'envoyer les informations des différents disques ainsi que la clé d'identification.
                        
                        --infoDisk : Permet de recevoir les informations du serveur conscernant l'ensemble des disques.
                        
                        
    infoDisk :   Se trouve sur le serveur et 
                  
                  
                  
                  
