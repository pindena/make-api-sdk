<?php

namespace Pindena\MakeSDK\Resources;

use Pindena\MakeSDK\Http\MakeClient;

class SubscriberBulkResource
{
    public function __construct(
        private readonly MakeClient $client,
    ) {}

    /**
     * Bulk insert subscribers.
     */
    public function insert(array $subscribers): array
    {
        return $this->client->subscribersPost('subscribers/bulkinsert', [
            'subscribers' => $subscribers,
        ]);
    }

    /**
     * Bulk update subscribers.
     */
    public function update(array $subscribers): array
    {
        return $this->client->subscribersPut('subscribers/bulkupdate', [
            'subscribers' => $subscribers,
        ]);
    }

    /**
     * Bulk update subscribers by external ID.
     */
    public function updateByExternalId(array $subscribers): array
    {
        return $this->client->subscribersPut('subscribers/bulkupdate_by_external_id', [
            'subscribers' => $subscribers,
        ]);
    }

    /**
     * Bulk remove subscribers.
     */
    public function remove(array $subscribers): array
    {
        return $this->client->subscribersPost('subscribers/bulkremove', [
            'subscribers' => $subscribers,
        ]);
    }

    /**
     * Bulk insert tickets by email.
     */
    public function insertTickets(array $tickets): array
    {
        return $this->client->subscribersPost('subscribers/tickets/bulkinsert', [
            'tickets' => $tickets,
        ]);
    }

    /**
     * Bulk insert tickets by external ID.
     */
    public function insertTicketsByExternalId(array $tickets): array
    {
        return $this->client->subscribersPost('subscribers/tickets/bulkinsert_by_external_id', [
            'tickets' => $tickets,
        ]);
    }

    /**
     * Bulk insert ticket events.
     */
    public function insertTicketEvents(array $events): array
    {
        return $this->client->subscribersPost('subscribers/tickets/events/bulkinsert', [
            'events' => $events,
        ]);
    }

    /**
     * Bulk insert subscriptions by email.
     */
    public function insertSubscriptions(array $subscriptions): array
    {
        return $this->client->subscribersPost('subscribers/subscriptions/bulkinsert', [
            'subscriptions' => $subscriptions,
        ]);
    }

    /**
     * Bulk insert subscriptions by external ID.
     */
    public function insertSubscriptionsByExternalId(array $subscriptions): array
    {
        return $this->client->subscribersPost('subscribers/subscriptions/bulkinsert_by_external_id', [
            'subscriptions' => $subscriptions,
        ]);
    }

    /**
     * Bulk insert subscription products.
     */
    public function insertSubscriptionProducts(array $products): array
    {
        return $this->client->subscribersPost('subscribers/subscriptions/products/bulkinsert', [
            'products' => $products,
        ]);
    }
}
