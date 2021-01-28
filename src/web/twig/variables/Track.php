<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\web\twig\variables;

use flipbox\craft\ember\helpers\QueryHelper;
use flipbox\craft\tracker\models\Settings;
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
     * Plugins settings which are accessed via 'craft.tracker.settings'
     *
     * @return Settings
     */
    public function getSettings()
    {
        return Tracker::getInstance()->getSettings();
    }
}
