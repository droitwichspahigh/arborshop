<?php

namespace bin;

require "ShopItem.php";

/**
 * Gets a list of ShopItems from the database
 *
 * @author reescm
 *        
 */
class Shop
{
    /**
     * @var array(ShopItem)
     */
    protected $items;

    /**
     * Pass a database connection to this to make a Shop.
     *  
     * @param \mysqli $conn
     */
    public function __construct($conn)
    {
        $this->items = [];
        
        $result = dosql("SELECT * FROM items");
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $shopItem = new ShopItem(
                    $row['item_id'],
                    $row['name'],
                    $row['price'],
                    $row['description'],
                    $row['imgpath'],
                    $row['allowed_yeargroups']);
                array_push($this->items, $shopItem);
            }
        }
    }
    
    public function getItems() {
        return $this->items;
    }
    
    /**
     * By default print all listitems, but yeargroup can restrict
     * @param integer $yeargroup
     */    
    public function outputHtmlListItems($yeargroup = NULL) {
        echo <<<EOF
<div class="row">
    <div class="col-1">Name</div>
    <div class="col-1">Price</div>
    <div class="col-8">Description</div>
    <div class="col-2">Image</div>
</div>
EOF;
        foreach ($this->items as $i) {
            if ($i->availableForYearGroup($yeargroup)) {
                $p = $i->getPrice();
                $n = $i->getName();
                $d = $i->getDescription();
                $img = $i->getImg();
                $listitem = <<<EOF
<div class="row">
    <div class="col-1">$p</div>
    <div class="col-1">$n</div>
    <div class="col-8">$d</div>
    <div class="col-2">$img</div>
</div>
EOF;
                echo $listitem;
            }
        }
    }

    function __destruct()
    {
        /* DO_NADA */
    }
}

