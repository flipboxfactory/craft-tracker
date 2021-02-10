<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\queries;

use craft\db\Query;
use craft\db\QueryAbortedException;
use flipbox\craft\ember\queries\PopulateObjectTrait;
use flipbox\craft\tracker\objects\RollUp;
use flipbox\craft\tracker\records\Track;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class RollUpQuery extends Query
{
    use QueryTrait,
        PopulateObjectTrait;

    /**
     * @inheritdoc
     */
    public $orderBy = ['tracker.dateCreated' => SORT_ASC];

    /**
     * @inheritdoc
     */
    public $groupBy = ['event', 'entryId', 'elementId'];

    /**
     * @inheritdoc
     */
    public $select = ['COUNT(*) AS count', 'event', 'title', 'entryId', 'elementId'];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->from([
            Track::tableName() . ' ' . Track::tableAlias()
        ]);

        parent::init();
    }

    /**
     * @inheritdoc
     * @throws QueryAbortedException
     */
    public function prepare($builder)
    {
        $this->applyParams();
        return parent::prepare($builder);
    }

    /*******************************************
     * RESULTS
     *******************************************/

    /**
     * @inheritdoc
     * @return array|mixed|null
     * @throws \yii\base\InvalidConfigException
     */
    public function one($db = null)
    {
        if (null === ($config = parent::one($db))) {
            return null;
        }

        return $this->createObject($config);
    }

    /*******************************************
     * CREATE OBJECT
     *******************************************/

    /**
     * @param array $config
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    protected function createObject(array $config)
    {
        return new RollUp($config);
    }
}
