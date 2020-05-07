<?php
namespace ArborShop;

/**
 * Each ShopItem contains the details for one Item.
 *
 * @author reescm
 *        
 */
class ShopItem
{
    protected $id, $img_filename, $name, $price, $description, $availability, $enabled;

    /**
     * @param integer $id
     * @param string $name
     * @param integer $price
     * @param string $description
     * @param string $img_filename
     * @param string $availability
     * @param boolean $enabled
     * 
     */
    public function __construct($id, $name, $price, $description, $img, $availability, $enabled)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->img_filename = $img;
        $this->availability = $availability;
        /* I both love and hate that PHP lets you compare an int to a string */
        $this->enabled = ($enabled == 1) ? TRUE : FALSE;
    }
    
    public function getName() { return $this->name; }
    
    public function getPrice() { return $this->price; }
    
    public function getDescription() { return $this->description; }
    
    public function getImg() { return '<img src="' . Config::$site_url . "/img/product/$this->img_filename\" class=\"img-rounded img-fluid\" />"; }
    
    public function isEnabled() { return $this->enabled; }
    
    /**
     * Returns the raw string for availability in the form "0,5,8" etc
     * 
     * @return string
     */
    public function getAvailability() { return $this->availability; }
    
    /**
     * 
     * @param integer $year
     * @return boolean
     */
    public function availableForYearGroup($year)
    {
        if ($year == NULL)
            return TRUE;
        
        foreach (explode(',', $this->availability) as $y) {
            if (strcmp($year, $y) == 0) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    /**
     * Outputs a div class="row" for a shop item
     * 
     * @param boolean $available            Darkens the row if false
     * @param string  $description_prefix   Prepends to the description
     * @param boolean $linked               Makes the row a link to purchase
     */
    function printRow($available = true, $description_prefix = NULL, $linked = false) {
        $d = $this->getDescription();
        $p = $this->getPrice();
        $n = $this->getName();
        $img = $this->getImg();
        /* Don't show the available years for disabled items */
        $rowclass = "";
        $link = "";
        if (!$available) {
            $rowclass = "bg-secondary";
        } else {
            $d = "$description_prefix $d";
            if ($linked)
                $link = "<a href=\"?purchase=$this->id\" class=\"stretched-link\"></a>";
        }
        echo <<<EOF
<div class="row $rowclass">
    <div class="col-sm-1 text-center">$p points$link</div>
    <div class="col-sm-2 text-center"><strong>$n</strong>$link</div>
    <div class="col-sm-7">$d$link</div>
    <div class="col-sm-2 text-center">$img$link</div>
</div>
<hr />
EOF;
        
    }
    

    /**
     */
    function __destruct()
    {

        // TODO - Insert your code here
    }
}

