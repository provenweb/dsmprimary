<?php
header("Access-Control-Allow-Origin: *");
include '../../wp-config.php';

$sql = "SELECT p2.ID,p2.post_title,p2.post_name FROM wp_term_relationships tr JOIN wp_posts p ON tr.object_id=p.id JOIN wp_postmeta pm on pm.post_id = p.id JOIN wp_posts p2 on p2.id = pm.meta_value WHERE tr.term_taxonomy_id = 170 ORDER BY p.menu_order;" ;

$sql = "SELECT ID,post_title,post_name FROM wp_posts WHERE post_type='page'";

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