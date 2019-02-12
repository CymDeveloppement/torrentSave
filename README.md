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
    
    public static function verifGet($getValue)
    {
        **Verifiaction des données de la variable $_GET**
    }

    private function _addDisk($Disk)
    {
        **Ajout d'un disque à la base de données**
    }

    private function _updateDisk($Disk)
    {
        **Mise à jours d'un disque dans la base de données**
    }

    private function _existKey($key)
    {
        **Vérification de l'existance de la clé dans la base de données**
    }
    
    private function _existDisk($disk)
    {
        **Vérification de l'existance du disque dans la base de données**
    }

    public function Disk()
    {
        **Permet d'appeller addDisk ou updatDisk en dehors de la classe si certaines conditions sont présentes**
    }
    
    public  function infosDisk()
    {
        **Renvoi les données stockée **
    }

 
 
 
 **SOURCE/PAIR :** 
                                 
  Class Commands
    
    public function verifArgs($argc,$argv)
    {
        **Permet de gèrer l'execution des fonctions en dehors de la classe en fonction des paramètres**
    }
    
    private function _addDisk($argv) 
    {
        **Ajout d'un disque**
    }

    private function _updateDisk() 
    {
        **Mise à jours d'un disque**
    }
 
    private function _removeDisk($argv) 
    {
        **supprime un disque**
    }
   

    private function _help($argv) 
    {
        **Renvoi l'aide conscernant les commandes**
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
                  
                  
                  
                  
