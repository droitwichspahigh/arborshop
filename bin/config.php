<?php
$brand_name = "The Droitwich Spa High School Rewards Shop";
$site_name = "The Rewards Shop";

$version = "Version 0.1 - April 2020";

if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
	$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	header('HTTP/1.1 301 Moved Permanently');
	header('location: ' . $redirect);
	exit();
}
?>