<?php

return array(

    'HoboJet'     => array(
        'environment' =>'development',
        'certificate' => storage_path() . '/certificates/apple/aps_development.pem',
        'passPhrase'  =>'loodloo',
        'service'     =>'apns'
    )

);