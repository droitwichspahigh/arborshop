<?php
if(!isset($_SERVER['PHP_AUTH_USER'])) {
    header('location: ./../');
}

if(preg_match('/^[0-9]{2}[-_\@.a-zA-Z0-9]+$/', $_SERVER['PHP_AUTH_USER'])) {
	header('location: students');
} elseif(preg_match('/^[-_\@.a-zA-Z0-9]+$/', $_SERVER['PHP_AUTH_USER'])) {
	header('location: staff');
}
?>