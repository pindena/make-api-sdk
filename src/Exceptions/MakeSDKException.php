<?php

namespace Pindena\MakeSDK\Exceptions;

use Exception;

class MakeSDKException extends Exception
{
    public static function fromResponse(int $statusCode, string $message): self
    {
        return new self("Make API error [{$statusCode}]: {$message}", $statusCode);
    }
}
