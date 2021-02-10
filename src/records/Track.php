<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\records;

use Craft;
use flipbox\craft\ember\records\ActiveRecord;
use flipbox\craft\ember\records\ElementAttributeTrait;
use flipbox\craft\tracker\queries\TrackQuery;


/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.1.0
 *
 * @property string $event An event name; such as `Click::Element123`
 * @property string|null $title A descriptive reference to the event
 * @property string|null $remoteIp Where the event originated
 * @property string|null $userAgent The browser info
 * @property string|null $clientOs The users operating system
 * @property string|null $metadata Additional data; stored as a JSON string
 *
 */
class Track extends ActiveRecord
{
    use ElementAttributeTrait,
        EntryAttributeTrait;

    const DEFAULT_EVENT = "View";

    /**
     * The table alias
     */
    const TABLE_ALIAS = 'tracker';

    /**
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @inheritdoc
     * @return TrackQuery
     */
    public static function find()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Craft::createObject(TrackQuery::class, [get_called_class()]);
    }

    /*******************************************
     * EVENTS
     *******************************************/

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        // If the default event is used, set the element as well
        if ($this->event === self::DEFAULT_EVENT && !$this->getElement()) {
            $this->setElement($this->getEntry());
        }

        // Auto set title (if not provided)
        if (!$this->title && $this->getElement()) {
            $this->title = $this->getElement()->title;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            $this->entryRules(),
            $this->elementRules(),
            [
                [
                    [
                        'event'
                    ],
                    'default',
                    'value' => self::DEFAULT_EVENT
                ],
                [
                    [
                        'entryId',
                        'event',
                    ],
                    'required'
                ],
                [
                    [
                        'entryId',
                        'elementId',
                        'event',
                        'title',
                        'remoteIp',
                        'userAgent',
                        'clientOs',
                        'metadata',
                    ],
                    'safe',
                    'on' => [
                        self::SCENARIO_DEFAULT
                    ]
                ]
            ]
        );
    }
}
