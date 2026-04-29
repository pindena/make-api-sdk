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
     * Create or update a subscriber. Adds them to the given subscriber lists.
     *
     * @param  array<int, int>  $subscriberListIds  Required. Lists to add the subscriber to.
     * @param  string|null  $status  Email status: active|unsubscribed|bounce
     * @param  string|null  $statusMobile  Mobile status: active|unsubscribed|bounce
     * @param  string|null  $tag  Tag handling mode: replace|merge (default: replace)
     */
    public function create(
        Subscriber $subscriber,
        array $subscriberListIds,
        ?string $status = null,
        ?string $statusMobile = null,
        ?string $tag = null,
    ): Subscriber {
        $response = $this->client->subscribersPost(
            'subscribers',
            $subscriber->toArray(),
            $this->buildQuery($subscriberListIds, $status, $statusMobile, $tag),
        );

        return Subscriber::fromArray($response);
    }

    /**
     * Get a subscriber by ID.
     */
    public function find(int $id, ?string $externalId = null): Subscriber
    {
        $response = $this->client->subscribersGet(
            "subscribers/{$id}",
            $externalId !== null ? ['external_id' => $externalId] : [],
        );

        return Subscriber::fromArray($response);
    }

    /**
     * Update a subscriber by ID.
     *
     * @param  array<int, int>  $subscriberListIds  Optional. Lists to update membership for.
     */
    public function update(
        int $id,
        Subscriber $subscriber,
        array $subscriberListIds = [],
        ?string $status = null,
        ?string $statusMobile = null,
        ?string $tag = null,
    ): Subscriber {
        $response = $this->client->subscribersPatch(
            "subscribers/{$id}",
            $subscriber->toArray(),
            $this->buildQuery($subscriberListIds, $status, $statusMobile, $tag),
        );

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
     * Get subscriber stats (total counts). Optionally filter by subscriber email.
     */
    public function stats(?string $email = null): array
    {
        return $this->client->subscribersGet(
            'subscribers/stats',
            $email !== null ? ['email' => $email] : [],
        );
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
     *
     * @param  string|null  $status  Email status filter: active|unsubscribed|bounce (default: active)
     * @param  string|null  $statusMobile  Mobile status filter: active|unsubscribed|bounce (default: active)
     * @param  string|null  $updatedAfter  ISO8601 timestamp filter
     */
    public function byList(
        int $listId,
        ?int $page = null,
        ?int $perPage = null,
        ?string $status = null,
        ?string $statusMobile = null,
        ?string $updatedAfter = null,
    ): array {
        $query = array_filter([
            'list_id' => $listId,
            'page' => $page,
            'per_page' => $perPage,
            'status' => $status,
            'status_mobile' => $statusMobile,
            'updated_after' => $updatedAfter,
        ], fn ($value) => $value !== null);

        $response = $this->client->subscribersGet('subscribers/by_list', $query);

        return array_map(fn (array $item) => Subscriber::fromArray($item), $response);
    }

    /**
     * Get subscriber status information. Optionally filter by date range.
     */
    public function status(?string $fromDate = null, ?string $toDate = null): array
    {
        $query = array_filter([
            'from_date' => $fromDate,
            'to_date' => $toDate,
        ], fn ($value) => $value !== null);

        return $this->client->subscribersGet('subscriberstatus', $query);
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

    /**
     * @param  array<int, int>  $subscriberListIds
     * @return array<string, mixed>
     */
    private function buildQuery(
        array $subscriberListIds = [],
        ?string $status = null,
        ?string $statusMobile = null,
        ?string $tag = null,
    ): array {
        return array_filter([
            'subscriber_list_id' => $subscriberListIds !== [] ? $subscriberListIds : null,
            'status' => $status,
            'status_mobile' => $statusMobile,
            'tag' => $tag,
        ], fn ($value) => $value !== null);
    }
}
