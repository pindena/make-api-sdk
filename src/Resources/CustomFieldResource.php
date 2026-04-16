<?php

namespace Pindena\MakeSDK\Resources;

use Pindena\MakeSDK\DataTransferObjects\CustomField;
use Pindena\MakeSDK\Http\MakeClient;

class CustomFieldResource
{
    public function __construct(
        private readonly MakeClient $client,
    ) {}

    /**
     * Get all custom fields.
     */
    public function all(): array
    {
        $response = $this->client->subscribersGet('custom_fields');

        return array_map(fn (array $item) => CustomField::fromArray($item), $response);
    }

    /**
     * Create a new custom field.
     */
    public function create(CustomField $field): CustomField
    {
        $response = $this->client->subscribersPost('custom_fields', $field->toArray());

        return CustomField::fromArray($response);
    }

    /**
     * Get a custom field by ID.
     */
    public function find(int $id): CustomField
    {
        $response = $this->client->subscribersGet("custom_fields/{$id}");

        return CustomField::fromArray($response);
    }

    /**
     * Update a custom field.
     */
    public function update(int $id, CustomField $field): CustomField
    {
        $response = $this->client->subscribersPut("custom_fields/{$id}", $field->toArray());

        return CustomField::fromArray($response);
    }

    /**
     * Delete a custom field.
     */
    public function delete(int $id): array
    {
        return $this->client->subscribersDelete("custom_fields/{$id}");
    }
}
