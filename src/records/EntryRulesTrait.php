<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\records;

use craft\elements\Entry;
use yii\base\Model;

/**
 * @property int|null $entryId
 * @property Entry|null $entry
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait EntryRulesTrait
{
    /**
     * @return array
     */
    protected function entryRules(): array
    {
        return [
            [
                [
                    'entryId'
                ],
                'number',
                'integerOnly' => true
            ],
            [
                [
                    'entryId',
                    'entry'
                ],
                'safe',
                'on' => [
                    Model::SCENARIO_DEFAULT
                ]
            ]
        ];
    }
}
