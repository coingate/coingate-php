<?php

// CoinGate client
require __DIR__ . '/lib/ClientInterface.php';
require __DIR__ . '/lib/BaseClient.php';
require __DIR__ . '/lib/Client.php';

// HttpClient
require __DIR__ . '/lib/HttpClient/ClientInterface.php';
require __DIR__ . '/lib/HttpClient/CurlClient.php';

// Services
require __DIR__ . '/lib/Services/AbstractService.php';
require __DIR__ . '/lib/Services/AbstractServiceFactory.php';
require __DIR__ . '/lib/Services/ServiceFactory.php';
require __DIR__ . '/lib/Services/OrderService.php';
require __DIR__ . '/lib/Services/PublicService.php';

// Exceptions
require __DIR__ . '/lib/Exception/ApiConnectionException.php';
require __DIR__ . '/lib/Exception/ApiErrorException.php';
require __DIR__ . '/lib/Exception/InternalServerError.php';
require __DIR__ . '/lib/Exception/InvalidArgumentException.php';
require __DIR__ . '/lib/Exception/RateLimitException.php';
require __DIR__ . '/lib/Exception/UnexpectedValueException.php';
require __DIR__ . '/lib/Exception/UnknownApiErrorException.php';

// Exceptions (API specific)
require __DIR__ . '/lib/Exception/Api/BadAuthToken.php';
require __DIR__ . '/lib/Exception/Api/BadRequest.php';
require __DIR__ . '/lib/Exception/Api/NotFound.php';
require __DIR__ . '/lib/Exception/Api/OrderIsNotValid.php';
require __DIR__ . '/lib/Exception/Api/OrderNotFound.php';
require __DIR__ . '/lib/Exception/Api/Unauthorized.php';
require __DIR__ . '/lib/Exception/Api/UnprocessableEntity.php';
