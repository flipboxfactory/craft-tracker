<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\objects;

use flipbox\craft\ember\objects\ElementAttributeTrait;
use yii\base\BaseObject;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class RollUp extends BaseObject
{
    use ElementAttributeTrait,
        EntryAttributeTrait;

    /**
     * @var int|null
     */
    public $count;

    /**
     * @var string|null
     */
    public $title;

    /**
     * @var string
     */
    public $event;

    /**
     * @var string
     */
    public $dateUpdated;

    /**
     * @var string
     */
    public $dateCreated;
}
