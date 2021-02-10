<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\queries;

use craft\db\QueryAbortedException;
use flipbox\craft\ember\queries\CacheableActiveQuery;
use flipbox\craft\tracker\records\Track;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @method Track one($db = null)
 * @method Track[] all($db = null)
 * @method Track[] getCachedResult($db = null)
 */
class TrackQuery extends CacheableActiveQuery
{
    use QueryTrait;

    /**
     * @inheritdoc
     */
    public $orderBy = ['tracker.dateCreated' => SORT_ASC];

    /**
     * @inheritdoc
     * @throws QueryAbortedException
     */
    public function prepare($builder)
    {
        $this->applyParams();
        return parent::prepare($builder);
    }
}
