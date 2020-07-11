
<?php

return [
    'org_name' => 'EMI',
    'web_page' => 'www.emi.edu.bo',
    'date_format' => env('DATE_FORMAT', 'Y-m-d H:i:s'),
    'date_format_insert' => env('DATE_FORMAT_INSERT', 'Y-m-d H:i:s'),
    'app_url' => env('APP_URL','http://servicios.emi.edu.bo/inscripciones/'),
    'generated_files' => env('APP_GENERATED_FILES','https://inscripcion.servicios.emi.edu.bo/tmp/'),
    'url_logo' => env('URL_LOGO','http://servicios.emi.edu.bo/inscripciones/images/emi_logo.png'),
    'auth_office365' => false,
    'auth_2step' => config('app.debug') ? false : true,
    'auth_google_recaptcha' => config('app.debug') ? false : true,
    'allow_data' => false,
    'gestion_actual_siscoin' => env('GESTION_ACTUAL_SISCOIN', 12019),
    'gestion_actual_siscoin_rep' => env('GESTION_ACTUAL_SISCOIN_REP', '02/19'),
    'gestion_actual_siscoin_rep_alt' => env('GESTION_ACTUAL_SISCOIN_REP_ALT', '02/2019'),
    'storage_path' => env('STORAGE_PATH', storage_path()),
    'public_path' => env('PUBLIC_PATH', public_path()),
    'valid_domains' => env('VALID_DOMAINS', 'gmail.com,yahoo.com,hotmail.com,yahoo.es,outlook.com,adm.emi.edu.bo,doc.emi.edu.bo,est.emi.edu.bo'),
    'gestion_anterior_saga' => env('GESTION_ANTERIOR_SAGA', 2019) ,
    'periodo_anterior_saga' => env('PERIODO_ANTERIOR_SAGA', 1) ,
    'gestion_actual_saga' => env('GESTION_ACTUAL_SAGA', 2019) ,
    'periodo_actual_saga' => env('PERIODO_ACTUAL_SAGA', 2) ,
    'semestre_creditaje' => env('SEMESTRE_CREDITAJE', 4) ,
    'conceptos_siscoin' => env('CONCEPTOS_SISCOIN', '("01R","111","20C","21","21C")'),
    'payments_endpoint' => env('PAYMENTS_ENDPOINT', 'http://devkqtest.eastus2.cloudapp.azure.com/KXPaymentTR/HostedService.svc/CreateOrder'),
    'payments_inquiry' => env('PAYMENTS_INQUIRY', 'http://devkqtest.eastus2.cloudapp.azure.com/KXPaymentTR/HostedService.svc/InquiryPayment'),
    'payments_credentials' => env('PAYMENTS_CREDENTIALS', '{EB620479-5A98-4283-8AA3-AA442F62B8F9}{0C421A18-029B-4F17-A7BA-DDD296D22FD3}')

];