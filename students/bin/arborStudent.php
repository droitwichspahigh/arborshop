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
 * */

require "../../bin/arbor_connection.php";
require "masquerade.php";
require "../../bin/database.php";

/** @var int $points 
 * Subtract behaviour points, and add good conduct points */
$points = 0;

$emailQuery = new \Arbor\Query\Query(\Arbor\Resource\ResourceType::EMAIL_ADDRESS);
/* This is where we'll query network login */
$emailQuery->addPropertyFilter(\Arbor\Model\EmailAddress::EMAIL_ADDRESS, \Arbor\Query\Query::OPERATOR_LIKE, "$student@$site_emaildomain");

$emailAddress = \Arbor\Model\EmailAddress::query($emailQuery);

/* Awesome, so we can get the Arbor ID of the pupil, and all is unlocked */

$arborStudentId = $emailAddress[0]->getEmailAddressOwner()->getProperty('id');
$arborStudentHref = "rest-v2/students/$arborStudentId";
/** @var \Arbor\Model\Student $arborStudent */
$arborStudent =  $api->retrieve(\Arbor\Resource\ResourceType::STUDENT, $arborStudentId);

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
        gmdate("Y-m-d", strtotime("first day of September last year")));
    
    $points += $pointValue * sizeof($api->query($behaviourQuery));
}

/* Now let's grab the spent points from the database, and subtract */



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



