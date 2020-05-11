<?php

namespace ArborShop;

require "classes.php";

class Student {
    protected $arborApi;
    protected $userName;
    protected $academic_year = [];        
    protected $points = null;
    protected $behaviourNetPoints = null;
    protected $spentPoints = null;
    protected $year_group;
    protected $firstName;
    protected $lastName;
    protected $arborResourceStudent = null;
    protected $arborResourceStudentId = null;
    protected $arborResourceStudentUrl = null;
    protected $db = null;
    
    public function __construct($userName) {
        $this->userName = $userName;
        $arbor = new ArborConnection();
        $this->arborApi = $arbor->getApi();
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
            $this->academic_year['post'] = $year+1 . "-09-01";
        } else {
            $this->academic_year['eve'] = $year-1 . "-08-31";
            $this->academic_year['post'] = "$year-09-01";
        }
        
        /* Is it something we actually know? */
        if (!array_key_exists($element, $this->academic_year))
            die("There isn't such an element; $element for academic year");
        
        return $this->getAY($element);
    }
    
    function getPoints() {
        if ($this->points != null) {
            return $this->points;
        }
        
        $this->points = 0;
        
        $this->points += $this->getBehaviourNetPoints();
        
        $this->points -= $this->getSpentPoints();
        
        return $this->getPoints();
    }
    
    protected function getBehaviourNetPoints() {
        if ($this->behaviourNetPoints != null) {
            return $this->behaviourNetPoints;
        }
        
        /* Arbor makes this really slow, so we're going
         * to cache the result in the database.  Only for
         * five minutes!
         */
        
        if (is_null($this->db)) {
            $this->db = new Database();
        }
        
        $pointsCache_query = $this->db->dosql("SELECT arborPoints, UNIX_TIMESTAMP(ts) FROM pointsCache WHERE arbor_id = '$this->arborResourceStudentId';");
        
        if ($pointsCache_query->num_rows > 0) {
            $row = $pointsCache_query->fetch_row();
            if ($row[1] > (time() - 300)) {
                $this->behaviourNetPoints = $row[0];
                return $this->getBehaviourNetPoints();
            } else {
                $this->db->dosql("DELETE FROM pointsCache WHERE arbor_id = '$this->arborResourceStudentId';");
            }
        }
           
        $this->behaviourNetPoints = 0;
        
        /* So... we get a list of incidents for x points, for all severity values.
         *
         * We sum the values to get points.
         */
        for ($pointValue = -5; $pointValue <= 5; $pointValue++) {
            /* Now get behavioural incidents */
            $behaviourQuery = new \Arbor\Query\Query(\Arbor\Resource\ResourceType::BEHAVIOURAL_INCIDENT_STUDENT_INVOLVEMENT);
            
            $behaviourQuery->addPropertyFilter(
                \Arbor\Model\User::STUDENT,
                \Arbor\Query\Query::OPERATOR_EQUALS,
                $this->getArborResourceStudentUrl());
            
            $behaviourQuery->addPropertyFilter(
                \Arbor\Model\BehaviouralIncidentStudentInvolvement::SEVERITY,
                \Arbor\Query\Query::OPERATOR_EQUALS,
                $pointValue);
            
            $behaviourQuery->addPropertyFilter(
                \Arbor\Model\BehaviouralIncidentStudentInvolvement::BEHAVIOURAL_INCIDENT
                . '.'
                . \Arbor\Model\BehaviouralIncident::INCIDENT_DATETIME,
                \Arbor\Query\Query::OPERATOR_AFTER,
                $this->getAY('eve'));
            
            $this->behaviourNetPoints += $pointValue * sizeof($this->arborApi->query($behaviourQuery));
        }
        
        $this->db->dosql("INSERT INTO pointsCache (arbor_id, arborPoints) VALUES ('$this->arborResourceStudentId', '$this->behaviourNetPoints');");
        
        return $this->getBehaviourNetPoints();
    }
    
    function getSpentPoints() {
        if ($this->spentPoints != null) {
            return $this->spentPoints;
        }
        if (is_null($this->db)) {
            $this->db = new Database();
        }
        /* Now let's grab the spent points from the database */
        $spent_query = $this->db->dosql("SELECT spent FROM spent WHERE arbor_id = "
            . $this->getArborResourceStudentId() . ";");
        
        if ($spent_query->num_rows > 0) {
            $this->spentPoints = $spent_query->fetch_row()[0];
        } else {
            /* This is their first visit.  Let's give them a new account! */
            $this->db->dosql("INSERT INTO spent (arbor_id, spent) VALUES ('" . $this->getArborResourceStudentId() . "', '0');");
            $this->spentPoints = 0;
        }
        return $this->getSpentPoints();
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
        $this->spentPoints = $newSpent;
        $this->points = null;
    }
    
    protected function getArborResourceStudent() {
        if ($this->arborResourceStudent != null) {
            return $this->arborResourceStudent;
        }

        $emailQuery = new \Arbor\Query\Query(\Arbor\Resource\ResourceType::EMAIL_ADDRESS);
        /* This is where we'll query network login */
        $emailQuery->addPropertyFilter(\Arbor\Model\EmailAddress::EMAIL_ADDRESS,
            \Arbor\Query\Query::OPERATOR_EQUALS,
            $this->userName . '@' . Config::$site_emaildomain);
        
        $emailAddress = \Arbor\Model\EmailAddress::query($emailQuery);
        
        if (!isset($emailAddress[0])) {
            die("Your email address " . $this->userName . '@' . Config::$site_emaildomain ." appears unrecognised.");
        }
        /* Awesome, so we can get the Arbor ID of the pupil, and all is unlocked */
        $this->arborResourceStudentId = $emailAddress[0]->getEmailAddressOwner()->getResourceId();
        
        $this->arborResourceStudentUrl = $emailAddress[0]->getEmailAddressOwner()->getResourceUrl();
        
        try {
            $this->arborResourceStudent = $this->arborApi->retrieve(\Arbor\Resource\ResourceType::STUDENT, $this->arborResourceStudentId);
        } catch (\Exception $e) {
            die("You don't appear to be a student- are you a parent or staff?");
        }

        return $this->arborResourceStudent;
    }
    
    function getFirstName() {
        if ($this->firstName != null) {
            return $this->firstName;
        }

        $this->firstName = $this->getArborResourceStudent()->getPerson()->getPreferredFirstName();
        
        return $this->getFirstName();
    }

    function getLastName() {
        if ($this->lastName != null) {
            return $this->lastName;
        }
        
        $this->lastName = $this->getArborResourceStudent()->getPerson()->getPreferredLastName();
        
        return $this->getLastName();
    }
    
    protected function getArborResourceStudentId() {
        if (is_null($this->arborResourceStudentId)) {
            $this->getArborResourceStudent();
        }
        
        return $this->arborResourceStudentId;
    }
    
    /**
     * Gets the ArborId of the student
     * 
     * @return integer $id
     */
    function getId() {
        return $this->getArborResourceStudentId();
    }
    
    protected function getArborResourceStudentUrl() {
        if (is_null($this->arborResourceStudentUrl)) {
            $this->getArborResourceStudent();
        }
            
        return $this->arborResourceStudentUrl;
    }
    
    function getYearGroup() {
        if ($this->year_group != null) {
            return $this->year_group;
        }
        
        $query = new \Arbor\Query\Query(\Arbor\Resource\ResourceType::ACADEMIC_LEVEL_MEMBERSHIP);
        $query->addPropertyFilter(\Arbor\Model\User::STUDENT, \Arbor\Query\Query::OPERATOR_EQUALS, $this->getArborResourceStudentUrl());
        $query->addPropertyFilter(\Arbor\Model\AcademicYear::START_DATE, \Arbor\Query\Query::OPERATOR_AFTER, $this->getAY('eve'));
        $query->addPropertyFilter(\Arbor\Model\AcademicYear::END_DATE, \Arbor\Query\Query::OPERATOR_BEFORE, $this->getAY('post'));
        $yearGroupMembershipList = $this->arborApi->query($query);
        
        if (!isset($yearGroupMembershipList[0])) {
            die("You appear not to be a member of a year group...");
        } else if (isset($yearGroupMembershipList[1])) {
            die("You appear to be a member of two year groups!");
        }
        
        $this->year_group = str_replace("Year ", "", $yearGroupMembershipList[0]->getProperty('academicLevel')->getProperty('shortName'));
        
        if (!is_numeric($this->year_group)) {
            die("Something strange is going on; your year group is '$this->year_group' apparently, which I can't convert to a number.");
        }
            
        return $this->year_group;
    }
}