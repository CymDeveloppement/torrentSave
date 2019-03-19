<?php

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
	            'INSERT INTO admin(username, password) 
	             VALUES(:username, :password)'
	        );

    $req->execute(
        array(
        'username' => password_hash($_POST['username'], PASSWORD_DEFAULT),
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
    	)
    );
   	$hh =  password_hash($_POST['username'], PASSWORD_DEFAULT);
	echo $hh;
	
}

?>

<form method="POST">
	<label for="username">username</label>
	<input type="text" name="username" required/>
	<label for="password">password</label>
	<input type="password" name="password" required/>
	<input type="submit" />
</form>

