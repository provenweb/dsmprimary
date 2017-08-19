<?php
header("Access-Control-Allow-Origin: *");
include '../../wp-config.php';

$sql = "SELECT name,slug FROM wp_7b6fbac120_term_taxonomy INNER JOIN wp_7b6fbac120_terms ON (wp_7b6fbac120_terms.term_id = wp_7b6fbac120_term_taxonomy.term_id) WHERE wp_7b6fbac120_term_taxonomy.taxonomy = 'category' AND slug!='events' AND slug!='misc' AND slug!='clubs' ORDER by name" ;

try {
	$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->query($sql);
	$categories = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;

	$catstr=json_encode($categories);
	$lookfor = '{"name":"7 Blue","slug":"7blue"},{"name":"7 Red","slug":"7red"},';
	if (strpos($catstr,$lookfor)>0) {
		$catstr=str_replace($lookfor,"",$catstr);
	}
	$lookfor = substr($lookfor,0,-1);
	$catstr=str_replace('"}]','"},'.$lookfor."]",$catstr);
	$lookfor = ',{"name":"STEM","slug":"stem"}';
	if (strpos($catstr,$lookfor)>0) {
		$catstr=str_replace($lookfor,"",$catstr);
	}
	$catstr=str_replace('"}]','"}'.$lookfor."]",$catstr);
	echo '{"items":'. $catstr .'}';

} catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}';
}


?>