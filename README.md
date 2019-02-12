# Intitulé : Réalisation d'un système de sauvegarde/partage de fichier via torrent dans un réseau privé/public.

Deux scripts sont utilisé pour l'authentification des sources ainsi que le stockage des données de ceux-ci :

    -torrentSave.php
    -infoDisk.php
    
-----------------------------------------------------------------------------------------------------------
    
    
   **torrentSave** : se trouve sur la source et permet d'ajouter(--addDisk),supprimer(--remodeDisk à complèter),
                 mettre à joursun disque(--updateDisk) ainsi que d'afficher de l'aide pour les commandes à utiliser(--help).
                 il sert aussi à  créer une clé d'identification unique à ce poste.
                 La clé et les disque ton enregistré au format JSON dans un fichier TXT.
                  
                  Ceci était la partie locale du script mais le but de celui ci est de communiquer avec le serveur.
                  Pour cela nous avons deux commande :
                  
                        --send : Permet d'envoyer les informations des différents disques ainsi que la clé d'identification.
                        
                        --infoDisk : Permet de recevoir les informations du serveur conscernant l'ensemble des disques.
                        
                        
    infoDisk :   Se trouve sur le serveur et 
                  
                  
                  
                  
