<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\web\twig\variables;

use Craft;
use craft\helpers\Json;
use flipbox\craft\ember\helpers\QueryHelper;
use flipbox\craft\tracker\models\Settings;
use flipbox\craft\tracker\queries\RollUpQuery;
use flipbox\craft\tracker\queries\TrackQuery;
use flipbox\craft\tracker\records\Track as TrackRecord;
use flipbox\craft\tracker\Tracker;
use yii\di\ServiceLocator;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Track extends ServiceLocator
{
    /**
     * Query tracker records via 'craft.tracker.query'
     *
     * @param array $config
     * @return TrackQuery
     */
    public function getQuery(array $config = []): TrackQuery
    {
        $query = TrackRecord::find();

        QueryHelper::configure(
            $query,
            $config
        );

        return $query;
    }

    /**
     * Query tracker records via 'craft.tracker.rollUp'
     *
     * @param array $config
     * @return RollUpQuery
     */
    public function getRollUp(array $config = []): RollUpQuery
    {
        $query = new RollUpQuery();

        QueryHelper::configure(
            $query,
            $config
        );

        return $query;
    }

    /**
     * Track an event via 'craft.tracker.track'
     *
     * @param array $config
     * @return TrackQuery
     */
    public function track(array $config = []): ?TrackRecord
    {
        $record = new TrackRecord();

        $record->setAttributes($config);

        // Add request data
        $record->userAgent = Craft::$app->getRequest()->getUserAgent();
        $record->remoteIp = Craft::$app->getRequest()->getRemoteIP();
        $record->clientOs = Craft::$app->getRequest()->getClientOs();

        try {
            if (!$record->save()) {
                Tracker::error(
                    sprintf(
                        "Failed to save track call due to the following errors: %s",
                        Json::encode($record->errors()
                        )
                    )
                );

                return null;
            }
        } catch (\Exception $error) {
            Tracker::error(
                sprintf(
                    "An exception was caught while to record a track event: %s",
                    $error->getMessage()
                )
            );
        }

        return $record;
    }

    /**
     * Plugins settings which are accessed via 'craft.tracker.settings'
     *
     * @return Settings
     */
    public function getSettings()
    {
        return Tracker::getInstance()->getSettings();
    }
}
