<?php

namespace Pindena\MakeSDK\DataTransferObjects;

class NewsletterSettings
{
    /**
     * @param int|null    $segmentId   Segment ID to send to
     * @param int[]|null  $listIds     List IDs to send to
     * @param int|null    $senderId    Sender ID to use
     * @param string|null $scheduledAt Scheduled send time (date-time format)
     */
    public function __construct(
        public readonly ?int $segmentId = null,
        public readonly ?array $listIds = null,
        public readonly ?int $senderId = null,
        public readonly ?string $scheduledAt = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            segmentId: $data['segment_id'] ?? null,
            listIds: $data['list_ids'] ?? null,
            senderId: $data['sender_id'] ?? null,
            scheduledAt: $data['scheduled_at'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'segment_id' => $this->segmentId,
            'list_ids' => $this->listIds,
            'sender_id' => $this->senderId,
            'scheduled_at' => $this->scheduledAt,
        ], fn ($value) => $value !== null);
    }
}
