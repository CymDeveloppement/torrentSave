<?php
require 'infoWeb.php';  				
?>
<!DOCTYPE html>
<html>
<?php  require 'require/head.php' ?>
<body>
<?php  require 'require/navbar.php' ?>
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
			            <th>Taille</th>
			            <th>Etat</th>
			            <th>Sources</th>
			            <th>Télécharger</th>
			        </tr>	
			    </thead>
			    <tbody>
			    	<?php 
			    		$infoWeb = new InfoWeb();
    					$infoWeb->torrentList();
			        ?>
			    </tbody>
			</table>
  		</div>
	</div>
</div>
	
</body>
</html>