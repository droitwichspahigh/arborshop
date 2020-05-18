<?php
namespace ArborShop;

use GraphQL\QueryBuilder\QueryBuilder;

/* Meh, this has code duplication with StudentReceiptWallet... */
class PurchaseDb
{
    protected $db = null, $userNameMap = null;

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
    protected function getPurchases($filter = "") {
        $result = $this->db->dosql("SELECT * FROM purchases $filter ORDER BY datetime DESC;");
        
        $purchases = [];
        
        if ($result->num_rows > 0) {
            foreach ($result->fetch_all() as $p) {
                $purchase = new Purchase($p);
                array_push($purchases, $purchase);
            }
        }
        return $purchases;
    }
    
    function getUncollectedPurchases() {
        return $this->getPurchases("WHERE collected IS NULL");
    }
    
    function getTodayCollectedPurchases() {
        return $this->getPurchases("WHERE collected >= CURDATE()");
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
    
    function collect($purchaseId) {
        foreach ($this->getPurchases() as $p) {
            if ($p->getPurchaseId() == $purchaseId) {
                $this->db->dosql("UPDATE purchases SET collected = NOW() WHERE purchase_id = $purchaseId;");
            }
        }
    }
    
    function uncollect($purchaseId) {
        foreach ($this->getPurchases() as $p) {
            if ($p->getPurchaseId() == $purchaseId) {
                $this->db->dosql("UPDATE purchases SET collected = NULL WHERE purchase_id = $purchaseId;");
            }
        }        
    }
    
    function userNameMap($id) {
        if (!is_null($this->userNameMap[$id])) {
            Config::debug("PurchaseDb::userNameMap: property cache hit for $id");
            return $this->userNameMap[$id];
        }
        Config::debug("PurchaseDb::userNameMap: property cache miss");
        if (isset($_SESSION['userNameMap'])) {
            Config::debug("PurchaseDb::userNameMap: session cache present");
            $this->userNameMap = $_SESSION['userNameMap'];
            if (isset($this->userNameMap[$id])) {
                Config::debug("PurchaseDb::userNameMap: session cache hit for $id");
                return $this->userNameMap[$id];
            } /* Fall through, because this didn't match.
               *
               * This could happen if a pupil makes their very first purchase
               * as the Shopkeeper has their session open.
               * 
               * Never mind, let's query Arbor again...
               */
        }
        Config::debug("PurchaseDb::userNameMap: session cache nonexistent");
        $client = new GraphQLClient();
        $result = $this->db->dosql("SELECT DISTINCT arbor_id FROM purchases");
        $arg = [];
        foreach ($result->fetch_all() as $r) {
            array_push($arg, $r[0]);
        }
        Config::debug("PurchaseDb::userNameMap: doing GraphQL query on names for " . print_r($r, true));
        $qB = new QueryBuilder('Student');
        $qB->selectField('preferredFirstName');
        $qB->selectField('preferredLastName');
        $qB->setArgument('id_in', $arg);
        $result = $client->query($qB->getQuery());
        foreach ($result->getResults()['data']['Student'] as $r) {
            $this->userNameMap[$r['id']] = $r['preferredFirstName'] . " " . $r['preferredLastName'];
        }
        
        $_SESSION['userNameMap'] = $this->userNameMap;
        
        return $this->userNameMap[$id];
    }
    
    function __destruct()
    {}
}

