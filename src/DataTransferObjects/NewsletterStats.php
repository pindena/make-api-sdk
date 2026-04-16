<?php

namespace Pindena\MakeSDK\DataTransferObjects;

class NewsletterStats
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?int $sendingId = null,
        public readonly ?int $clicks = null,
        public readonly ?int $uniqueClicks = null,
        public readonly ?int $opens = null,
        public readonly ?int $bounce = null,
        public readonly ?int $unsubscribes = null,
        public readonly ?string $trackingMode = null,
        public readonly ?int $sent = null,
        public readonly ?string $createdAt = null,
        public readonly ?string $sentAt = null,
        public readonly ?float $openrate = null,
        public readonly ?string $mailbody = null,
        public readonly ?string $newsletterScreenshotUrl = null,
        public readonly ?int $delivered = null,
        public readonly ?string $subject = null,
        public readonly ?string $fromEmail = null,
        public readonly ?array $tags = null,
        public readonly ?array $lists = null,
        public readonly ?array $segment = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            sendingId: $data['sending_id'] ?? null,
            clicks: $data['clicks'] ?? null,
            uniqueClicks: $data['unique_clicks'] ?? null,
            opens: $data['opens'] ?? null,
            bounce: $data['bounce'] ?? null,
            unsubscribes: $data['unsubscribes'] ?? null,
            trackingMode: $data['tracking_mode'] ?? null,
            sent: $data['sent'] ?? null,
            createdAt: $data['created_at'] ?? null,
            sentAt: $data['sent_at'] ?? null,
            openrate: isset($data['openrate']) ? (float) $data['openrate'] : null,
            mailbody: $data['mailbody'] ?? null,
            newsletterScreenshotUrl: $data['newsletter_screenshot_url'] ?? null,
            delivered: $data['delivered'] ?? null,
            subject: $data['subject'] ?? null,
            fromEmail: $data['from_email'] ?? null,
            tags: $data['tags'] ?? null,
            lists: $data['lists'] ?? null,
            segment: $data['segment'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'sending_id' => $this->sendingId,
            'clicks' => $this->clicks,
            'unique_clicks' => $this->uniqueClicks,
            'opens' => $this->opens,
            'bounce' => $this->bounce,
            'unsubscribes' => $this->unsubscribes,
            'tracking_mode' => $this->trackingMode,
            'sent' => $this->sent,
            'created_at' => $this->createdAt,
            'sent_at' => $this->sentAt,
            'openrate' => $this->openrate,
            'mailbody' => $this->mailbody,
            'newsletter_screenshot_url' => $this->newsletterScreenshotUrl,
            'delivered' => $this->delivered,
            'subject' => $this->subject,
            'from_email' => $this->fromEmail,
            'tags' => $this->tags,
            'lists' => $this->lists,
            'segment' => $this->segment,
        ], fn ($value) => $value !== null);
    }
}
