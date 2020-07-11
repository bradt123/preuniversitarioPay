<?php

return [
    'informix' => [
        'driver'    => 'informix',
        'host'      => env('DB_IFX_HOST', 'localhost'),
        'database'  => env('DB_IFX_DATABASE', 'forge'),
        'username'  => env('DB_IFX_USERNAME', 'forge'),
        'password'  => env('DB_IFX_PASSWORD', ''),
        'service'  => env('DB_IFX_SERVICE', '11143'),
        'server'  => env('DB_IFX_SERVER', ''),
        'db_locale'   => env('DB_LOCALE', ''),
        'client_locale' => env('CLIENT_LOCALE', ''),
        'db_encoding'   => 'GBK',
        'initSqls' => false,
        'client_encoding' => 'UTF-8',
        'prefix'    => '',
        'jdbc_driver' => 'com.informix.jdbc.IfxDriver',
        'jdbc_url' => env('IFRX_DB_JDBC', 'jdbc:informix-sqli://172.16.3.77:33333/emi2018_p:informixserver=siscoincentral;user=emi;password=p1nky'),
    ],
];