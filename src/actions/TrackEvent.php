<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\actions;

use Craft;
use flipbox\craft\ember\actions\records\CreateRecord;
use flipbox\craft\tracker\records\Track as TrackRecord;
use yii\db\ActiveRecord;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class TrackEvent extends CreateRecord
{
    /**
     * @inheritdoc
     */
    public $validBodyParams = [
        'event',
        'title',
        'elementId',
        'entryId',
        'metadata'
    ];

    /**
     * @inheritdoc
     * @return TrackRecord
     */
    protected function newRecord(array $config = []): ActiveRecord
    {
        $record = new TrackRecord();

        $record->setAttributes($config);

        // Add request data
        $record->userAgent = Craft::$app->getRequest()->getUserAgent();
        $record->remoteIp = Craft::$app->getRequest()->getRemoteIP();
        $record->clientOs = Craft::$app->getRequest()->getClientOs();

        return $record;
    }
}
