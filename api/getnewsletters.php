<?php
header("Access-Control-Allow-Origin: *");
include '../../wp-config.php';

$sql = "SELECT post_date,post_name,ID,guid FROM `wp_7b6fbac120_posts` WHERE post_status='publish' and post_type='post' and post_title like '%Newsletter%' order by post_date desc limit 7" ;

try {
	$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->query($sql);
	$posts = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"items":'. json_encode($posts) .'}';
} catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}';
}


?>