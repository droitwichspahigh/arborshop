<?php

namespace ArborShop;

class Student {
    protected $academic_year = [];
    protected $db = null;
    protected $detail = [];
    protected $client;
    protected $query;
    
    /**
     * Looks up a Student from Arbor, using GraphQL.
     * 
     * @param mixed $userName_or_arborId Arbor ID or email username is acceptable.
     * @param bool $nameonly If this optional argument is false, Arbor looks up behaviour points as well.
     */
    public function __construct($userName_or_arborId, $nameOnly = true) {        
        if (isset($_SESSION['studentDetail'])) {
            Config::debug("Student::__construct: session detail found");
            if ($_SESSION['studentDetail']['arborId'] == $userName_or_arborId ||
                (isset($_SESSION['studentDetail']['userName']) && $_SESSION['studentDetail']['userName'] == $userName_or_arborId)) {
                    $this->detail = $_SESSION['studentDetail'];
                    return;
                }
            Config::debug("Student::__construct: session detail found, but not matched");
        }
        
        Config::debug("Student::__construct: no session detail found");
        
        $this->client = new GraphQLClient();

        if (is_numeric($userName_or_arborId)) {
            $this->detail['arborId'] = $userName_or_arborId;
        } else {
            $this->detail['userName'] = $userName_or_arborId;
            /*   Ugh, got to look up using email address */
            Config::debug("Student::__construct: looking for email");
            $emailAddress = $userName_or_arborId . "@" . Config::$site_emaildomain;
            $emailQuery = "{ EmailAddress (emailAddress: \"$emailAddress\") { emailAddressOwner { id }}}";
            $emailAddress = $this->client->rawQuery($emailQuery)->getData()['EmailAddress'];
            Config::debug("Student::__construct: query complete");
            if (!isset($emailAddress[0])) {
                die("Your email address " . $userName_or_arborId . '@' . Config::$site_emaildomain ." appears unrecognised.");
            }
            if (isset($emailAddress[1])) {
                die("Your email address appears to have more than one owner.  This cannot possibly be right");
            }
            if ($emailAddress[0]['emailAddressOwner']['entityType'] != 'Student') {
                die("Your email address " . $userName_or_arborId . '@' . Config::$site_emaildomain ." appears not to belong to a student.");
            }
            Config::debug("Student::__construct: email found");
            $this->detail['arborId'] = $emailAddress[0]['emailAddressOwner']['id'];
        }
        $id = $this->detail['arborId'];
        $this->query = <<<EOF
Student (id: $id) {
  id
  preferredFirstName
  preferredLastName
  academicLevel {
    id
    shortName
  }
}
EOF;
        $queryForBehaviourPoints = false;
        if (!$nameOnly) {
            /* Before we query Arbor, let's see if we have them cached */
            if ($this->getBehaviourNetPointsFromCache() == false) {
                $queryForBehaviourPoints = true;
                if (empty(Config::$queryMyDshs)) {
                    $ayEve = $this->getAY('eve');
                    $ayPost = $this->getAY('post');
                    $this->query .= <<<EOF
    BehaviouralIncidentStudentInvolvement (student__id: $id behaviouralIncident__incidentDatetime_before: "$ayPost" behaviouralIncident__incidentDatetime_after: "$ayEve") {
      severity
      student {
        academicLevel {
          shortName
        }
      }
    }
    PointAward (student__id: $id awardedDatetime_before: "$ayPost" awardedDatetime_after: "$ayEve") {
      points
    }
    EOF;
                }
            }
        }
        
        $this->query = "query { $this->query }";
        $result = $this->client->rawQuery($this->query)->getData();
        
        foreach (['preferredFirstName', 'preferredLastName'] as $n) {
            $this->detail[$n] = $result['Student'][0][$n];
        }
        $matches = [];
        if (preg_match('/[1-9][0-9]*|[0RKN]$/', $result['Student'][0]['academicLevel']['shortName'], $matches) == 0) {
            die("Student {$this->getFirstName()} {$this->getLastName()}'s year group {$result['Student'][0]['academicLevel']['shortName']} is invalid.");
        }
        $this->detail['yearGroup'] = $matches[0];
        if ($queryForBehaviourPoints) {
            if (empty(Config::$queryMyDshs)) {
                $p = 0;
                foreach ($result['BehaviouralIncidentStudentInvolvement'] as $i) {
                    $p += $i['severity'];
                }
                foreach ($result['PointAward'] as $i) {
                    $p += $i['points'];
                }
            } else {
                $client = new \GuzzleHttp\Client(['base_uri' => Config::$queryMyDshs, 'timeout' => 1.0,]);
                $key = Config::$queryMyDshsApiKey;
                try {
                    $response = $client->get("{$this->getId()}/praise-points/total",
                        ['headers' => [ 'Authorization' => "Bearer $key",],]
                    );
                    
                } catch (\Exception $e) {
                    die("There has been a problem communicating with MyDSHS.");
                }
                $p = (string)$response->getBody();
                if (!is_numeric($p)) {
                    echo "<pre>" . $response->getBody() . "</pre>";
                    die("There has been a problem communicating with MyDSHS.");
                }
            }
            $this->detail['behaviourNetPoints'] = $p;
            $this->db->dosql("INSERT INTO pointsCache (arbor_id, arborPoints) VALUES ('"
                . $this->getId() . "', '"
                . $this->getBehaviourNetPoints() . "');");
        }
        if (isset ($_SESSION)) {
            $_SESSION['studentDetail'] = $this->detail;
        }
    }
    
