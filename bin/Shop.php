<?php
namespace bin;

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
        
        $result = dosql("SELECT * FROM products");
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $shopItem = new ShopItem(
                    $row['id'],
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
        foreach ($this->items as $i) {
            if ($i->availableForYearGroup($yeargroup)) {
                $listitem = <<<EOF
<div class="row">
    <div class="col-md-2">$i->getName()</div>
    <div class="col-md-2">$i->getPrice()</div>
    <div class="col-md-8">$i->getDescription()</div>
    <div class="col-md-4">$i->getImg()</div>
</div>
EOF;
                echo $listitem;
            }
        }
    }

    function __destruct($commit = FALSE)
    {
        /* DO_NADA */
    }
}

