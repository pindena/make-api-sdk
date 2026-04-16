<?php

namespace Pindena\MakeSDK\Resources;

use Pindena\MakeSDK\Http\MakeClient;

class RecurringActionResource
{
    public function __construct(
        private readonly MakeClient $client,
    ) {}

    /**
     * Get all recurring actions.
     */
    public function all(): array
    {
        return $this->client->newslettersGet('recurring_actions');
    }

    /**
     * Trigger a recurring action.
     */
    public function trigger(int $id, ?array $guids = null): array
    {
        $data = [];

        if ($guids !== null) {
            $data['guids'] = $guids;
        }

        return $this->client->newslettersPost("recurring_actions/{$id}/trigger", $data);
    }
}
