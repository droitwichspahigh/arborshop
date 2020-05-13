<?php

namespace ArborShop;

require "classes.php";

require Config::$site_docroot . "/contrib/php-graphql-client/vendor/autoload.php";

use \GraphQL\Client;
use \GraphQL\Query;

class GraphQLClient {
    protected $client;
    
    function __construct() {
        $this->client = new Client(
            Config::$arbor['site'] . 'graphql/query',
            ['Authorization' => 'Basic ' . base64_encode(Config::$arbor['user'] . ':' . Config::$arbor['password'])]);
    }
    
    function builder() {
        return $this->queryBuilder();
    }
        
    function query(Query $query) {
        return $this->client->runQuery($query, true, [['a' => 0]]);
    }
    
    function rawQuery(string $query) {
        return $this->client->runRawQuery($query, true, [['a' => 0]]);
    }
    
    function test() {
        /* Work around bug somewhere- dummy variable a = 0 */
        $results = $this->client->runRawQuery("{ Student (id: 5) { id } }", false, [['a' => 0]]);
        print_r($results->getData());
    }
    
}

