<?php 

namespace App\Exceptions;

use Exception;

class TryoutException extends Exception
{
    public static function sessionNotFound(): self
    {
        return new self('Tryout session not found');
    }

    public static function sessionExpired(): self
    {
        return new self('Tryout session has expired');
    }

    public static function sessionAlreadyCompleted(): self
    {
        return new self('Tryout session is already completed');
    }

    public static function unauthorizedAccess(): self
    {
        return new self('Unauthorized access to tryout session');
    }

    public static function invalidQuestion(): self
    {
        return new self('Invalid question for this tryout');
    }

    public static function invalidChoice(): self
    {
        return new self('Invalid answer choice');
    }
}