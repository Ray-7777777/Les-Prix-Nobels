<?php
#fonction pour se connecter à la base sql
function getBD(){
	$bdd = new PDO('mysql:host=localhost;dbname=prix_nobel;charset=utf8', 'root', '');
	return $bdd;
	}
?>