<?php
session_start();



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
	             FROM admin 
	             WHERE username = :username 
	             AND password = :password'
	        );

    $req->execute(
        array(
        'username' => $_POST['username'],
        'password' => $_POST['password']
    	)
    );
   
	$_SESSION = $req->fetch(PDO::FETCH_ASSOC);
	

	
}

if (!empty($_SESSION)) {   

    header('Location: index.php');
                    
}

?>

<form method="POST">
	<label for="username">username</label>
	<input type="text" name="username" required/>
	<label for="password">password</label>
	<input type="password" name="password" required/>
	<input type="submit" />
</form>

