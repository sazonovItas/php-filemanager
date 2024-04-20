<?php
declare(strict_types=1);

namespace app\exceptions;

class TokenNotFoundException extends \Exception {}
class TokenExpiredException extends \Exception {}
class TokenInvalidException extends \Exception {}