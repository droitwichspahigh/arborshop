<?php
namespace ArborShop;

class Purchase
{
    protected $item = null, $purchase_id, $arbor_id, $datetime, $price, $item_id, $collected;

    public function __construct($p) {
        $this->purchase_id = $p[0];
        $this->arbor_id = $p[1];
        $this->datetime = $p[2];
        $this->price = $p[3];
        $this->item_id = $p[4];
        $this->collected = $p[5];
    }
    
    public function getPurchaseId() { return $this->purchase_id; }
    public function getArborId() { return $this->arbor_id; }
    public function getDatetime() { return $this->datetime; }
    public function getPrice() { return $this->price; }
    public function getItemId() { return $this->item_id; }
    public function getCollected() { return $this->collected; }
    
    /**
     * @param \ArborShop\Shop $shop
     * @return \ArborShop\ShopItem
     */
    function shopItem($shop) {
        if ($this->item = null) {
            $this->item = $shop->getShopItemById($this->item_id);
        }
        return $this->item;
    }
    
    function __destruct() {
        /* Nothing */
    }
}

