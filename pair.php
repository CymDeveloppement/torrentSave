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
    		<h3 class="titre">Liste des Pairs :</h3>
    	</div>

    </div>
    <br/>
  	<div class="row content">
	    <div class="column column-100 ">
	        <table >
			    <thead>
			        <tr>
			            <th>Nom</th>
			            <th>Nombre de Sauvegardes </br>en partages</th>
			            <th>Nombre de Sauvegardes</br> ajoutées</th>
			            <th>Nombre de disques</th>
			            <th>Dernière </br>mise a jours</th>
			        </tr>	
			    </thead>
			    <tbody>
			    	<?php 
			    		$infoWeb = new InfoWeb();
    					$infoWeb->pairList();
			        ?>
			    </tbody>
			</table>
  		</div>
	</div>
</div>	
</body>
</html>