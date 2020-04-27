<?php

require "../contrib/sis-sdk-php/vendor/autoload.php";

require "config.php";

/**
 * Connection details to Arbor
 */
$arborconfig = [
    'api' => [
        'baseUrl' => "$arborsite/rest-v2/",
        'auth' => [
            'user' => $arboruser,
            'password' => $arborpassword
        ]
    ]
];

$api = new \Arbor\Api\Gateway\RestGateway(
    $arborconfig['api']['baseUrl'],
    $arborconfig['api']['auth']['user'],
    $arborconfig['api']['auth']['password']
    );

\Arbor\Model\ModelBase::setDefaultGateway($api);

date_default_timezone_set('Europe/London');
