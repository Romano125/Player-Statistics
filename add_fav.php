<?php
	session_start();
	
	$db = new mysqli('127.0.0.1', 'root', '', 'player_stats');

	$q = "INSERT INTO users_igrac (ID, reg_br_igr) VALUES (" . $_SESSION['id'] . ", '" . $_GET['id'] . "')";

	$db->query($q);

	header('Location: player.php?id=' . $_GET['id']);
?>