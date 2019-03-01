<?php

session_start();

if (empty($_SESSION)) {

    header('Location: login.php');
    exit();

}

if ($_SESSION['pseudo']!="admin" && $_SESSION['password']!="mdpadmin") {
    header('Location: login.php');
    exit();
}

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
 	<a class="button" href="#"><i class="fa fa-list-ul fa-fw margin-right"></i>Liste des Torrents</a>
 	<a class="button" href="#"><i class="fa fas fa-plus fa-fw margin-right"></i>Ajouter un Torrent</a>
 	<a class="button" href="#"><i class="fa fa-list-ul fa-fw margin-right"></i>Liste des Pairs</a>
 	<a class="button" href="#"><i class="fa fa-check fa-fw margin-right"></i>Validation des Pairs</a>
 	<a class="button" href="#"><i class="fa fa-check fa-fw margin-right"></i>Validation des suppressions</a>
 	<a class="button" href="#"><i class="fa fa-check fa-fw margin-right"></i>Validation des Torrents</a>

</aside>
<div class="container ">
	<div class="row  ">
    	<div class="column column-100 titre">
    		<h3 class="titre">Liste des Torrents :</h3>
    	</div>

    </div>
    <br/>
  	<div class="row content">
    
	    <div class="column column-100 ">
	        <table >
			    <thead>
			        <tr>
			            <th>Id</th>
			            <th>Nom</th>
			            <th>Etat</th>
			            <th>Sources</th>
			            <th>Télécharger</th>
			        </tr>
			    </thead>
			    <tbody>
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
			        </tr>
			    </tbody>
			</table>


  	</div>

</div>
	
</body>
</html>