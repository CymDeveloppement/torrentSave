# Intitulé : Réalisation d'un système de sauvegarde/partage de fichier via torrent dans un réseau privé/public.

Deux scripts sont utilisé pour l'authentification des sources ainsi que le stockage des données de ceux-ci :

    -torrentSave.php ---> source/pair
    -infoDisk.php    ---> serveur
    
-----------------------------------------------------------------------------------------------------------
  
  ## ARCHITECTURE DU SYSTEME - Ligne de commande :  
 
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
 
 2. Le serveur envoi le torrent aux X( X étant le nombre de sources voulue indiqué dans config.ini) pairs ayant le plus         d'espace libre pour avoir de nouvelles sources et donc plus de sauvegarde.
 
                              
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
    
    private function _firstWaittingTorrent($disk)
    {
        **Récupération des informations sur un des torrents en attente de pairs et n'étant pas déjà sauvegardé par la source           concernée.**
    }
    
    private function _checkSend($disk)
    {
        **Vérification de la disponibilité du torrent à envoyer, de l'espace disponible de la source ainsi que                     l'existance du torrent sur la source**
    }
    
    private function _torrentStatus($get)
    {
        **Valide le torrent quand le nombre de sources de celui-ci est égal au nombre de sources indiqué dans config.ini**
    }
    
    private function _linkTorrentTo($get)
    {
        **Lie un torrent à un pair dans la liste de partage**
    }
    
    public function sendTorrent($torrent)
    {
        **Envoi du torrent à la source**
    }
    
    public function Disk()
    {
        **Permet d'appeller addDisk ou updatDisk en dehors de la classe si certaines conditions sont présentes**
    }
    
    public  function infosDisk()
    {
        **Renvoi les données stockées **
    }
    
    public function addTorrent($FILES, $POST)
    {
        **Ajoute le torrent envoyé par la source à la base de données**
    }
    
    public function uninstall($value)
    {
        **Détruit toutes les données d'une source suite à sa demande**
    
    }

 **infoDisk** :   Se trouve sur le serveur et permet d'ajouter, supprimer, mettre à jours un disque dans la base de donnée grâce aux informations envoyées par les différentes sources avec le paramètre ?update placé dans l'URL ou de renvoyer les informations conscernant l'ensemble des disques avec le paramètre ?infoDisk.
Permet aussi d'ajouter des torrents à la base de données et de les lier à un pair.
 
 
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
        **Mise à jours d'un disque avec en retour la possibilité d'avoir un torrent à télécharger**
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
    
    private function _save($file) 
    {
        **Créer un torrent puis envoi de celui-ci au serveur**
    }
    
    private function _uninstall()
    {
        **Suppression de torrentSave de la source ainsi que les informations le concernant sur le serveur**
    }
    
    
   **torrentSave** : se trouve sur la source et permet d'ajouter(--addDisk),supprimer(--remodeDisk à complèter),
                 mettre à joursun disque(--updateDisk) ainsi que d'afficher de l'aide pour les commandes à utiliser(--help).
                 Permet aussi de créer une clé d'identification unique au a la source.
                 La clé et les disque ton enregistré au format JSON dans un fichier TXT.
                 Ceci était la partie locale du script mais le but de celui ci est de communiquer avec le serveur.
                 Pour cela nous avons:
                  
    --send : Permet d'envoyer les informations des différents disques ainsi que la clé d'identification puis si les             conditions sont présentes, recevoir un torrent à télécharger.
                        
    --infoDisk : Permet de recevoir les informations du serveur conscernant l'ensemble des disques.
    
    --save : Permet de créer un torrent et de l'envoyer au serveur.
    
    --link/--unlink : Permet de lier ou délier l'adresse du serveur
    
    --uninstall : Suppression de torrentSave de la source ainsi que les informations le concernant sur le serveur 
    
   ### Installation 
   
**Pre-Requis :**

         - php
         
**Client : Installation automatique - torrentSave**
   
        curl -sSL http://10.0.10.145/install.php | bash
        
**Serveur : Installation automatique des commandes - torrentSaveServer **
    
        php <lien vers infoDisk.php> --install
        
**Serveur : Paramétrage **

       <Répertoire de infoDisk.php>/config/config.ini
       
       Dans ce fichier vous pourrez indiquer le nombre de sources 
       voulue ainsi que le pourcentage du disque accordé à l'utilisateur.
       
       
-----------------------------------------------------------------------------------------------------------
  
  ## DESCRIPTION DU SYSTEME - Interface Web : 
  
  L'interface Web permet à l'administrateur de valider les nouveaux pairs, d'afficher les différentes informations liées aux torrents, disques et pairs ainsi que de télécharger les torrents.
  
  
 **Cas 1:**
 
 1. L'administrateur se connecte à l'interface.
        
 2. Il se rend sur la page de validation des pairs grâce à la barre de navigation située à gauche.
        
 3. Il valide une de nouvelles pairs en attentes.    
 
  **Cas 2:**
 
 1. L'administrateur se connecte à l'interface.
        
 2. Il se rend sur la page listant les torrents grâce à la barre de navigation située à gauche.
        
 3. Il télécharge les torrents dont il a besoin.     



-----------------------------------------------------------------------------------------------------------
  
  ## INSTRUCTION D'UTILISATION:
  
  **Source / pair:**
  
  Après Installation de torrentSave, vous devrez :
    -Lier un serveur.(torrentSave --link http://serverAdress/infoDisk.php)
    -Ajouter un disque pour la sauvegarde des torrents.(torrentSave -add /diskDirectory)
    -Envoyer vos données au serveur.(Une validation par l'administrateur sera nécessaire pour vous compter à la liste des       pairs).(torrentSave --send)
    
   **Serveur:**
   
   Après installation de torrentSaveServer ainsi que des autres fichiers sur votre serveur web, vous devrez : 
     -Créer la base de données(de préférence sqlite) puis les tables grâce aux requêtes présentes dans SQLRequest.sql 
     -Modifier le chemin de votre base de données dans infoWeb.php, login.php, register.php , infoDisk.php ainsi que               announce.php.
   
   **Cas 2:**
     
   Pour l'interface web vous devrez : 
     -Créer votre identifiant administrateur grace à <URL>/register.php puis supprimez register.php après création des             identifiants.
     -Vous pourrez vous connecter au panel via <URL>/login.php.
  
  
  

            
        
                        
                        
   
                  
                  
                  
                  
