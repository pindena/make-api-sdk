<?php

namespace Pindena\MakeSDK;

use Pindena\MakeSDK\Http\MakeClient;
use Pindena\MakeSDK\Resources\CustomFieldResource;
use Pindena\MakeSDK\Resources\EventResource;
use Pindena\MakeSDK\Resources\NewsletterResource;
use Pindena\MakeSDK\Resources\RecurringActionResource;
use Pindena\MakeSDK\Resources\SubscriberListResource;
use Pindena\MakeSDK\Resources\SubscriberResource;
use Pindena\MakeSDK\Resources\TagResource;

class MakeSDK
{
    private MakeClient $client;

    public function __construct(
        string $username,
        string $password,
        string $subscribersApiUrl = 'https://subscribers.dialogapi.no/api/public/v2',
        string $newslettersApiUrl = 'https://newsletters.dialogapi.no/api/public/v1',
    ) {
        $this->client = new MakeClient($username, $password, $subscribersApiUrl, $newslettersApiUrl);
    }

    public function subscribers(): SubscriberResource
    {
        return new SubscriberResource($this->client);
    }

    public function subscriberlists(): SubscriberListResource
    {
        return new SubscriberListResource($this->client);
    }

    public function customFields(): CustomFieldResource
    {
        return new CustomFieldResource($this->client);
    }

    public function tags(): TagResource
    {
        return new TagResource($this->client);
    }

    public function events(): EventResource
    {
        return new EventResource($this->client);
    }

    public function newsletters(): NewsletterResource
    {
        return new NewsletterResource($this->client);
    }

    public function recurringActions(): RecurringActionResource
    {
        return new RecurringActionResource($this->client);
    }
}
