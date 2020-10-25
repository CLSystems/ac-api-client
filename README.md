ac-api-client
==================

A PHP wrapper around the Adcell API

Install
-------

Install http://getcomposer.org/ and run the following command:

```
php composer.phar require clsystems/ac-api-client dev-main
```

Examples
-------

#### Request access token

* By ClientId / ClientSecret

```php
$api = new CLSystems\Admitad\Api();
$response = $api->authorizeByCredentials($clientId, $clientSecret, $scope);
$result = $response->getResult(); // or $response->getArrayResult();
```
* OAuth2

```php
// 1 step - get oauth authorization url
$api = new CLSystems\Admitad\Api();
$authorizeUrl = $api->getAuthorizeUrl($clientId, $redirectUri, $scope);
// redirect user to authorizeUrl


// 2 step - request access token by OAuth2 code returned from authorization url
$response = $api->requestAccessToken($clientId, $clientSecret, $code, $redirectUri);
$result = $response->getResult();
```
* Signed Request (for applications on apps.admitad.com)

```php
$api = new CLSystems\Admitad\Api();
$data = $api->parseSignedRequest($signedRequest, $clientSecret);
// this method throws Admitad\Api\Exception\InvalidSignedRequestException when $signedRequest is invalid
```

#### Refresh token

```php
$result = $api->refreshToken($clientId, $clientSecret, $refreshToken)->getResult();
```

#### Methods
There are 2 common methods to communicate with api:
```php
$api = new CLSystems\Admitad\Api($accessToken);

$api->get($path, $params);
$api->post($path, $params);

//for example
$data = $api->get('/advcampaigns/', array(
    'limit' => 20,
    'offset' => 0
))->getResult();
```

Paginated-result methods can be iterated in this way (instead of manually call methods with different offsets)

```php
$iterator = $api->getIterator('/advcampaigns/', array(
    'order_by' => 'id'
));

foreach ($iterator as $campaign) {
    // do smth with campaign
}
```
