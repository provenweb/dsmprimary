<?php
error_reporting(E_ERROR | E_PARSE);
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

$page=$_GET['p'];

$remotepage = file_get_contents('http://www.dsmprimary.essex.sch.uk'.$page, false);
$strpos = strpos($remotepage,'<h1 class="page-title">');
$htmlpage = substr($remotepage, $strpos+23);
$strpos = strpos($htmlpage,'</h1>');
$title = html_entity_decode(substr($htmlpage, 0, $strpos));

$strpos = strpos($remotepage,'wh-content-inner">');
$htmlpage = "<div>".substr($remotepage, $strpos+18);
$strpos = strpos($htmlpage,'wh-sidebar');
$content = substr($htmlpage, 0, $strpos-12);

$content = str_replace('"',"'",$content);

$content = preg_replace("/class\s*=\s*'[^\']*[^\']*'/", "", $content);
$content = preg_replace("/data-vc-container\s*=\s*'[^\']*[^\']*'/", "", $content);
$content = preg_replace("/data-vc-tabs\s*=\s*'[^\']*[^\']*'/", "", $content);
$content = preg_replace("/data-vc-tab\s*=\s*'[^\']*[^\']*'/", "", $content);
$content = preg_replace("/data-vc-accordion\s*=\s*'[^\']*[^\']*'/", "", $content);

$content = str_replace("data-vc-accordion","",$content);
$content = str_replace(" data-vc-tabs","",$content);
$content = str_replace(" data-vc-tab","",$content);

$content = str_replace("\n"," ",$content);
$content = str_replace("\t"," ",$content);

$content = strip_tags($content, '<br><a><h1><h2><h3><h4><li><b><i><em><strong><p><img>');

$content = str_replace("rel='bookmark'","",$content);
$content = str_replace("  "," ",$content);
$content = str_replace("  "," ",$content);
$content = str_replace("  "," ",$content);
$content = str_replace("  "," ",$content);
$content = str_replace(" > ",">",$content);

$content = str_replace("a style='' ","a ",$content);
$content = str_replace("a href='#","a style='color:#000;text-decoration:none' href='#",$content);
$content = str_replace("<img ","<img style='max-width: 100%;height: auto;border-radius: 10px;box-shadow: 1px 1px 9px #aaa;margin-bottom: 9px;' ",$content);
$content = str_replace("<p><a href","<p><a target='_system' class='btnstyle' onclick='window.open(this.href,\\\"_system\\\");return false;' href",$content);
$content = str_replace("<a href","<a target='_system' style='color: #1AB7B3;' onclick='window.open(this.href,\\\"_system\\\");return false;' href",$content);

$imgcontent = strip_tags($content, '<img>');
$imgcontent = html_to_obj($imgcontent);

$images = $imgcontent["children"][0]["children"][0]["children"];
if (empty ($images)) { $images = $imgcontent["children"][0]["children"]; }

$gallery='';

foreach ($images as $key => $item) {

	$src = $item["src"];
	$tag = $item["tag"];

	if ($tag=="img") { $gallery.= "<a href='".$src."' rel='external'><img src='".$src."' alt='' /></a>"; }

}





// "attachments":"<a class='ui-btn ui-btn-corner-all ui-shadow ui-btn-up-b' target='_system' onclick='window.open(\"http://www.frontstreetprimary.co.uk/wp-content/uploads/2014/09/Nursery-Admission-Policy-2016-2017.docx\", \"_system\")' ><span class='ui-btn-inner ui-btn-corner-all'><span class='ui-btn-text'>Nursery Admission Policy 2016 2017</span></span></a><a class='ui-btn ui-btn-corner-all ui-shadow ui-btn-up-b' target='_system' onclick='window.open(\"http://www.frontstreetprimary.co.uk/wp-content/uploads/2014/09/Nursery-Application-Form-2016-2017.doc\", \"_system\")' ><span class='ui-btn-inner ui-btn-corner-all'><span class='ui-btn-text'>Nursery Application Form 2016 2017</span></span></a><a class='ui-btn ui-btn-corner-all ui-shadow ui-btn-up-b' target='_system' onclick='window.open(\"http://www.frontstreetprimary.co.uk/wp-content/uploads/2014/09/NEW-PROSPECTUS-2016.pdf\", \"_system\")' ><span class='ui-btn-inner ui-btn-corner-all'><span class='ui-btn-text'>new-prospectus-2016</span></span></a><a class='ui-btn ui-btn-corner-all ui-shadow ui-btn-up-b' target='_system' onclick='window.open(\"http://www.frontstreetprimary.co.uk/wp-content/uploads/2014/09/Letter-from-Westminster-regarding-school-performance.pdf\", \"_system\")' ><span class='ui-btn-inner ui-btn-corner-all'><span class='ui-btn-text'>Letter from Westminster regarding school performance</span></span></a><a class='ui-btn ui-btn-corner-all ui-shadow ui-btn-up-b' target='_system' onclick='window.open(\"http://www.frontstreetprimary.co.uk/wp-content/uploads/2014/09/Admissions-2016.docx\", \"_system\")' ><span class='ui-btn-inner ui-btn-corner-all'><span class='ui-btn-text'>admissions-2016</span></span></a>",
// "image":"<img x='683' y='403' src='http://www.frontstreetprimary.co.uk/image.php?w=600&h=320&zc=1&a=l&q=50&src=http://www.frontstreetprimary.co.uk/wp-content/uploads/2014/09/reception.jpg' class='attachment-post-thumbnail size-post-thumbnail wp-post-image' alt='' />",

?>

{"items":[

		{"title":"<?php echo $title;?>",
		"content":"<?php echo $content;?>",
		"attachments":"",
		"image":"",
		"gallery":"<?php echo $gallery; ?>"}

]}

