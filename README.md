# Intitulé : Réalisation d'un système de sauvegarde/partage de fichier via torrent dans un réseau privé/public.

Deux scripts sont utilisé pour l'authentification des sources ainsi que le stockage des données de ceux-ci :

    -torrentSave.php ---> source/pair
    -infoDisk.php    ---> serveur
    
-----------------------------------------------------------------------------------------------------------
  
  ## ARCHITECTURE DU SYSTEME :  
 
 Le serveur doit récupérer les informations des différents pairs pour savoir à qui attribuer les torrents en fonction de   
 l'espace restant sur leurs disque dur. 
 
 Pour cela il faut attribuer à chaque pair un identifiant unique afin de pouvoir reconnaître l'appartenance des disques dans
 la base de données.
 
 **Cas 1:**
 
 1. Un pair ajoute un disque pour la première fois, cela crée au même moment un identifiant unique.
        
 2. Le pair envoie son identifiant ainsi que les informations relative aux disques dur.
        
 3. Le serveur reçoit les informations du pair, controle la clé et les disques puis enregistre/met à jours ceux-ci
        pour ensuite attribuer des torrents ou non au pair(en fonction des besoins), afin qu'il devienne une source.
 
 **Cas 2:** 
 
 1. Une source envoie un torrent au serveur.
 
 2. Le serveur envoi le torrent aux X pairs ayant le plus d'espace libre pour avoir de nouvelles sources et donc plus de
 sauvegarde.
 
 **Cas 3:**
 
 1. Quelqu'un veut supprimer un torrent.
 2. Le serveur fait la liste des sources le possèdant.
 3. Quand les pairs auront envoyer leurs informations au serveur, il leurs renverra un tableau JSON contenant
 les torrents à supprimer.
 
                              
 ### SERVEUR :
 
 Class UpdateInfoDisk
{
    
    public static function verifGet($getValue)
    {
        **Vérifiaction des données de la variable $_GET**
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
        **Renvoi les données stockées **
    }

 **infoDisk** :   Se trouve sur le serveur et permet d'ajouter, supprimer, mettre à jours un disque dans la base de donnée grâce aux informations envoyées par les différentes sources avec le paramètre ?update placé dans l'URL ou de renvoyer les informations conscernant l'ensemble des disques avec le paramètre ?infoDisk.
 
 
 ### SOURCE/PAIR :
                                 
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
   
    private function _linkServer($address)
    {
        **Permet de lier l'adresse du serveur**
    }
    
    private function _unLinkServer()
    {
        **Supprime l'adresse du serveur ainsi que la clé et le tableau d'information des disques**
    }

    private function _help($argv) 
    {
        **Renvoi l'aide conscernant les commandes**
    }
    
   **torrentSave** : se trouve sur la source et permet d'ajouter(--addDisk),supprimer(--remodeDisk à complèter),
                 mettre à joursun disque(--updateDisk) ainsi que d'afficher de l'aide pour les commandes à utiliser(--help).
                 Permet aussi de créer une clé d'identification unique au a la source.
                 La clé et les disque ton enregistré au format JSON dans un fichier TXT.
                 Ceci était la partie locale du script mais le but de celui ci est de communiquer avec le serveur.
                 Pour cela nous avons deux commande :
                  
    --send : Permet d'envoyer les informations des différents disques ainsi que la clé d'identification.
                        
    --infoDisk : Permet de recevoir les informations du serveur conscernant l'ensemble des disques.
    
   ### Installation 
   
   
**Méthode 1 : Installation automatique**
   
        curl -sSL http://10.0.10.145/install.sh | bash
        
            
**Méthode 2 : Téléchargement manuel de l'installer + éxécution** 
   
            wget -O install.sh https://10.0.10.145/install.sh
            sudo bash install.sh
            
        
                        
                        
   
                  
                  
                  
                  
