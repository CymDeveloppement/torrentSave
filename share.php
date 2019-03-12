<?php

session_start();
session_regenerate_id();

if (empty($_SESSION['username'])) {

    header('Location: login.php');
    exit();

}
require 'infoWeb.php';

    				
?>

<!DOCTYPE html>
<html>
<head>
    <title>TorrentSave</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.3.0/milligram.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.3.0/milligram.css.map">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.3.0/milligram.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.3.0/milligram.min.css.map">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

</head>
</head>
<body>

<aside class="column column-20">
  	<h2>Torrent Save</h2>
  	<br/>
 	<a class="button" href="index.php"><i class="fa fa-list-ul fa-fw margin-right"></i>Liste des Torrents</a>
 	<a class="button" href="share.php"><i class="fa fa-list-ul fa-fw margin-right"></i>Liste des Sauvegardes</a>
 	<a class="button" href="#"><i class="fa fa-list-ul fa-fw margin-right"></i>Liste des Pairs</a>
 	<a class="button" href="#"><i class="fa fa-check fa-fw margin-right"></i>Validation des Pairs</a>
 	<a class="button" href="#"><i class="fa fa-check fa-fw margin-right"></i>Validation des suppressions</a>
 	<a class="button" href="#"><i class="fa fa-check fa-fw margin-right"></i>Validation des Torrents</a>
 	<a class="button" href="login.php?logout"><i class="fa fa-check fa-fw margin-right"></i>Déconnexion</a>

</aside>
<div class="container ">
	<div class="row  ">
    	<div class="column column-100 titre">
    		<h3 class="titre">Liste des Sauvegardes :</h3>
    	</div>

    </div>
    <br/>
  	<div class="row content">
    ss
	    <div class="column column-100 ">
	        <table >
			    <thead>
			        <tr>
			            <th>Id</th>
			            <th>Nom du torrent</th>
			            <th>Source</th>
			            <th>Disponibilité</th>
			        </tr>	
			    </thead>
			    <tbody>
			    	<?php 
			    		$infoWeb = new InfoWeb();
    					$infoWeb->shareList();
    				
/*		

			        <tr>
			            <td>value</td>
			            <td>value</td>
			            <td>value1</td>
			            <td>value</td>
			            <td><a href="#"><i class="fas fa-download"></i></a></td>
			        </tr>
			        <tr>
				        <td>value</td>
				        <td>value</td>
				        <td>value</td>
				        <td>value</td>
				        <td><a href="#"><i class="fas fa-download"></i></a></td>
			        </tr>*/
			        ?>
			    </tbody>
			</table>


  	</div>

</div>
</div>
	
</body>
</html>