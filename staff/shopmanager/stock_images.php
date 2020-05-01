<?php

require "../../bin/auth.php";
require "../../bin/breadcrumbs.php";

$productsdir = "../../img/product";

if (!file_exists($productsdir))
    mkdir($productsdir);

require "../../bin/tinyfilemanager.php";