<?php

namespace Pindena\MakeSDK\Exceptions;

class AuthenticationException extends MakeSDKException
{
    public static function invalidCredentials(): self
    {
        return new self('Invalid Make API credentials. Check your username and password.', 401);
    }
}
