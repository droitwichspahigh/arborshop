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
     * Array of ShopItems
     * 
     * @var array(ShopItem) items
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
        
        $result = dosql("SELECT * FROM items ORDER BY price ASC");
        
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
     * Print a shop without links, for staff to peruse.
     * 
     * First the enabled items are printed, then disabled.
     */
    
    public function staffShop() {
        $i = null;
        
        echo "<hr />";
        foreach ($this->items as $i) {
            if ($i->isEnabled())
                $i->printRow(true, "<span class=\"text-muted\">(Years " . $i->getAvailability() . ")</span> ");
        }
        foreach ($this->items as $i) {
            if (!$i->isEnabled())
                $i->printRow(false);
        }
    }
    
    /**
     * Print a shop with links for available items
     * 
     * Available being those where:
     *  - $yeargroup matches the item's availability
     *  - item is enabled
     *  - item is affordable (i.e. costs <= $balance)
     * 
     * @param integer $yeargroup
     * @param integer $balance
     */
    public function studentShop($yeargroup, $balance) {
        echo "<hr />";
        foreach ($this->items as $i) {
            if (!$i->availableForYearGroup($yeargroup))
                continue;
            if (!$i->isEnabled())
                continue;
            if ($i->getPrice() > $balance) {
                $i->printRow(false);
            } else {
                $i->printRow(true, "", true);
            }
        }
    }
    
    function __destruct()
    {
        /* DO_NADA */
    }
}

