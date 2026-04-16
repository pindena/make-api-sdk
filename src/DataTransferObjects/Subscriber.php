<?php

namespace Pindena\MakeSDK\DataTransferObjects;

class Subscriber
{
    /** @var list<string> Keys recognised as standard subscriber fields */
    private const KNOWN_KEYS = [
        'id', 'email', 'firstname', 'lastname', 'address', 'zip', 'city',
        'phone', 'company', 'birthday', 'gender', 'tags', 'lists',
        'external_id', 'date_created', 'date_updated', 'email_status', 'phone_status',
    ];

    /**
     * @param array<string, mixed> $customFields Upper-case custom field values, e.g. ['EXTRA1' => 'foo']
     */
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?string $email = null,
        public readonly ?string $firstname = null,
        public readonly ?string $lastname = null,
        public readonly ?string $address = null,
        public readonly ?string $zip = null,
        public readonly ?string $city = null,
        public readonly ?string $phone = null,
        public readonly ?string $company = null,
        public readonly ?string $birthday = null,
        public readonly ?string $gender = null,
        public readonly ?array $tags = null,
        public readonly ?array $lists = null,
        public readonly ?string $externalId = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $emailStatus = null,
        public readonly ?string $phoneStatus = null,
        public readonly array $customFields = [],
    ) {}

    public static function fromArray(array $data): self
    {
        $customFields = array_diff_key($data, array_flip(self::KNOWN_KEYS));

        return new self(
            id: $data['id'] ?? null,
            email: $data['email'] ?? null,
            firstname: $data['firstname'] ?? null,
            lastname: $data['lastname'] ?? null,
            address: $data['address'] ?? null,
            zip: $data['zip'] ?? null,
            city: $data['city'] ?? null,
            phone: $data['phone'] ?? null,
            company: $data['company'] ?? null,
            birthday: $data['birthday'] ?? null,
            gender: $data['gender'] ?? null,
            tags: $data['tags'] ?? null,
            lists: $data['lists'] ?? null,
            externalId: $data['external_id'] ?? null,
            dateCreated: $data['date_created'] ?? null,
            dateUpdated: $data['date_updated'] ?? null,
            emailStatus: $data['email_status'] ?? null,
            phoneStatus: $data['phone_status'] ?? null,
            customFields: $customFields,
        );
    }

    /**
     * Get a single custom field value by its key (e.g. 'EXTRA1').
     */
    public function customField(string $key, mixed $default = null): mixed
    {
        return $this->customFields[$key] ?? $default;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'address' => $this->address,
            'zip' => $this->zip,
            'city' => $this->city,
            'phone' => $this->phone,
            'company' => $this->company,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'tags' => $this->tags,
            'lists' => $this->lists,
            'external_id' => $this->externalId,
            'date_created' => $this->dateCreated,
            'date_updated' => $this->dateUpdated,
            'email_status' => $this->emailStatus,
            'phone_status' => $this->phoneStatus,
            ...$this->customFields,
        ], fn ($value) => $value !== null);
    }
}
