<?php

namespace Pindena\MakeSDK\Resources;

use Pindena\MakeSDK\Http\MakeClient;

class EventResource
{
    public function __construct(
        private readonly MakeClient $client,
    ) {}

    /**
     * Get bounce events.
     */
    public function bounces(array $params = []): array
    {
        return $this->client->subscribersGet('events/bounces', $params);
    }

    /**
     * Get click events.
     */
    public function clicks(array $params = []): array
    {
        return $this->client->subscribersGet('events/clicks', $params);
    }

    /**
     * Get open events.
     */
    public function opens(array $params = []): array
    {
        return $this->client->subscribersGet('events/opens', $params);
    }

    /**
     * Get sending events.
     */
    public function sendings(array $params = []): array
    {
        return $this->client->subscribersGet('events/sendings', $params);
    }

    /**
     * Get unsubscribe events.
     */
    public function unsubscribes(array $params = []): array
    {
        return $this->client->subscribersGet('events/unsubscribes', $params);
    }
}
