<?php
header("Access-Control-Allow-Origin: *");
include '../../wp-config.php';

$sql = "SELECT name,slug FROM wp_7b6fbac120_term_taxonomy INNER JOIN wp_7b6fbac120_terms ON (wp_7b6fbac120_terms.term_id = wp_7b6fbac120_term_taxonomy.term_id) WHERE wp_7b6fbac120_term_taxonomy.taxonomy = 'category' AND slug!='events' AND slug!='misc' AND slug!='newsletter' AND slug!='clubs' ORDER by name" ;

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