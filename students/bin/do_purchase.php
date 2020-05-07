<?php
namespace ArborShop;

/** from index.php:
 *
 * @var \ArborShop\Database $db
 * @var \ArborShop\Shop     $shop
 * @var \ArborShop\Student  $student
 * 
 * From masquerade.php:
 * 
 * @var string $masqueraded_username
 */

/**
 * @var integer $itemId
 */
$itemId = $_GET['purchase'];

/**
 * @var \ArborShop\array(ShopItem) $item
 */
$item = $shop->getItemById($itemId);

$purchaseHistory = new PurchaseHistory($student, $db);

if (!$purchaseHistory->addPurchase($item)) {
    die("No idea why your purchase failed, but it did...");
}

if ($masqueraded_username != "") {
    $m = "&masquerade_name=$masqueraded_username";
}

header("location: index.php?successful_purchase=$itemId$m");