<?php

namespace Pindena\MakeSDK\Resources;

use Pindena\MakeSDK\DataTransferObjects\NewsletterSettings;
use Pindena\MakeSDK\DataTransferObjects\NewsletterStats;
use Pindena\MakeSDK\Http\MakeClient;

class NewsletterResource
{
    public function __construct(
        private readonly MakeClient $client,
    ) {}

    /**
     * Send a newsletter.
     */
    public function send(int $id, NewsletterSettings $settings = new NewsletterSettings()): NewsletterStats
    {
        $response = $this->client->newslettersPost("newsletters/{$id}/send", $settings->toArray());

        return NewsletterStats::fromArray($response);
    }

    /**
     * Get stats for all newsletters.
     */
    public function stats(): array
    {
        $response = $this->client->newslettersGet('newsletters/stats');

        return array_map(fn (array $item) => NewsletterStats::fromArray($item), $response);
    }

    /**
     * Get stats for a specific newsletter by ID.
     */
    public function statsById(int $id): NewsletterStats
    {
        $response = $this->client->newslettersGet("newsletters/{$id}/stats");

        return NewsletterStats::fromArray($response);
    }

    /**
     * Get stats for a newsletter by external GUID.
     */
    public function statsByExternalGuid(int $id): NewsletterStats
    {
        $response = $this->client->newslettersGet("newsletters/{$id}/stats_by_external_guid");

        return NewsletterStats::fromArray($response);
    }

    /**
     * Get newsletters by subscriber email.
     */
    public function bySubscriberEmail(string $email, array $params = []): array
    {
        return $this->client->subscribersGet('newsletters/by_subscribers_email', array_merge(
            ['email' => $email],
            $params,
        ));
    }

    /**
     * Get newsletters by subscriber external ID.
     */
    public function bySubscriberExternalId(string $externalId, array $params = []): array
    {
        return $this->client->subscribersGet('newsletters/by_subscribers_external_id', array_merge(
            ['external_id' => $externalId],
            $params,
        ));
    }
}
