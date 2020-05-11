<?php
namespace ArborShop;

/* Meh, this has code duplication with StudentReceiptWallet... */
class PurchaseDb
{
    private $db = null, $purchases = null;

    public function __construct($db = null)
    {
        if (is_null($db)) {
            $db = new Database();
        }
        $this->db = $db;
    }

    /** Returns an array of Purchases for all students
     * 
     * @return array(\ArborShop\Purchase)
     */
    function getPurchases() {
        if (!is_null($this->purchases)) {
            return $this->purchases;
        }
        
        $result = $this->db->dosql('SELECT * FROM purchases ORDER BY datetime DESC;');
        
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
     * Returns an array of Purchases for $student
     * 
     * @param \ArborShop\Student $student
     * @return array(\ArborShop\Purchase)
     */
    function getStudentPurchases($student) {
        $result = [];
        
        foreach ($this->getPurchases() as $p) {
            if ($p->getArborId() == $student->getId()) {
                array_push($result, $p);
            }
        }
        return $result;
    }
    
    /**
     * @param \ArborShop\Student $student
     * @param \ArborShop\ShopItem $item
     */
    function addPurchase($student, $item) {
        $arborId = $student->getId();
        $price = $item->getPrice();
        $itemId = $item->getId();
        
        if ($price > $student->getPoints()) {
            /* unaffordable */
            die("Nice try, but you can't hack an unaffordable product this way\n");
        }
        
        /* Now make the purchase- we'll invalidate the cached purchases too */
        
        $this->purchases = null;
        
        $student->debitPoints($price);
        
        $purchaseSuccess = $this->db->dosql("INSERT INTO purchases (arbor_id, price, item_id)
                                    VALUES ('$arborId', '$price', '$itemId');");
        
        /* Just in case, credit the student's account */
        if (!$purchaseSuccess) {
            $student->debitPoints(-$price);
            return FALSE;
        }
        return TRUE;
    }
    
    
    /**
     * @param \ArborShop\Student $student
     * @param int $purchaseId
     */
    function deletePurchase($student, $purchaseId) {        
        $studentId = $student->getId();
        /** @var \ArborShop\Purchase $p */
        foreach ($this->getPurchases() as $p) {
            if ($p->getPurchaseId() == $purchaseId) {
                if ($p->getCollected != "") {
                    die("Nice try- you've already collected this purchase...");
                } if ($p->getArborId() != $studentId) {
                    die("Nice try- you can't get a refund on someone else's purchase...");
                }
                $this->db->dosql("DELETE FROM purchases WHERE purchase_id = '$purchaseId';");
                $student->debitPoints(-($p->getPrice()));
                /* Invalidate cached purchases */
                $this->purchases = null;
                return true;
            }
        }
        die("Nice try- purchase $purchaseId not found...");
    }
    
    
    function __destruct()
    {}
}

