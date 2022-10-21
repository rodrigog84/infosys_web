<?php 

require_once(__DIR__ . '/sendinblue/vendor/autoload.php');

// Configure API key authorization: api-key
/*SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

$api_instance = new SendinBlue\Client\Api\AccountApi();

$smtp_instance = new SendinBlue\Client\Api\SMTPApi();



$sendSmtpEmail = new SendinBlue\Client\Model\SendSmtpEmail([
     'subject' => 'from the PHP SDK!',
     'sender' => ['name' => 'Sendinblue', 'email' => 'rodrigo.gonzalez@arnou.cl'],
     'replyTo' => ['name' => 'Sendinblue', 'email' => 'contacto@arnou.cl'],
     'to' => [[ 'name' => 'Max Mustermann', 'email' => 'rodrigog.84@gmail.com']],
     'htmlContent' => '<html><body><h1>This is a transactional email {{params.bodyMessage}}</h1></body></html>',
     'params' => ['bodyMessage' => 'made just for you!']
]);

try {
    $result = $smtp_instance->sendTransacEmail($sendSmtpEmail);
    print_r($result);
} catch (Exception $e) {
    echo $e->getMessage(),PHP_EOL;
}*/


/*
try {
    $result = $api_instance->getAccount();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountApi->getAccount: ', $e->getMessage(), PHP_EOL;
}*/