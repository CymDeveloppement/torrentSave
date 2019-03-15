<?php
session_start();
ini_set('session.use_strict_mode', '0');
session_regenerate_id();


if (isset($_GET['logout'])) {

	session_destroy();
	session_regenerate_id();
	header('Location: login.php');

}

	
if(!empty($_POST) && isset($_POST['username']) && isset($_POST['password'])){

	function dbConnect()
	{
	   try{
            $db = new PDO('sqlite:/data/DB/database.sqlite');
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(Exception $e) {
            echo "Impossible d'accéder à la base de données: ".$e->getMessage();
            die();
        }
        return $db; 
	}
	$db=dbConnect();
	$req = $db->prepare(
	            'SELECT * 
	             FROM admin'
	        );

   $req->execute();
   
	$_SESSION = $req->fetch(PDO::FETCH_ASSOC);

	if (password_verify ($_POST['username'], $_SESSION["username"]) && password_verify ($_POST['password'], $_SESSION["password"])) {
		header('Location: index.php');
	} else {
		$erreur = "<blockquote class = \"blockquote-error\">
  				<p><em>Erreur, Mauvais nom d'utilisateur ou mot de passe.</em></p>
			  </blockquote>";
	}
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

<div class="container top-25">	
	<div class="row top-25">
		<div class ="column column-50 column-offset-25 ">
			<?php 
				if (isset($erreur)) {
					echo $erreur;
				}
			?>
			<form method="POST">
				<label for="username">Nom d'utilisateur</label>
				<input type="text" name="username" required/>
				<label for="password">Mot de passe</label>
				<input type="password" name="password" required/>
				<input type="submit" />
			</form>
		</div>
	</div>
</div>
</body>
</html>