<?php
require __DIR__ . '/vendor/autoload.php';

use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

// Configuration
$configuration = new Configuration(
    host: 'https://51wymj.api.infobip.com',
    apiKey: '747414b82ba0afef49beb3bb28066981-9e290b7d-2a7f-4481-a3fe-09a597a56db0'
);

$smsApi = new SmsApi(config: $configuration);

// Recipient and message
$offender_mobile = '+94765536428';
$smsText = 'Hi buddy, this is a test SMS from FineMate.';

// Create destination
$destination = new SmsDestination(to: $offender_mobile);

// Create SMS textual message
$message = new SmsTextualMessage(
    destinations: [$destination],
    text: $smsText
);

// Create SMS request
$request = new SmsAdvancedTextualRequest(messages: [$message]);

try {
    $response = $smsApi->sendSmsMessage($request);
    echo "SMS sent successfully!\n";
    print_r($response);
} catch (\Infobip\ApiException $e) {
    echo "SMS Error: " . $e->getMessage() . "\n";
    echo "Response body: " . $e->getResponseBody();
} catch (\Exception $e) {
    echo "General Error: " . $e->getMessage();
}