<?php

namespace Pindena\MakeSDK\Resources;

use Pindena\MakeSDK\DataTransferObjects\SubscriberList;
use Pindena\MakeSDK\Http\MakeClient;

class SubscriberListResource
{
    public function __construct(
        private readonly MakeClient $client,
    ) {}

    /**
     * Get all subscriber lists.
     */
    public function all(): array
    {
        $response = $this->client->subscribersGet('subscriberlists');

        return array_map(fn (array $item) => SubscriberList::fromArray($item), $response);
    }

    /**
     * Create a new subscriber list.
     */
    public function create(SubscriberList $list): SubscriberList
    {
        $response = $this->client->subscribersPost('subscriberlists', $list->toArray());

        return SubscriberList::fromArray($response);
    }

    /**
     * Get a subscriber list by ID.
     */
    public function find(int $id): SubscriberList
    {
        $response = $this->client->subscribersGet("subscriberlists/{$id}");

        return SubscriberList::fromArray($response);
    }

    /**
     * Update a subscriber list.
     */
    public function update(int $id, SubscriberList $list): SubscriberList
    {
        $response = $this->client->subscribersPut("subscriberlists/{$id}", $list->toArray());

        return SubscriberList::fromArray($response);
    }

    /**
     * Delete a subscriber list.
     */
    public function delete(int $id): array
    {
        return $this->client->subscribersDelete("subscriberlists/{$id}");
    }

    /**
     * Get all lists for a subscriber.
     */
    public function forSubscriber(int $subscriberId): array
    {
        $response = $this->client->subscribersGet("subscribers/{$subscriberId}/subscriberlists");

        return array_map(fn (array $item) => SubscriberList::fromArray($item), $response);
    }

    /**
     * Add a subscriber to a list.
     */
    public function addSubscriber(int $subscriberId, int $listId): array
    {
        return $this->client->subscribersPost("subscribers/{$subscriberId}/subscriberlists", [
            'list_id' => $listId,
        ]);
    }

    /**
     * Remove a subscriber from a list.
     */
    public function removeSubscriber(int $subscriberId, int $listId): array
    {
        return $this->client->subscribersDelete("subscribers/{$subscriberId}/subscriberlists/{$listId}");
    }
}
