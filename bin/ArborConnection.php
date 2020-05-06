<?php
namespace ArborShop;

require Config::$site_docroot . "/contrib/sis-sdk-php/vendor/autoload.php";

use \Arbor\Api\Gateway\RestGateway;
use \Arbor\Model\ModelBase;

/**
 * Connection details to Arbor
 */
class ArborConnection {
    protected $api;
    
    function __construct() {
        $this->api = new RestGateway(
            Config::$arbor['site'] . "/rest-v2/",
            Config::$arbor['user'],
            Config::$arbor['password']
            );
        ModelBase::setDefaultGateway($this->api);
        
        date_default_timezone_set('Europe/London');
        
    }
    
    function getApi() {
        return $this->api;
    }
}