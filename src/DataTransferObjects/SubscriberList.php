<?php

namespace Pindena\MakeSDK\DataTransferObjects;

class SubscriberList
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?string $title = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?int $subscriberEmails = null,
        public readonly ?int $subscriberSms = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'] ?? null,
            dateCreated: $data['date_created'] ?? null,
            dateUpdated: $data['date_updated'] ?? null,
            subscriberEmails: $data['subscriber_emails'] ?? null,
            subscriberSms: $data['subscriber_sms'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'title' => $this->title,
            'date_created' => $this->dateCreated,
            'date_updated' => $this->dateUpdated,
            'subscriber_emails' => $this->subscriberEmails,
            'subscriber_sms' => $this->subscriberSms,
        ], fn ($value) => $value !== null);
    }
}
