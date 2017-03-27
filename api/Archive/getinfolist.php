<?php
header("Access-Control-Allow-Origin: *");
include '../../wp-config.php';

$sql = "SELECT ID,post_title,post_name FROM `wp_7b6fbac120_posts` WHERE post_type='page' AND post_parent='3500' and post_status='publish' AND ID!='13560' order by menu_order,post_title LIMIT 8" ;

try {
	$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->query($sql);
	$categories = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"items":'. json_encode($categories) .'}';
} catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}';
}


?>