<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<?php

require "classes.php";

/* We want to do this relative to the base of the site */
$site_urlbase = preg_replace(",^[^/]+//[^/]+/,", "", \ArborShop\Config::$site_url);

/* Trail, then strip the beginning from it */
$breadcrumb_trail = str_replace("$site_urlbase", "", $_SERVER['PHP_SELF']);
$breadcrumb_trail = explode("/", $breadcrumb_trail);

if (end($breadcrumb_trail) == "index.php")
    array_pop($breadcrumb_trail);

for ($i = 1; $i < sizeof($breadcrumb_trail) - 1; $i++) {
    echo "<li class=\"breadcrumb-item\"><a href=\"";
    for ($j = 0; $j < sizeof($breadcrumb_trail) - $i - 1; $j++)
        echo "../";
    echo ".\">$breadcrumb_trail[$i]</a></li>";
}
    echo "<li class=\"breadcrumb-item active\" aria-current=\"page\">" . end($breadcrumb_trail) . "</a></li>";

?>
</ol>
</nav>