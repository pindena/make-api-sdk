<?php

use Pindena\MakeSDK\DataTransferObjects\CustomField;
use Pindena\MakeSDK\DataTransferObjects\NewsletterStats;
use Pindena\MakeSDK\DataTransferObjects\Subscriber;
use Pindena\MakeSDK\DataTransferObjects\SubscriberList;
use Pindena\MakeSDK\MakeSDK;
use Pindena\MakeSDK\Resources\CustomFieldResource;
use Pindena\MakeSDK\Resources\EventResource;
use Pindena\MakeSDK\Resources\NewsletterResource;
use Pindena\MakeSDK\Resources\RecurringActionResource;
use Pindena\MakeSDK\Resources\SubscriberBulkResource;
use Pindena\MakeSDK\Resources\SubscriberListResource;
use Pindena\MakeSDK\Resources\SubscriberResource;
use Pindena\MakeSDK\Resources\TagResource;

it('can be instantiated with plain PHP constructor', function () {
    $sdk = new MakeSDK(
        username: 'my-user',
        password: 'my-pass',
    );

    expect($sdk)->toBeInstanceOf(MakeSDK::class);
});

it('accepts custom API URLs via constructor', function () {
    $sdk = new MakeSDK(
        username: 'my-user',
        password: 'my-pass',
        subscribersApiUrl: 'https://custom-subscribers.example.com/api/v2',
        newslettersApiUrl: 'https://custom-newsletters.example.com/api/v1',
    );

    expect($sdk)->toBeInstanceOf(MakeSDK::class);
});

it('exposes all resource methods without Laravel', function () {
    $sdk = new MakeSDK(username: 'user', password: 'pass');

    expect($sdk->subscribers())->toBeInstanceOf(SubscriberResource::class)
        ->and($sdk->subscribers()->bulk())->toBeInstanceOf(SubscriberBulkResource::class)
        ->and($sdk->subscriberlists())->toBeInstanceOf(SubscriberListResource::class)
        ->and($sdk->customFields())->toBeInstanceOf(CustomFieldResource::class)
        ->and($sdk->tags())->toBeInstanceOf(TagResource::class)
        ->and($sdk->events())->toBeInstanceOf(EventResource::class)
        ->and($sdk->newsletters())->toBeInstanceOf(NewsletterResource::class)
        ->and($sdk->recurringActions())->toBeInstanceOf(RecurringActionResource::class);
});

it('returns fresh resource instances on each call', function () {
    $sdk = new MakeSDK(username: 'user', password: 'pass');

    $a = $sdk->subscribers();
    $b = $sdk->subscribers();

    expect($a)->toBeInstanceOf(SubscriberResource::class)
        ->and($b)->toBeInstanceOf(SubscriberResource::class)
        ->and($a)->not->toBe($b);
});

it('can build DTOs without any container or framework', function () {
    $subscriber = Subscriber::fromArray([
        'id' => 99,
        'email' => 'plain@php.dev',
        'firstname' => 'Plain',
        'lastname' => 'PHP',
        'external_id' => 'ext-plain',
        'tags' => ['cli', 'script'],
        'date_created' => '2026-01-01',
    ]);

    expect($subscriber->id)->toBe(99)
        ->and($subscriber->email)->toBe('plain@php.dev')
        ->and($subscriber->firstname)->toBe('Plain')
        ->and($subscriber->externalId)->toBe('ext-plain')
        ->and($subscriber->tags)->toBe(['cli', 'script'])
        ->and($subscriber->dateCreated)->toBe('2026-01-01');
});

it('can round-trip a Subscriber through fromArray and toArray', function () {
    $input = [
        'id' => 7,
        'email' => 'roundtrip@test.com',
        'firstname' => 'Round',
        'lastname' => 'Trip',
        'phone' => '+4712345678',
        'external_id' => 'rt-7',
        'tags' => ['test'],
    ];

    $dto = Subscriber::fromArray($input);
    $output = $dto->toArray();

    expect($output)->toHaveCount(count($input))
        ->and($output['id'])->toBe(7)
        ->and($output['email'])->toBe('roundtrip@test.com')
        ->and($output['firstname'])->toBe('Round')
        ->and($output['lastname'])->toBe('Trip')
        ->and($output['phone'])->toBe('+4712345678')
        ->and($output['external_id'])->toBe('rt-7')
        ->and($output['tags'])->toBe(['test']);
});

