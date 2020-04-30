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
                    $row['allowed_yeargroups'],
                    $row['enabled']);
                array_push($this->items, $shopItem);
            }
        }
    }
    
    public function getItems() {
        return $this->items;
    }
    
    /**
     * By default print all listitems, but yeargroup can restrict
     * 
     * It assumes that if yeargroup is set, this is a pupil's list and will have links
     * 
     * @param integer $yeargroup
     */    
    public function outputHtmlListItems($yeargroup = NULL) {
        echo "<hr />";
        foreach ($this->items as $i) {
            if ($i->availableForYearGroup($yeargroup)) {
                $d = $i->getDescription();
                if ($yeargroup == NULL) {
                    /* This will be staff */
                    /* Show which year groups are allowed it */
                    $d = "<span class=\"text-muted\">(Years " . $i->getAvailability() . ")</span> $d";
                    if ($i->isEnabled() == FALSE)
                        $d = "<span class=\"text-muted\">(disabled)</span> $d";
                } else if ($i->isEnabled() == FALSE) {
                    /* Don't show disabled items to kids */
                    continue;
                }
                $p = $i->getPrice();
                $n = $i->getName();
                $img = $i->getImg();
                echo <<<EOF
<div class="row">
    <div class="col-sm-1 text-center">$p points</div>
    <div class="col-sm-2 text-center"><strong>$n</strong></div>
    <div class="col-sm-7">$d</div>
    <div class="col-sm-2 text-center">$img</div>
</div>
<hr />
EOF;
            }
        }
    }

    function __destruct()
    {
        /* DO_NADA */
    }
}

