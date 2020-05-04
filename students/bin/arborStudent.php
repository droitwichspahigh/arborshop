<?php

/**
 * From arbor_connection.php:
 * @var \Arbor\Api\Gateway\RestGateway  $api
 * @var array                           $arborconfig
 * 
 * From config.php:
 * @var string                          $site_emaildomain
 * 
 * From masquerade.php:
 * @var string                          $student
 */

/** This file defines:
 * 
 * @var string                  $year_group
 * @var \Arbor\Model\Student    $arborStudent
 * @var integer                 $points 
 * 
 */

require "../bin/arbor_connection.php";
require "masquerade.php";
require "../bin/database.php";

/* Let's find our academic year before we go any further */

$month = date("m");
$year = date("Y");

if ($month >= 9) {
    $eveOfAY = "$year-08-31";
    $postThisAY = $year+1 . "-09-01";
} else {
    $eveOfAY = $year-1 . "-08-31";
    $postThisAY = "$year-09-01"; 
}

/** @var int $points 
 * Subtract behaviour points, and add good conduct points */
$points = 0;

$emailQuery = new \Arbor\Query\Query(\Arbor\Resource\ResourceType::EMAIL_ADDRESS);
/* This is where we'll query network login */
$emailQuery->addPropertyFilter(\Arbor\Model\EmailAddress::EMAIL_ADDRESS, \Arbor\Query\Query::OPERATOR_EQUALS, "$student@$site_emaildomain");
$emailAddress = \Arbor\Model\EmailAddress::query($emailQuery);

if (!isset($emailAddress[0])) {
    die("Your email address $student@$site_emaildomain appears unrecognised.");
}

/* Awesome, so we can get the Arbor ID of the pupil, and all is unlocked */

$arborStudentId = $emailAddress[0]->getEmailAddressOwner()->getProperty('id');
$arborStudentHref = "rest-v2/students/$arborStudentId";

$arborStudent =  $api->retrieve(\Arbor\Resource\ResourceType::STUDENT, $arborStudentId);

/* Let's get the year group for this kid.  It really shouldn't be this difficult... */

$regFormMemQuery = new \Arbor\Query\Query(\Arbor\Resource\ResourceType::REGISTRATION_FORM_MEMBERSHIP);
$regFormMemQuery->addPropertyFilter("student", \Arbor\Query\Query::OPERATOR_EQUALS, $arborStudentHref);
$regFormMemQuery->addPropertyFilter(
    \Arbor\Model\RegistrationFormMembership::REGISTRATION_FORM . '.' .
        \Arbor\Model\RegistrationForm::ACADEMIC_YEAR . '.' .
        \Arbor\Model\AcademicYear::START_DATE,
    \Arbor\Query\Query::OPERATOR_AFTER,
    $eveOfAY);
$regFormMemQuery->addPropertyFilter(
        \Arbor\Model\RegistrationFormMembership::REGISTRATION_FORM . '.' .
        \Arbor\Model\RegistrationForm::ACADEMIC_YEAR . '.' .
        \Arbor\Model\AcademicYear::END_DATE,
    \Arbor\Query\Query::OPERATOR_BEFORE,
    $postThisAY);

$regFormMem = \Arbor\Model\RegistrationFormMembership::query($regFormMemQuery);

$year_group = preg_replace('/^[^0-9]*([0-9]+).*/', '\1', $regFormMem[0]->getProperty('registrationForm')->getProperty('shortName'));

/* So... we get a list of incidents for x points, for all severity values.
 * 
 * We sum the values to get points.
 */
for ($pointValue = -4; $pointValue <= 4; $pointValue++) {
    /* Now get behavioural incidents */
    $behaviourQuery = new \Arbor\Query\Query(\Arbor\Resource\ResourceType::BEHAVIOURAL_INCIDENT_STUDENT_INVOLVEMENT);
    
    /* So... I have to hand-hack to look up by ArborId.  Really?  This *is* going to break at some point :( */
    $behaviourQuery->addPropertyFilter(
        "student",
        \Arbor\Query\Query::OPERATOR_EQUALS,
        $arborStudentHref);
    
    $behaviourQuery->addPropertyFilter(
        \Arbor\Model\BehaviouralIncidentStudentInvolvement::SEVERITY,
        \Arbor\Query\Query::OPERATOR_EQUALS,
        $pointValue);
    
    $behaviourQuery->addPropertyFilter(
        \Arbor\Model\BehaviouralIncidentStudentInvolvement::BEHAVIOURAL_INCIDENT
            . '.'
            . \Arbor\Model\BehaviouralIncident::INCIDENT_DATETIME,
        \Arbor\Query\Query::OPERATOR_AFTER,
        $eveOfAY);
    
    $points += $pointValue * sizeof($api->query($behaviourQuery));
}

/* Now let's grab the spent points from the database, and subtract */
$spent_query = dosql("SELECT spent FROM spent WHERE arbor_id = $arborStudentId;");

if ($spent_query->num_rows > 0) {
    $points -= $spent_query->fetch_row()[0];
} else {
    /* This is their first visit.  Let's give them a new account! */
    dosql("INSERT INTO spent (arbor_id, spent) VALUES ('$arborStudentId', '0');");
}

/* Can't believe I have to do it the above way- this way is way
 * too slow because of the huge pages I have to download for each
 * Incident. 
 */
$multiline_comment = <<< 'EOF'

echo "<pre>";

 foreach ($api->query($behaviourQuery) as $model) {
 $hydrator = new \Arbor\Model\Hydrator();
 print_r($hydrator->extractArray($model));
 }


foreach ($api->query($behaviourQuery) as $b) {
    //$behaviouralIncidentStudentInvolvement = $api->retrieve(\Arbor\Resource\ResourceType::BEHAVIOURAL_INCIDENT_STUDENT_INVOLVEMENT, $b->getProperty('id'));
    //$points += $behaviouralIncidentStudentInvolvement->getBehaviouralIncident()->getSeverity();

//$points += $behaviouralIncidentStudentInvolvement->getSeverity();
}

echo "Your points balance; $points";

//$modelCollection = $api->query($behaviourQuery);

/*
foreach ($modelCollection as $model) {
    $hydrator = new \Arbor\Model\Hydrator();
    print_r($hydrator->extractArray($model));
}
*/

die("Done");

EOF;



