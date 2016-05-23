<?php
namespace CoinGate;

class APIError extends \Exception {}

# HTTP Status 400
class BadRequest extends APIError {}
class CredentialsMissing extends BadRequest {}
class BadEnvironment extends BadRequest {}

# HTTP Status 401
class Unauthorized extends APIError {}
class BadCredentials extends Unauthorized {}

# HTTP Status 404
class NotFound extends APIError {}
class PageNotFound extends NotFound{}
class RecordNotFound extends NotFound {}
class OrderNotFound extends NotFound {}

# HTTP Status 422
class UnprocessableEntity extends APIError {}
class OrderIsNotValid extends UnprocessableEntity {}

# HTTP Status 500
class InternalServerError extends APIError {}
