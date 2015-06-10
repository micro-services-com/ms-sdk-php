# ms-sdk-php
PHP-SDK to access micro services

## Requirements

- PHP >= 5.3

## Usage

```php
require 'ms-sdk-php/LoyaltyServiceClient.php';

use \mssdk\LoyaltyServiceClient;

// loyalty service application id
$applicationId = "[CHANGE-ME]";
// generate an api key and secret at https://application.micro-services.com 
$key = '[CHANGE-ME]';
$secret = '[CHANGE-ME]';

$loyaltyServiceClient = new LoyaltyServiceClient($applicationId, $key, $secret);

// read the ambassador id from cookie and track the activity for the ambassador
$ambassadorId = $loyaltyServiceClient->validateAmbassadorId($_COOKIE[LoyaltyServiceClient::COOKIENAME_AMBASSADORID]);
if(isset($ambassadorId)) {
	$response = $loyaltyServiceClient->trackAmbassadorActivity($ambassadorId, "New Order from Customer XXX");
	// uncomment for for debugging
	//if($response["error"]) {
	//   echo "Error - HttpStatus: " . $response["status"] . ", Response: " . $response["response"];
	//} 
}

// read the browser id from cookie and track activity for the customer
$browserId = $loyaltyServiceClient->validateBrowserId($_COOKIE[LoyaltyServiceClient::COOKIENAME_BROWSERID]);
if(isset($browserId)) {
	$response = $loyaltyServiceClient->trackBrowserActivity($browserId, "ORDER", "Order Id: XXX"); 
	// uncomment for for debugging
	//if($response["error"]) {
	//   echo "Error - HttpStatus: " . $response["status"] . ", Response: " . $response["response"];
	//} 
}
```


