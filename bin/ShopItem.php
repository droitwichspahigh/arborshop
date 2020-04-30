<?php
namespace bin;

/**
 * Each ShopItem contains the details for one Item.
 *
 * @author reescm
 *        
 */
class ShopItem
{
    protected $id, $img_filename, $name, $price, $description, $availability;

    /**
     * @param integer $id
     * @param string $name
     * @param integer $price
     * @param string $description
     * @param string $img_filename
     * @param string $availability
     * 
     */
    public function __construct($id, $name, $price, $description, $img, $availability)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->img_filename = $img;
        $this->availability = $availability;
    }
    
    public function getName() { return $this->name; }
    
    public function getPrice() { return $this->price; }
    
    public function getDescription() { return $this->description; }
    
    public function getImg() { global $site_url; return "<img src=\"$site_url/img/product/$this->img_filename\" class=\"img-rounded img-fluid\" />"; }
    
    /**
     * Returns the raw string for availability in the form "0,5,8" etc
     * 
     * @return string
     */
    public function getAvailability() {
        return $this->availability;
    }
    
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
     */
    function __destruct()
    {

        // TODO - Insert your code here
    }
}

