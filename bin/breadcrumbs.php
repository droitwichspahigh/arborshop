<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<?php

/* We want to do this relative to the base of the site */
$site_urlbase = preg_replace("/^[^\/]+\//", "", $site_url);

/* Trail, then strip the beginning from it */
$breadcrumb_trail = str_replace("$site_urlbase", "", $_SERVER['PHP_SELF']);
$breadcrumb_trail = explode("/", $breadcrumb_trail);

for ($i = 1; $i < sizeof($breadcrumb_trail) - 2; $i++) {
    echo "<li class=\"breadcrumb-item\"><a href=\"";
    for ($j = 0; $j < sizeof($breadcrumb_trail) - $i - 2; $j++)
        echo "../";
    echo "\">$breadcrumb_trail[$i]</a></li>";
}
    echo "<li class=\"breadcrumb-item active\" aria-current=\"page\">" . $breadcrumb_trail[sizeof($breadcrumb_trail)-2] . "</a></li>";

?>
</ol>
</nav>