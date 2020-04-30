<?php

require "../../bin/auth.php";

if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/shop/img/product"))
    mkdir($_SERVER['DOCUMENT_ROOT'] . "/shop/img/product");

require "../../bin/tinyfilemanager.php";