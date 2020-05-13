<?php

namespace ArborShop;

require Config::$site_docroot . "/contrib/php-graphql-client/vendor/autoload.php";

require "classes.php";

use \GraphQL\Client;
use \GraphQL\Exception\QueryError;

class GraphQLQuery {
    protected $client;
    
    function __construct() {
        $this->client = new Client(
            Config::$arbor['site'] . 'graphql/query',
            ['Authorization' => 'Basic ' . base64_encode(Config::$arbor['user'] . ':' . Config::$arbor['password'])]);
    }
    
    function test() {
        $results = $this->client->runRawQuery("{ Student (id: 5) { id } }");
        print_r($results->getData());
    }
    
}

