<?php
declare(strict_types = 1);

namespace app\exceptions;

class UserNotFoundException extends \Exception {}
class UserPasswordDoesNotMatchException extends \Exception {}
class UserAlreadyExistsException extends \Exception{}
