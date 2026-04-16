<?php

namespace Pindena\MakeSDK\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Pindena\MakeSDK\Exceptions\AuthenticationException;
use Pindena\MakeSDK\Exceptions\MakeSDKException;

class MakeClient
{
    public function __construct(
        private readonly string $username,
        private readonly string $password,
        private readonly string $subscribersApiUrl,
        private readonly string $newslettersApiUrl,
    ) {}

    private function buildRequest(): PendingRequest
    {
        return Http::withBasicAuth($this->username, $this->password)
            ->acceptJson()
            ->asJson()
            ->throw();
    }

    private function subscribersUrl(string $uri): string
    {
        return rtrim($this->subscribersApiUrl, '/') . '/' . ltrim($uri, '/');
    }

    private function newslettersUrl(string $uri): string
    {
        return rtrim($this->newslettersApiUrl, '/') . '/' . ltrim($uri, '/');
    }

    /**
     * @throws MakeSDKException
     * @throws AuthenticationException
     */
    public function subscribersGet(string $uri, array $query = []): array
    {
        return $this->send('GET', $this->subscribersUrl($uri), query: $query);
    }

    /**
     * @throws MakeSDKException
     * @throws AuthenticationException
     */
    public function subscribersPost(string $uri, array $data = []): array
    {
        return $this->send('POST', $this->subscribersUrl($uri), data: $data);
    }

    /**
     * @throws MakeSDKException
     * @throws AuthenticationException
     */
    public function subscribersPut(string $uri, array $data = []): array
    {
        return $this->send('PUT', $this->subscribersUrl($uri), data: $data);
    }

    /**
     * @throws MakeSDKException
     * @throws AuthenticationException
     */
    public function subscribersDelete(string $uri, array $data = []): array
    {
        return $this->send('DELETE', $this->subscribersUrl($uri), data: $data);
    }

    /**
     * @throws MakeSDKException
     * @throws AuthenticationException
     */
    public function newslettersGet(string $uri, array $query = []): array
    {
        return $this->send('GET', $this->newslettersUrl($uri), query: $query);
    }

    /**
     * @throws MakeSDKException
     * @throws AuthenticationException
     */
    public function newslettersPost(string $uri, array $data = []): array
    {
        return $this->send('POST', $this->newslettersUrl($uri), data: $data);
    }

    /**
     * @throws MakeSDKException
     * @throws AuthenticationException
     */
    public function newslettersPut(string $uri, array $data = []): array
    {
        return $this->send('PUT', $this->newslettersUrl($uri), data: $data);
    }

    /**
     * @throws MakeSDKException
     * @throws AuthenticationException
     */
    private function send(string $method, string $url, array $query = [], array $data = []): array
    {
        try {
            $request = $this->buildRequest();

            /** @var Response $response */
            $response = match ($method) {
                'GET' => $request->get($url, $query),
                'POST' => $request->post($url, $data),
                'PUT' => $request->put($url, $data),
                'DELETE' => $request->delete($url, $data),
                'PATCH' => $request->patch($url, $data),
            };

            return $response->json() ?? [];
        } catch (RequestException $e) {
            $statusCode = $e->response->status();

            if ($statusCode === 401) {
                throw AuthenticationException::invalidCredentials();
            }

            $body = $e->response->json() ?? [];
            $message = $body['message'] ?? $body['status'] ?? $e->getMessage();

            throw MakeSDKException::fromResponse($statusCode, $message);
        }
    }
}
