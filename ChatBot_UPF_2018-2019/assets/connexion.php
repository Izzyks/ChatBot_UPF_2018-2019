<?php
    try{//connextion de la base de donnée
		$bdd = new PDO('mysql:host=localhost;
						dbname=chatbot;
						charset=utf8', 'root', '');
				array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
?>