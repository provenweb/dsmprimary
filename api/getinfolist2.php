<?php
header("Access-Control-Allow-Origin: *");

function html_to_obj($html) {
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    return element_to_obj($dom->documentElement);
}

function element_to_obj($element) {
    $obj = array( "tag" => $element->tagName );
    foreach ($element->attributes as $attribute) {
        $obj[$attribute->name] = $attribute->value;
    }
    foreach ($element->childNodes as $subElement) {
        if ($subElement->nodeType == XML_TEXT_NODE) {
            $obj["html"] = $subElement->wholeText;
        }
        else {
            $obj["children"][] = element_to_obj($subElement);
        }
    }
    return $obj;
}

$remotepage = file_get_contents('http://www.dsmprimary.essex.sch.uk/app-menu/', false);
$strpos = strpos($remotepage,'<ul id="menu-app"');
$remotepage = substr($remotepage, $strpos);
$strpos = strpos($remotepage,'</ul');
$remotepage = substr($remotepage, 0, $strpos+5);

$remotepage = html_to_obj($remotepage);

$menu = $remotepage["children"][0]["children"][0]["children"];

echo '{"items":[';

$count=0;
$string='';

foreach ($menu as $key => $item) {

	$count++;

	$name = $item["children"][0]["href"];
	$title = $item["children"][0]["html"];

	$name = htmlspecialchars($name);
	$title = htmlspecialchars($title);

	$name = str_replace("http://www.dsmprimary.essex.sch.uk","",$name);

	$string.= '{"ID":"'.$count.'","post_title":"'.$title.'","post_name":"'.$name.'"},';


}

$string=substr($string,0,-1);
echo $string;

echo ']}';

?>