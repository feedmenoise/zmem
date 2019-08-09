<?php 
ob_start("ob_gzhandler");
session_start();
ini_set('display_errors', 0);

require_once "./inc/parser.class.php";
require_once "./inc/nav.class.php";
require_once "./inc/contracts.class.php";
require_once "./inc/recipients.class.php";
require_once "./inc/companies.class.php";
require_once "./inc/adresat.class.php";
require_once "./inc/generate.class.php";
require_once "./inc/pdf.class.php";
require_once "./inc/fpdf.php";

$nav = new Nav();

$menu = $nav->mainMenu();

if ($_GET["p"] == "contracts") {
	$contracts = new Contracts();
	$content = $contracts->showContent();
}
else if ($_GET["p"] == "recipients") {
	$recipients = new Recipients();
	$content = $recipients->showContent();
}
else if ($_GET["p"] == "companies") {
	$companies = new Companies();
	$content = $companies->showContent();
}
else if ($_GET["p"] == "adresat") {
	$adresat = new Adresat();
	$content = $adresat->showContent();
}

else {
	$content = "pidor";
}


$main_site = new parser("index.tpl");
$main_site->get_tpl();
$main_site->set_tpl("%MENU%", $menu);
$main_site->set_tpl("%CONTENT%",$content);
print $main_site->tpl_parse();

?>