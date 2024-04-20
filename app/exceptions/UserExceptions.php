<?php
declare(strict_types = 1);

namespace app\exceptions;

class UserNotFoundException extends \Exception {}
class UserPasswordDoesNotMatchException extends UserNotFoundException {}
class UserAlreadyExistsException extends \Exception{}
