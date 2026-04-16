<?php

use Pindena\MakeSDK\DataTransferObjects\CustomField;
use Pindena\MakeSDK\DataTransferObjects\NewsletterStats;
use Pindena\MakeSDK\DataTransferObjects\Subscriber;
use Pindena\MakeSDK\DataTransferObjects\SubscriberList;
use Pindena\MakeSDK\Facades\Make;
use Pindena\MakeSDK\MakeSDK;

it('can resolve MakeSDK from the container', function () {
    $sdk = app(MakeSDK::class);

    expect($sdk)->toBeInstanceOf(MakeSDK::class);
});

it('can resolve MakeSDK via the facade', function () {
    expect(Make::getFacadeRoot())->toBeInstanceOf(MakeSDK::class);
});

it('exposes all resource methods', function () {
    $sdk = app(MakeSDK::class);

    expect($sdk->subscribers())->toBeInstanceOf(\Pindena\MakeSDK\Resources\SubscriberResource::class)
        ->and($sdk->subscribers()->bulk())->toBeInstanceOf(\Pindena\MakeSDK\Resources\SubscriberBulkResource::class)
        ->and($sdk->subscriberlists())->toBeInstanceOf(\Pindena\MakeSDK\Resources\SubscriberListResource::class)
        ->and($sdk->customFields())->toBeInstanceOf(\Pindena\MakeSDK\Resources\CustomFieldResource::class)
        ->and($sdk->tags())->toBeInstanceOf(\Pindena\MakeSDK\Resources\TagResource::class)
        ->and($sdk->events())->toBeInstanceOf(\Pindena\MakeSDK\Resources\EventResource::class)
        ->and($sdk->newsletters())->toBeInstanceOf(\Pindena\MakeSDK\Resources\NewsletterResource::class)
        ->and($sdk->recurringActions())->toBeInstanceOf(\Pindena\MakeSDK\Resources\RecurringActionResource::class);
});

it('can create a Subscriber DTO from array', function () {
    $data = [
        'id' => 1,
        'email' => 'john@example.com',
        'firstname' => 'John',
        'lastname' => 'Doe',
        'external_id' => 'ext-123',
        'tags' => ['vip', 'active'],
    ];

    $subscriber = Subscriber::fromArray($data);

    expect($subscriber->id)->toBe(1)
        ->and($subscriber->email)->toBe('john@example.com')
        ->and($subscriber->firstname)->toBe('John')
        ->and($subscriber->lastname)->toBe('Doe')
        ->and($subscriber->externalId)->toBe('ext-123')
        ->and($subscriber->tags)->toBe(['vip', 'active']);
});

it('can convert a Subscriber DTO to array', function () {
    $subscriber = new Subscriber(
        id: 1,
        email: 'john@example.com',
        firstname: 'John',
    );

    $array = $subscriber->toArray();

    expect($array)->toBe([
        'id' => 1,
        'email' => 'john@example.com',
        'firstname' => 'John',
    ]);
});

it('can create a SubscriberList DTO from array', function () {
    $data = [
        'id' => 10,
        'title' => 'My List',
        'subscriber_emails' => 150,
    ];

    $list = SubscriberList::fromArray($data);

    expect($list->id)->toBe(10)
        ->and($list->title)->toBe('My List')
        ->and($list->subscriberEmails)->toBe(150);
});

it('can create a CustomField DTO from array', function () {
    $data = [
        'id' => 5,
        'label' => 'Favorite Color',
        'datatype' => 'string',
        'choices' => ['red', 'blue', 'green'],
    ];

    $field = CustomField::fromArray($data);

    expect($field->id)->toBe(5)
        ->and($field->label)->toBe('Favorite Color')
        ->and($field->datatype)->toBe('string')
        ->and($field->choices)->toBe(['red', 'blue', 'green']);
});

it('can create a NewsletterStats DTO from array', function () {
    $data = [
        'id' => 42,
        'sending_id' => 100,
        'clicks' => 250,
        'unique_clicks' => 180,
        'opens' => 500,
        'openrate' => '0.75',
        'subject' => 'Weekly Update',
    ];

    $stats = NewsletterStats::fromArray($data);

    expect($stats->id)->toBe(42)
        ->and($stats->sendingId)->toBe(100)
        ->and($stats->clicks)->toBe(250)
        ->and($stats->uniqueClicks)->toBe(180)
        ->and($stats->opens)->toBe(500)
        ->and($stats->openrate)->toBe(0.75)
        ->and($stats->subject)->toBe('Weekly Update');
});

it('publishes the config file', function () {
    $configPath = config_path('make-sdk.php');

    // The config should already be loaded via the service provider
    expect(config('make-sdk.username'))->toBe('test-user')
        ->and(config('make-sdk.password'))->toBe('test-pass');
});