    function getId() {          return $this->detail['arborId']; }
    function getFirstName() {   return $this->detail['preferredFirstName']; }
    function getLastName() {    return $this->detail['preferredLastName']; }
    function getYearGroup() {   return $this->detail['yearGroup']; }
    
    
    function getPoints() {
        if (isset($this->detail['points'])) {
            return $this->detail['points'];
        }
        
        $points = 0;
        
        $points += $this->getBehaviourNetPoints();
        
        Config::debug("Student::getPoints: behaviour obtained");
        
        $points -= $this->getSpentPoints();
        
        Config::debug("Student::getPoints: spent points obtained");
        
        $this->detail['points'] = $points;
        
        return $this->detail['points'];
    }
    
    protected function getBehaviourNetPoints() {
        if (isset($this->detail['behaviourNetPoints'])) {
            return $this->detail['behaviourNetPoints'];
        }
        
        die ("BUG: You didn't ask for points in the constructor");
    }
    
    protected function getBehaviourNetPointsFromCache() {
        if (is_null($this->db)) {
            $this->db = new Database();
        }
        
        $pointsCache_query = $this->db->dosql("SELECT arborPoints, UNIX_TIMESTAMP(ts) FROM pointsCache WHERE arbor_id = '"
                        . $this->getId() . "';");
        
        /* Sometimes accidental duplicates appear, we'll clean them up and purge */
        if ($pointsCache_query->num_rows == 1) {
            $row = $pointsCache_query->fetch_row();
            Config::debug("Student::getBehaviourNetPointsFromCache: row[1] = '$row[1]' and time() = '" . time() . "'");
            if ($row[1] > (time() - 300)) {
                Config::debug("Student::getBehaviourNetPointsFromCache: cache hit from points database");
                $this->detail['behaviourNetPoints'] = $row[0];
                return $this->detail['behaviourNetPoints'];
            }
        }
        $this->db->dosql("DELETE FROM pointsCache WHERE arbor_id = '"
                    . $this->getId() . "';");
           
        return false;
    }
    
    function getSpentPoints() {
        if (isset($this->detail['spentPoints'])) {
            return $this->detail['spentPoints'];
        }
        if (is_null($this->db)) {
            $this->db = new Database();
        }
        /* Now let's grab the spent points from the database */
        $spent_query = $this->db->dosql("SELECT spent FROM spent WHERE arbor_id = "
            . $this->getId() . ";");
        
        if ($spent_query->num_rows > 0) {
            $this->detail['spentPoints'] = $spent_query->fetch_row()[0];
        } else {
            /* This is their first visit.  Let's give them a new account! */
            $this->db->dosql("INSERT INTO spent (arbor_id, spent) VALUES ('" . $this->getId() . "', '0');");
            $this->detail['spentPoints'] = 0;
        }
        return $this->detail['spentPoints'];
    }
    
    function debitPoints($figure) {
        if (is_null($this->db)) {
            $this->db = new Database();
        }
        /* First debit the student's account */
        $arborId = $this->getId();       
            
        $oldSpent = $this->getSpentPoints();
        $newSpent = $oldSpent + $figure;
        
        if ($newSpent < 0) {
            die("Sorry, we don't extend credit (this is almost certainly a bug)");
        }
        
        $this->db->dosql("UPDATE spent SET spent = '$newSpent' WHERE arbor_id = '$arborId'");
        
        /* We've just invalidated points, so we'll need to calculate them again */
        $this->detail['spentPoints'] = $newSpent;
        unset($this->detail['points']);
    }
    
    protected function getAY($element = 'start') {
        if (isset($this->academic_year[$element])) {
            return $this->academic_year[$element];
        }
        
        /* Let's find our academic year before we go any further */
        $month = date("m");
        $year = date("Y");
        
        if ($month >= 9) {
            $this->academic_year['eve'] = "$year-08-31";
            $this->academic_year['start'] = "$year-09-01";
            $this->academic_year['end'] = $year+1 . "-08-31";
            $this->academic_year['post'] = $year+1 . "-09-01";
        } else {
            $this->academic_year['eve'] = $year-1 . "-08-31";
            $this->academic_year['start'] = $year-1 . "-09-01";
            $this->academic_year['end'] = "$year-08-31";
            $this->academic_year['post'] = "$year-09-01";
        }
        
        /* Is it something we actually know? */
        if (!array_key_exists($element, $this->academic_year))
            die("There isn't such an element; $element for academic year");
            
            return $this->getAY($element);
    }
    
    
}