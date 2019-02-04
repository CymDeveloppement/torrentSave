<?php

//var_dump(is_file("/data/manage/KeyId/hh.txt"));

$db_dir = '/data/DB/database.sqlite';
//insertKey();


$pdo = db_connect($db_dir);
$res=$pdo->query("SELECT Key FROM ID WHERE Key='". $_GET['Key'] ."' AND PC_name='". $_GET['hostname'] ."'"); 
$check=$res->fetch(PDO::FETCH_ASSOC);;

if($check['Key']){ 
			
	$HD_exist_req=$pdo->query("SELECT id_disk FROM infoDisk WHERE pc_key='". $_GET['Key'] ."' AND pc_name='". $_GET['hostname'] ."'"); 
	$HD_exist=$HD_exist_req->fetch(PDO::FETCH_ASSOC);; 

	if($HD_exist['id_disk']){
		file_put_contents ('/data/DB/exist.txt' , $HD_exist['id_disk'],FILE_APPEND);
		updateKey();
		}else{
			file_put_contents ('/data/DB/noexist.txt' , $HD_exist['id_disk'],FILE_APPEND);
			insertHD();
		}

}else{
		
	insertKey();
	file_put_contents ('/data/DB/ICI.txt' , $check['Key'],FILE_APPEND);
	   
}


function updateKey(){
	$key = $_GET['Key'];
  	$hostname = $_GET['hostname'];
  	$free_space = $_GET['free_space_data'];
  	$total_space = $_GET['total_space']; 
  	$used_space_save = $_GET['used_space_save'];
  	$used_space_data = $_GET['used_space_data'];
  	$last_update = date("Y-m-d H:i:s"); 
  	$pdo = db_connect($GLOBALS['db_dir']); 
	$pdo->query("UPDATE infoDisk SET 
						   total_space = '".$key."',
	                       free_space = '".$free_space."',
	                       used_space_save = '".$used_space_save."',
	                       used_space_data ='".$used_space_data."',
	                       last_update ='".$last_update."',
	                       pc_name ='".$hostname."',
	                       pc_key ='".$key."'
	                       WHERE pc_key='".$key."' AND pc_name='".$hostname."'");

}
function insertHD(){
 	$key = $_GET['Key'];
  	$hostname = $_GET['hostname'];
  	$free_space = $_GET['free_space_data'];
  	$total_space = $_GET['total_space']; 
  	$used_space_save = $_GET['used_space_save'];
  	$used_space_data = $_GET['used_space_data'];
  	$last_update = date("Y-m-d H:i:s"); 
    $pdo = db_connect($GLOBALS['db_dir']); 
    $req="INSERT INTO infoDisk (total_space, free_space, used_space_save, used_space_data, last_update, pc_name, pc_key) 
                VALUES ('".$total_space."','".$free_space."','".$used_space_save."','".$used_space_data."','".$last_update."','".$hostname."','".$key."')";
    file_put_contents ('/data/DB/req.txt' , $req,FILE_APPEND);
    $pdo->query($req);



 }
function insertKey(){
	$key = $_GET['Key'];
  	$hostname = $_GET['hostname'];
    $pdo = db_connect($GLOBALS['db_dir']); 
    $pdo->query("INSERT INTO ID (Key, PC_name) 
                VALUES ('".$key."','".$hostname."')");
  
    
}

function db_connect($db_dir){
    try{
	    $pdo = new PDO('sqlite:'.$db_dir);
	    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
	} catch(Exception $e) {
    	echo "Impossible d'accéder à la base de données SQLite : ".$e->getMessage();
    	die();
	}
   return $pdo;

}