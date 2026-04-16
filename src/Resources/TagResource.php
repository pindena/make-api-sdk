<?php

namespace Pindena\MakeSDK\Resources;

use Pindena\MakeSDK\Http\MakeClient;

class TagResource
{
    public function __construct(
        private readonly MakeClient $client,
    ) {}

    /**
     * Get all subscriber tags.
     */
    public function all(): array
    {
        return $this->client->subscribersGet('subscriber_tags');
    }

    /**
     * Create a new subscriber tag.
     */
    public function create(string $title): array
    {
        return $this->client->subscribersPost('subscriber_tags', [
            'title' => $title,
        ]);
    }

    /**
     * Get a subscriber tag by ID.
     */
    public function find(int $id): array
    {
        return $this->client->subscribersGet("subscriber_tags/{$id}");
    }

    /**
     * Update a subscriber tag.
     */
    public function update(int $id, string $title): array
    {
        return $this->client->subscribersPut("subscriber_tags/{$id}", [
            'title' => $title,
        ]);
    }

    /**
     * Delete a subscriber tag.
     */
    public function delete(int $id): array
    {
        return $this->client->subscribersDelete("subscriber_tags/{$id}");
    }
}
