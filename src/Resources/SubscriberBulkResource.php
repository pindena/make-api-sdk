<?php

namespace Pindena\MakeSDK\Resources;

use Pindena\MakeSDK\Http\MakeClient;

class SubscriberBulkResource
{
    public function __construct(
        private readonly MakeClient $client,
    ) {}

    /**
     * Bulk insert subscribers into one or more lists. 1000 subscribers max.
     *
     * @param  array<int, array<string, mixed>>  $subscribers
     * @param  array<int, int>  $subscriberListIds  Required. Lists to add the subscribers to.
     * @param  string|null  $status  Email status: active|unsubscribed|bounce|reactivate
     * @param  string|null  $statusMobile  Mobile status: active|unsubscribed|bounce|reactivate
     * @param  string|null  $tag  Tag handling mode: replace|merge (default: replace)
     */
    public function insert(
        array $subscribers,
        array $subscriberListIds,
        ?string $status = null,
        ?string $statusMobile = null,
        ?string $tag = null,
    ): array {
        return $this->client->subscribersPost(
            'subscribers/bulkinsert',
            $subscribers,
            $this->buildQuery($subscriberListIds, $status, $statusMobile, $tag),
        );
    }

    /**
     * Bulk update subscribers. 1000 subscribers max.
     *
     * @param  array<int, array<string, mixed>>  $subscribers
     * @param  array<int, int>  $subscriberListIds  Required.
     */
    public function update(
        array $subscribers,
        array $subscriberListIds,
        ?string $status = null,
        ?string $statusMobile = null,
        ?string $tag = null,
    ): array {
        return $this->client->subscribersPut(
            'subscribers/bulkupdate',
            $subscribers,
            $this->buildQuery($subscriberListIds, $status, $statusMobile, $tag),
        );
    }

    /**
     * Bulk update subscribers by external ID. 1000 subscribers max.
     *
     * @param  array<int, array<string, mixed>>  $subscribers
     * @param  array<int, int>  $subscriberListIds  Optional.
     * @param  string|null  $tag  Tag handling mode: replace|merge (default: replace)
     */
    public function updateByExternalId(
        array $subscribers,
        array $subscriberListIds = [],
        ?string $tag = null,
    ): array {
        return $this->client->subscribersPut(
            'subscribers/bulkupdate_by_external_id',
            $subscribers,
            $this->buildQuery($subscriberListIds, tag: $tag),
        );
    }

    /**
     * Bulk remove subscribers from one or more lists. 1000 entries max.
     *
     * @param  array<int, array<string, mixed>>  $subscribers
     * @param  array<int, int>  $subscriberListIds  Required.
     */
    public function remove(array $subscribers, array $subscriberListIds): array
    {
        return $this->client->subscribersPost(
            'subscribers/bulkremove',
            $subscribers,
            $this->buildQuery($subscriberListIds),
        );
    }

    /**
     * Bulk insert tickets by email.
     *
     * @param  array<int, array<string, mixed>>  $tickets
     */
    public function insertTickets(array $tickets): array
    {
        return $this->client->subscribersPost('subscribers/tickets/bulkinsert', $tickets);
    }

    /**
     * Bulk insert tickets by external ID.
     *
     * @param  array<int, array<string, mixed>>  $tickets
     */
    public function insertTicketsByExternalId(array $tickets): array
    {
        return $this->client->subscribersPost('subscribers/tickets/bulkinsert_by_external_id', $tickets);
    }

    /**
     * Bulk insert ticket events.
     *
     * @param  array<int, array<string, mixed>>  $events
     */
    public function insertTicketEvents(array $events): array
    {
        return $this->client->subscribersPost('subscribers/tickets/events/bulkinsert', $events);
    }

    /**
     * Bulk insert subscriptions by email.
     *
     * @param  array<int, array<string, mixed>>  $subscriptions
     */
    public function insertSubscriptions(array $subscriptions): array
    {
        return $this->client->subscribersPost('subscribers/subscriptions/bulkinsert', $subscriptions);
    }

    /**
     * Bulk insert subscriptions by external ID.
     *
     * @param  array<int, array<string, mixed>>  $subscriptions
     */
    public function insertSubscriptionsByExternalId(array $subscriptions): array
    {
        return $this->client->subscribersPost('subscribers/subscriptions/bulkinsert_by_external_id', $subscriptions);
    }

    /**
     * Bulk insert subscription products.
     *
     * @param  array<int, array<string, mixed>>  $products
     */
    public function insertSubscriptionProducts(array $products): array
    {
        return $this->client->subscribersPost('subscribers/subscriptions/products/bulkinsert', $products);
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
