<?php

namespace Pindena\MakeSDK\Exceptions;

use Exception;

class MakeSDKException extends Exception
{
    /**
     * @param  array<string, mixed>  $query
     * @param  array<mixed>  $requestBody
     * @param  array<mixed>  $responseBody
     */
    public function __construct(
        string $message,
        int $statusCode,
        public readonly ?string $method = null,
        public readonly ?string $url = null,
        public readonly array $query = [],
        public readonly array $requestBody = [],
        public readonly array $responseBody = [],
    ) {
        parent::__construct($message, $statusCode);
    }

    /**
     * @param  array<string, mixed>  $query
     * @param  array<mixed>  $requestBody
     * @param  array<mixed>  $responseBody
     */
    public static function fromResponse(
        int $statusCode,
        string $message,
        ?string $method = null,
        ?string $url = null,
        array $query = [],
        array $requestBody = [],
        array $responseBody = [],
    ): self {
        $context = self::formatContext($method, $url, $query, $requestBody, $responseBody);
        $full = "Make API error [{$statusCode}]: {$message}".$context;

        return new self($full, $statusCode, $method, $url, $query, $requestBody, $responseBody);
    }

    /**
     * @param  array<string, mixed>  $query
     * @param  array<mixed>  $requestBody
     * @param  array<mixed>  $responseBody
     */
    private static function formatContext(
        ?string $method,
        ?string $url,
        array $query,
        array $requestBody,
        array $responseBody,
    ): string {
        if ($method === null && $url === null && $query === [] && $requestBody === [] && $responseBody === []) {
            return '';
        }

        $parts = [];
        if ($method !== null && $url !== null) {
            $parts[] = "{$method} {$url}";
        }
        if ($query !== []) {
            $parts[] = 'Query: '.json_encode($query, JSON_UNESCAPED_SLASHES);
        }
        if ($requestBody !== []) {
            $parts[] = 'Body: '.json_encode($requestBody, JSON_UNESCAPED_SLASHES);
        }
        if ($responseBody !== []) {
            $parts[] = 'Response: '.json_encode($responseBody, JSON_UNESCAPED_SLASHES);
        }

        return ' | '.implode(' | ', $parts);
    }
}
