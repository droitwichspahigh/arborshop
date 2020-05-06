<?php

namespace ArborShop;

require "classes.php";

class Student {
    protected $arborApi;
    protected $userName;
    protected $academic_year = [];        
    protected $points = null;
    protected $year_group;
    protected $firstName;
    protected $lastName;
    protected $arborResourceStudent = null;
    protected $arborResourceStudentId = null;
    protected $arborResourceStudentUrl = null;
    
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
        
        /* So... we get a list of incidents for x points, for all severity values.
         *
         * We sum the values to get points.
         */
        for ($pointValue = -4; $pointValue <= 4; $pointValue++) {
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
            
            $this->points += $pointValue * sizeof($this->arborApi->query($behaviourQuery));
        }
        
        $db = new Database();
        
        /* Now let's grab the spent points from the database, and subtract */
        $spent_query = $db->dosql("SELECT spent FROM spent WHERE arbor_id = "
            . $this->getArborResourceStudentId() . ";");
        
        if ($spent_query->num_rows > 0) {
            $this->points -= $spent_query->fetch_row()[0];
        } else {
            /* This is their first visit.  Let's give them a new account! */
            $db->dosql("INSERT INTO spent (arbor_id, spent) VALUES ('" . $this->getArborResourceStudentId() . "', '0');");
        }
        
        return $this->getPoints();
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
        
        $this->arborResourceStudent = $this->arborApi->retrieve(\Arbor\Resource\ResourceType::STUDENT, $this->arborResourceStudentId);

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
        if ($this->arborResourceStudentId == null) {
            $this->getArborResourceStudent();
        }
        
        return $this->arborResourceStudentId;
    }
    
    protected function getArborResourceStudentUrl() {
        if ($this->arborResourceStudentUrl == null) {
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