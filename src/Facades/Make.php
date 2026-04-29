<?php

namespace Pindena\MakeSDK\Facades;

use Illuminate\Support\Facades\Facade;
use Pindena\MakeSDK\MakeSDK;

/**
 * @method static \Pindena\MakeSDK\Resources\SubscriberResource subscribers()
 * @method static \Pindena\MakeSDK\Resources\SubscriberListResource subscriberlists()
 * @method static \Pindena\MakeSDK\Resources\CustomFieldResource customFields()
 * @method static \Pindena\MakeSDK\Resources\TagResource tags()
 * @method static \Pindena\MakeSDK\Resources\EventResource events()
 * @method static \Pindena\MakeSDK\Resources\NewsletterResource newsletters()
 * @method static \Pindena\MakeSDK\Resources\RecurringActionResource recurringActions()
 *
 * @see MakeSDK
 */
class Make extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MakeSDK::class;
    }
}
