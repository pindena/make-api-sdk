<?php

namespace Pindena\MakeSDK\Resources;

use Pindena\MakeSDK\DataTransferObjects\Subscriber;
use Pindena\MakeSDK\Http\MakeClient;

class SubscriberResource
{
    public function __construct(
        private readonly MakeClient $client,
    ) {}

    /**
     * Access bulk subscriber operations.
     */
    public function bulk(): SubscriberBulkResource
    {
        return new SubscriberBulkResource($this->client);
    }

    /**
     * Create a new subscriber.
     */
    public function create(Subscriber $subscriber): Subscriber
    {
        $response = $this->client->subscribersPost('subscribers', $subscriber->toArray());

        return Subscriber::fromArray($response);
    }

    /**
     * Get a subscriber by ID.
     */
    public function find(int $id): Subscriber
    {
        $response = $this->client->subscribersGet("subscribers/{$id}");

        return Subscriber::fromArray($response);
    }

    /**
     * Update a subscriber by ID.
     */
    public function update(int $id, Subscriber $subscriber): Subscriber
    {
        $response = $this->client->subscribersPut("subscribers/{$id}", $subscriber->toArray());

        return Subscriber::fromArray($response);
    }

    /**
     * Delete a subscriber by ID.
     */
    public function delete(int $id): array
    {
        return $this->client->subscribersDelete("subscribers/{$id}");
    }

    /**
     * Get subscriber stats (total counts).
     */
    public function stats(): array
    {
        return $this->client->subscribersGet('subscribers/stats');
    }

    /**
     * Find a subscriber by email address.
     */
    public function findByEmail(string $email): Subscriber
    {
        $response = $this->client->subscribersPost('subscribers/by_email', [
            'email' => $email,
        ]);

        return Subscriber::fromArray($response);
    }

    /**
     * Find a subscriber by phone number.
     */
    public function findByPhone(string $phone): Subscriber
    {
        $response = $this->client->subscribersPost('subscribers/by_phone', [
            'phone' => $phone,
        ]);

        return Subscriber::fromArray($response);
    }

    /**
     * Find a subscriber by tracking token.
     */
    public function findByTrackingToken(string $token): Subscriber
    {
        $response = $this->client->subscribersPost('subscribers/by_trackingtoken', [
            'trackingtoken' => $token,
        ]);

        return Subscriber::fromArray($response);
    }

    /**
     * Get subscribers by list ID.
     */
    public function byList(int $listId): array
    {
        $response = $this->client->subscribersGet('subscribers/by_list', [
            'list_id' => $listId,
        ]);

        return array_map(fn (array $item) => Subscriber::fromArray($item), $response);
    }

    /**
     * Get subscriber status information.
     */
    public function status(): array
    {
        return $this->client->subscribersGet('subscriberstatus');
    }

    /**
     * Unsubscribe a subscriber's email by subscriber ID.
     */
    public function unsubscribeEmail(int $id): array
    {
        return $this->client->subscribersPost("subscribers/{$id}/unsubscribe_email");
    }

    /**
     * Unsubscribe email by email address.
     */
    public function unsubscribeEmailByAddress(string $email): array
    {
        return $this->client->subscribersPost('subscribers/unsubscribe_email', [
            'email' => $email,
        ]);
    }

    /**
     * Unsubscribe a subscriber's phone by subscriber ID.
     */
    public function unsubscribePhone(int $id): array
    {
        return $this->client->subscribersPost("subscribers/{$id}/unsubscribe_phone");
    }

    /**
     * Unsubscribe phone by phone number.
     */
    public function unsubscribePhoneByNumber(string $phone): array
    {
        return $this->client->subscribersPost('subscribers/unsubscribe_phone', [
            'phone' => $phone,
        ]);
    }
}
