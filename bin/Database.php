<?php

namespace Arborshop;
use ArborShop\Config;
use mysqli;
use mysqli_result;

/** @var mysqli $conn */
class Database {
    private $conn;
    
    function Database($firstconnection = FALSE) {
        $details = Config::$db;
        if ($firstconnection) {
            $this->conn = new mysqli($details['host'], $details['user'], $details['password']);
        } else {
            $this->conn = new mysqli($details['host'], $details['user'], $details['password'], $details['database']);
        }
        
        if ($this->conn->connect_error) {
            die('Connect Error (' . $this->conn->connect_errno . ') '
                . $this->conn->connect_error);
        }
    }

    private function debug($msg) {
        global $debug;
        
        if ($debug) {
            echo $msg;
        }
    }

    /**
     * Performs an SQL operation.
     * 
     * @param string $sqlcmd SQL operation to perform
     * @param boolean $critical If set to FALSE, only warn on failure
     * @return mysqli_result
     */
    function dosql($sqlcmd, $critical = TRUE) {        
        if ($result = $this->conn->query($sqlcmd)) {
            debug("$sqlcmd performed successfully<br /><br />");
        } else {
            if ($critical == TRUE) {
                die ("Error:   $sqlcmd failed: " . $this->conn->error);
            } else {
                debug("Warning: $sqlcmd failed: " . $this->conn->error);
            }
        }
        return $result;
    }
}