it('can round-trip a SubscriberList through fromArray and toArray', function () {
    $original = [
        'id' => 3,
        'title' => 'VIP Customers',
        'date_created' => '2026-03-01',
        'date_updated' => '2026-04-10',
        'subscriber_emails' => 42,
        'subscriber_sms' => 8,
    ];

    $dto = SubscriberList::fromArray($original);
    $output = $dto->toArray();

    expect($output)->toBe($original);
});

it('can round-trip a CustomField through fromArray and toArray', function () {
    $original = [
        'id' => 2,
        'label' => 'Membership',
        'value' => 'EXTRA3',
        'choices' => ['gold', 'silver', 'bronze'],
        'datatype' => 'dropdown',
    ];

    $dto = CustomField::fromArray($original);
    $output = $dto->toArray();

    expect($output)->toBe($original);
});

it('can round-trip a NewsletterStats through fromArray and toArray', function () {
    $original = [
        'id' => 10,
        'sending_id' => 55,
        'clicks' => 120,
        'unique_clicks' => 80,
        'opens' => 300,
        'openrate' => 0.65,
        'subject' => 'Monthly Digest',
        'from_email' => 'news@example.com',
    ];

    $dto = NewsletterStats::fromArray($original);
    $output = $dto->toArray();

    expect($output)->toBe($original);
});


it('captures custom fields from API response into customFields', function () {
    $data = [
        'id' => 1,
        'email' => 'james@example.com',
        'firstname' => 'james',
        'CUSTOMPROPERTY' => 1,
        'CUSTOMPROPERTY2' => 'hello',
        'EXTRA1' => 'some value',
    ];

    $subscriber = Subscriber::fromArray($data);

    expect($subscriber->id)->toBe(1)
        ->and($subscriber->email)->toBe('james@example.com')
        ->and($subscriber->firstname)->toBe('james')
        ->and($subscriber->customFields)->toBe([
            'CUSTOMPROPERTY' => 1,
            'CUSTOMPROPERTY2' => 'hello',
            'EXTRA1' => 'some value',
        ]);
});

it('provides a helper to access a single custom field', function () {
    $subscriber = Subscriber::fromArray([
        'email' => 'test@example.com',
        'MEMBERSHIP' => 'gold',
        'POINTS' => 42,
    ]);

    expect($subscriber->customField('MEMBERSHIP'))->toBe('gold')
        ->and($subscriber->customField('POINTS'))->toBe(42)
        ->and($subscriber->customField('NONEXISTENT'))->toBeNull()
        ->and($subscriber->customField('NONEXISTENT', 'fallback'))->toBe('fallback');
});

it('round-trips custom fields through toArray', function () {
    $input = [
        'id' => 5,
        'email' => 'custom@example.com',
        'EXTRA1' => 'value1',
        'EXTRA2' => 'value2',
    ];

    $subscriber = Subscriber::fromArray($input);
    $output = $subscriber->toArray();

    expect($output)->toHaveKey('EXTRA1', 'value1')
        ->and($output)->toHaveKey('EXTRA2', 'value2')
        ->and($output)->toHaveKey('id', 5)
        ->and($output)->toHaveKey('email', 'custom@example.com');
});

it('can construct a subscriber with custom fields directly', function () {
    $subscriber = new Subscriber(
        email: 'direct@example.com',
        customFields: ['LOYALTY' => 'platinum', 'SCORE' => 99],
    );

    expect($subscriber->email)->toBe('direct@example.com')
        ->and($subscriber->customField('LOYALTY'))->toBe('platinum')
        ->and($subscriber->customField('SCORE'))->toBe(99)
        ->and($subscriber->toArray())->toHaveKey('LOYALTY', 'platinum');
});
