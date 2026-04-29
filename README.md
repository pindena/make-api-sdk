# Make SDK

A PHP SDK for [Make AS](https://make.as)' API — subscribers, newsletters, and recurring actions.

## Requirements

- PHP 8.2+
- Laravel 11+ (optional — works with plain PHP too)

## Installation

```bash
composer require pindena/make-sdk
```

## Authentication

The Make API uses HTTP Basic Authentication. Your **Userid** is the username and your **APIKey** is the password (generate one from the Make dashboard).

### Laravel

Add credentials to your `.env` file:

```dotenv
MAKE_API_USERNAME=your-userid
MAKE_API_PASSWORD=your-api-key
```

The package auto-discovers the service provider and facade. Publish the config file if needed:

```bash
php artisan vendor:publish --tag=make-sdk-config
```

Then use the `Make` facade:

```php
use Pindena\MakeSDK\Facades\Make;

$lists = Make::subscriberlists()->all();
```

### Plain PHP

```php
use Pindena\MakeSDK\MakeSDK;

$sdk = new MakeSDK(
    username: 'your-userid',
    password: 'your-api-key',
);

$lists = $sdk->subscriberlists()->all();
```

You may also override the default API base URLs:

```php
$sdk = new MakeSDK(
    username: 'your-userid',
    password: 'your-api-key',
    subscribersApiUrl: 'https://subscribers.dialogapi.no/api/public/v2',
    newslettersApiUrl: 'https://newsletters.dialogapi.no/api/public/v1',
);
```

---

## Available Methods

> All examples below use the `Make` facade. When using plain PHP, replace `Make::` with `$sdk->`.

---

### Subscribers

Access via `Make::subscribers()`.

| Method | Description | Returns |
|---|---|---|
| `create(Subscriber $subscriber, array $subscriberListIds, ?string $status = null, ?string $statusMobile = null, ?string $tag = null)` | Create or update a subscriber and add them to one or more lists | `Subscriber` |
| `find(int $id, ?string $externalId = null)` | Get a subscriber by ID | `Subscriber` |
| `update(int $id, Subscriber $subscriber, array $subscriberListIds = [], ?string $status = null, ?string $statusMobile = null, ?string $tag = null)` | Update a subscriber (uses `PATCH`) | `Subscriber` |
| `delete(int $id)` | Delete a subscriber | `array` |
| `findByEmail(string $email)` | Find subscriber by email | `Subscriber` |
| `findByPhone(string $phone)` | Find subscriber by phone | `Subscriber` |
| `findByTrackingToken(string $token)` | Find subscriber by tracking token | `Subscriber` |
| `byList(int $listId, ?int $page = null, ?int $perPage = null, ?string $status = null, ?string $statusMobile = null, ?string $updatedAfter = null)` | Get all subscribers in a list (paginated, max 1000 per page) | `Subscriber[]` |
| `stats(?string $email = null)` | Get subscriber stats (total counts) | `array` |
| `status(?string $fromDate = null, ?string $toDate = null)` | Get subscriber status information | `array` |
| `unsubscribeEmail(int $id)` | Unsubscribe email by subscriber ID | `array` |
| `unsubscribeEmailByAddress(string $email)` | Unsubscribe email by address | `array` |
| `unsubscribePhone(int $id)` | Unsubscribe phone by subscriber ID | `array` |
| `unsubscribePhoneByNumber(string $phone)` | Unsubscribe phone by number | `array` |
| `bulk()` | Access bulk operations | `SubscriberBulkResource` |

> **`subscriberListIds` is required when creating a subscriber.** The Make API expects you to specify which
> subscriber list(s) the subscriber belongs to via the `subscriber_list_id[]` query parameter. Optional `status`,
> `statusMobile`, and `tag` parameters control the email/mobile status (`active`, `unsubscribed`, `bounce`) and
> tag-handling mode (`replace`, `merge`).

```php
use Pindena\MakeSDK\DataTransferObjects\Subscriber;

// Create a subscriber and add them to lists 1 and 2
$subscriber = Make::subscribers()->create(
    new Subscriber(
        email: 'john@example.com',
        firstname: 'John',
        lastname: 'Doe',
        tags: ['vip'],
        customFields: ['EXTRA1' => 'custom value'],
    ),
    subscriberListIds: [1, 2],
    tag: 'merge',
);

// Update a subscriber
Make::subscribers()->update($subscriber->id, new Subscriber(
    firstname: 'Jane',
));

// Find by email
$subscriber = Make::subscribers()->findByEmail('john@example.com');

// Get a paginated slice of subscribers in a list
$subscribers = Make::subscribers()->byList(1, page: 1, perPage: 100);
```

---

### Subscriber Bulk Operations

Access via `Make::subscribers()->bulk()`. **Maximum 1000 entries per call.**

| Method | Description |
|---|---|
| `insert(array $subscribers, array $subscriberListIds, ?string $status = null, ?string $statusMobile = null, ?string $tag = null)` | Bulk insert subscribers into list(s) |
| `update(array $subscribers, array $subscriberListIds, ?string $status = null, ?string $statusMobile = null, ?string $tag = null)` | Bulk update subscribers in list(s) |
| `updateByExternalId(array $subscribers, array $subscriberListIds = [], ?string $tag = null)` | Bulk update by external ID |
| `remove(array $subscribers, array $subscriberListIds)` | Bulk remove subscribers from list(s) |
| `insertTickets(array $tickets)` | Bulk insert tickets by email |
| `insertTicketsByExternalId(array $tickets)` | Bulk insert tickets by external ID |
| `insertTicketEvents(array $events)` | Bulk insert ticket events |
| `insertSubscriptions(array $subscriptions)` | Bulk insert subscriptions by email |
| `insertSubscriptionsByExternalId(array $subscriptions)` | Bulk insert subscriptions by external ID |
| `insertSubscriptionProducts(array $products)` | Bulk insert subscription products |

> **`$subscriberListIds` is required for `insert()`, `update()`, and `remove()`.** It is sent as a
> `subscriber_list_id[]` query parameter and tells the Make API which list(s) the operation targets.
> For `updateByExternalId()` it is optional. Optional `status`, `statusMobile`, and `tag` parameters
> are also forwarded as query params (see the [API docs](docs/make_api_subscribers_swagger.json) for valid values).

```php
$users = [
    ['email' => 'a@example.com', 'firstname' => 'Alice'],
    ['email' => 'b@example.com', 'firstname' => 'Bob'],
];

// Insert into lists 1 and 2
Make::subscribers()->bulk()->insert($users, subscriberListIds: [1, 2]);

// Update with custom status and merge tags
Make::subscribers()->bulk()->update($users, subscriberListIds: [1], tag: 'merge');

// Remove from list 1
Make::subscribers()->bulk()->remove(
    [['email' => 'a@example.com']],
    subscriberListIds: [1],
);
```

---

### Subscriber Lists

Access via `Make::subscriberlists()`.

| Method | Description | Returns |
|---|---|---|
| `all()` | Get all subscriber lists | `SubscriberList[]` |
| `create(SubscriberList $list)` | Create a new list | `SubscriberList` |
| `find(int $id)` | Get a list by ID | `SubscriberList` |
| `update(int $id, SubscriberList $list)` | Update a list | `SubscriberList` |
| `delete(int $id)` | Delete a list | `array` |
| `forSubscriber(int $subscriberId)` | Get all lists for a subscriber | `SubscriberList[]` |
| `addSubscriber(int $subscriberId, int $listId)` | Add subscriber to list | `array` |
| `removeSubscriber(int $subscriberId, int $listId)` | Remove subscriber from list | `array` |

```php
use Pindena\MakeSDK\DataTransferObjects\SubscriberList;

$lists = Make::subscriberlists()->all();
$list = Make::subscriberlists()->create(new SubscriberList(title: 'VIP Customers'));
Make::subscriberlists()->addSubscriber($subscriberId, $list->id);
```

---

### Custom Fields

Access via `Make::customFields()`.

| Method | Description | Returns |
|---|---|---|
| `all()` | Get all custom fields | `CustomField[]` |
| `create(CustomField $field)` | Create a new custom field | `CustomField` |
| `find(int $id)` | Get a custom field by ID | `CustomField` |
| `update(int $id, CustomField $field)` | Update a custom field | `CustomField` |
| `delete(int $id)` | Delete a custom field | `array` |

```php
use Pindena\MakeSDK\DataTransferObjects\CustomField;

$fields = Make::customFields()->all();

$field = Make::customFields()->create(new CustomField(
    label: 'Membership Level',
    datatype: 'dropdown',
    choices: ['gold', 'silver', 'bronze'],
));
```

---

### Tags

Access via `Make::tags()`.

| Method | Description | Returns |
|---|---|---|
| `all()` | Get all subscriber tags | `array` |
| `create(string $title)` | Create a new tag | `array` |
| `find(int $id)` | Get a tag by ID | `array` |
| `update(int $id, string $title)` | Update a tag | `array` |
| `delete(int $id)` | Delete a tag | `array` |

```php
$tags = Make::tags()->all();
Make::tags()->create('newsletter-subscriber');
```

---

### Events

Access via `Make::events()`.

| Method | Description | Returns |
|---|---|---|
| `bounces(array $params = [])` | Get bounce events | `array` |
| `clicks(array $params = [])` | Get click events | `array` |
| `opens(array $params = [])` | Get open events | `array` |
| `sendings(array $params = [])` | Get sending events | `array` |
| `unsubscribes(array $params = [])` | Get unsubscribe events | `array` |

```php
$bounces = Make::events()->bounces();
$clicks = Make::events()->clicks(['from_date' => '2026-01-01']);
```

---

### Newsletters

Access via `Make::newsletters()`.

| Method | Description | Returns |
|---|---|---|
| `send(int $id, NewsletterSettings $settings = new NewsletterSettings())` | Send a newsletter | `NewsletterStats` |
| `stats()` | Get stats for all newsletters | `NewsletterStats[]` |
| `statsById(int $id)` | Get stats for a specific newsletter | `NewsletterStats` |
| `statsByExternalGuid(int $id)` | Get stats by external GUID | `NewsletterStats` |
| `bySubscriberEmail(string $email, array $params = [])` | Get newsletters for a subscriber email | `array` |
| `bySubscriberExternalId(string $externalId, array $params = [])` | Get newsletters for a subscriber external ID | `array` |

```php
use Pindena\MakeSDK\DataTransferObjects\NewsletterSettings;

// Send a newsletter (ID is preconfigured in the Make dashboard)
$stats = Make::newsletters()->send(5, new NewsletterSettings(
    segmentId: 0,
    listIds: [1, 2],
    senderId: 0,
    scheduledAt: '2026-05-01 10:00:00',
));

// Get all newsletter stats
$allStats = Make::newsletters()->stats();

// Get stats for a specific newsletter
$stats = Make::newsletters()->statsById(5);
```

---

### Recurring Actions

Access via `Make::recurringActions()`.

| Method | Description | Returns |
|---|---|---|
| `all()` | Get all recurring actions | `array` |
| `trigger(int $id, ?array $guids = null)` | Trigger a recurring action | `array` |

```php
$actions = Make::recurringActions()->all();
Make::recurringActions()->trigger(1);
```

---

## Data Transfer Objects

The SDK returns typed DTOs for core resources. All DTOs provide `fromArray()` and `toArray()` methods.

### `Subscriber`

Properties: `id`, `email`, `firstname`, `lastname`, `address`, `zip`, `city`, `phone`, `company`, `birthday`, `gender`, `tags`, `lists`, `externalId`, `dateCreated`, `dateUpdated`, `emailStatus`, `phoneStatus`, `customFields`.

Custom fields from the API (uppercase keys like `EXTRA1`) are automatically captured into the `customFields` array:

```php
$subscriber = Make::subscribers()->find(1);

$subscriber->customFields;                  // ['EXTRA1' => 'server', 'EXTRA2' => 'database']
$subscriber->customField('EXTRA1');         // 'server'
$subscriber->customField('MISSING', 'n/a'); // 'n/a' (default)
$subscriber->toArray();                     // merges known + custom fields back into flat array
```

> **How custom field keys work:** When you create a custom field via `Make::customFields()->create(...)`, the
> API assigns it an opaque key like `EXTRA1`, `EXTRA2`, etc. This key is stored in the `value` property of the
> `CustomField` DTO. On subscriber objects, the custom field data uses these keys — not the human-readable label.
>
> To find the mapping between labels and keys, fetch the custom fields list:
>
> ```php
> $fields = Make::customFields()->all();
>
> // CustomField { id: 5, label: "Server", value: "EXTRA1", datatype: "string" }
> // CustomField { id: 6, label: "Database", value: "EXTRA2", datatype: "string" }
> ```
>
> Then use the `value` (e.g. `EXTRA1`) when reading or writing subscriber custom fields:
>
> ```php
> $subscriber->customField('EXTRA1'); // "server"
>
> Make::subscribers()->create(new Subscriber(
>     email: 'john@example.com',
>     customFields: ['EXTRA1' => 'my-server', 'EXTRA2' => 'my-database'],
> ));
> ```
>
> Subscribers may also contain system-level fields (e.g. `SAMTYKKEEPOST`, `PERSONVERNERKLRING`) that are
> managed by Make and typically appear as `null`. These are included in `customFields` alongside your own fields.

### `SubscriberList`

Properties: `id`, `title`, `dateCreated`, `dateUpdated`, `subscriberEmails`, `subscriberSms`.

### `CustomField`

Properties: `id`, `label`, `value`, `choices`, `datatype`.

- `label` — The human-readable name (e.g. "Server")
- `value` — The opaque key used on subscriber objects (e.g. "EXTRA1")
- `datatype` — The field type (e.g. "string", "dropdown")
- `choices` — Available options for dropdown fields

### `NewsletterSettings`

Properties: `segmentId`, `listIds`, `senderId`, `scheduledAt`.

### `NewsletterStats`

Properties: `id`, `sendingId`, `clicks`, `uniqueClicks`, `opens`, `bounce`, `unsubscribes`, `trackingMode`, `sent`, `createdAt`, `sentAt`, `openrate`, `mailbody`, `newsletterScreenshotUrl`, `delivered`, `subject`, `fromEmail`, `tags`, `lists`, `segment`.

---

## Error Handling

The SDK throws typed exceptions:

```php
use Pindena\MakeSDK\Exceptions\AuthenticationException;
use Pindena\MakeSDK\Exceptions\MakeSDKException;

try {
    Make::subscribers()->find(999);
} catch (AuthenticationException $e) {
    // Invalid credentials (401)
} catch (MakeSDKException $e) {
    // Any other API error
    $e->getCode();    // HTTP status code
    $e->getMessage(); // Error description
}
```

## Testing

```bash
composer test
```

## License

MIT
