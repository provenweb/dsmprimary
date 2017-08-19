<?php
header("Access-Control-Allow-Origin: *");
include '../../wp-config.php';

$sql = "SELECT p2.ID,p2.post_title,p2.post_name FROM wp_7b6fbac120_term_relationships tr INNER JOIN wp_7b6fbac120_posts p ON tr.object_id = p.id INNER JOIN wp_7b6fbac120_postmeta pm ON pm.post_id = p.id INNER JOIN wp_7b6fbac120_posts p2 ON pm.meta_value = p2.id WHERE tr.term_taxonomy_id='164' ORDER BY p.menu_order" ;

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