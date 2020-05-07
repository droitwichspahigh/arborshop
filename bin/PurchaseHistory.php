<?php
namespace ArborShop;

class PurchaseHistory
{
    protected $student;
    protected $purchases = null;
    protected $db = null;
    
    /** 
     * @param \ArborShop\Student $student
     * @param \ArborShop\Database $db
     */
    public function __construct($student, $db = null)
    {
        if ($db == null) {
            $db = new Database();
        }
        $this->db = $db;
        $this->student = $student;
    }
    
    function getPurchases() {
        if ($this->purchases != null)
            return $this->purchases;
        
        $result = $this->db->dosql('SELECT * FROM purchases WHERE arbor_id = ' . $this->student->getId() . ';');
        
        $this->purchases = [];
        
        if ($result->num_rows > 0) {
            foreach ($result->fetch_all() as $p) {
                $purchase = new Purchase($p);
                array_push($this->purchases, $purchase);
            }
        }
        return $this->getPurchases();
    }
    
    /**
     * @param \ArborShop\ShopItem $item
     */
    function addPurchase($item) {
        $arborId = $this->student->getId();
        $price = $item->getPrice();
        $itemId = $item->getId();

        if ($price > $this->student->getPoints()) {
            /* unaffordable */
            die("Nice try, but you can't hack an unaffordable product this way\n");
        }
        
        /* Now make the purchase- we'll invalidate the cached purchases too */
        
        $this->purchases = null;
        
        $this->student->debitPoints($price);
        
        $purchaseSuccess = $this->db->dosql("INSERT INTO purchases (arbor_id, price, item_id)
                                    VALUES ('$arborId', '$price', '$itemId');");
        
        /* Just in case, credit the student's account */
        if (!$purchaseSuccess) {
            $this->student->debitPoints(-$price);
            return FALSE;
        }
        return TRUE;
        
        
        
    }

    function __destruct()
    {}
}

