<?php
	function getBD(){
		$bdd = new PDO('mysql:host=sql211.infinityfree.com;dbname=if0_36368472_prix_nobel;charset=utf8','if0_36368472', 'EamGfcaHBI0k');
		return $bdd;
	}
?>