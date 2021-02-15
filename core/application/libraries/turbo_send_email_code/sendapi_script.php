<?php

require_once "lib/TurboApiClient.php";


$email = new Email();
$email->setFrom("rgonzalez@tugastocomun.cl");
$email->setToList("rodrigog.84@gmail.com, rodrigo.gonzalez@info-sys.cl");
//$email->setCcList("dd@domain.com,ee@domain.com");
//$email->setBccList("ffi@domain.com,rr@domain.com");	
$email->setSubject("subject");
$email->setContent("content");
$email->setHtmlContent("html content");
$email->addCustomHeader('X-FirstHeader', "value");
$email->addCustomHeader('X-SecondHeader', "value");
$email->addCustomHeader('X-Header-da-rimuovere', 'value');
$email->removeCustomHeader('X-Header-da-rimuovere');



$turboApiClient = new TurboApiClient("rgonzalez@tugastocomun.cl", "S5mbPgpL");


$response = $turboApiClient->sendEmail($email);

var_dump($response);


