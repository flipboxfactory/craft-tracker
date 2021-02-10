<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\queries;

use craft\db\Query;
use craft\db\QueryAbortedException;
use craft\elements\Entry;
use flipbox\craft\ember\helpers\QueryHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait EntryAttributeTrait
{
    /**
     * The entry(s) that the resulting types must have.
     *
     * @var string|string[]|int|int[]|Entry|Entry[]|null $value
     */
    public $entry;

    /**
     * @param string|string[]|int|int[]|Entry|Entry[]|null $value
     * @return self The query object itself
     */
    public function setEntry($value)
    {
        $this->entry = $value;
        return $this;
    }

    /**
     * @param string|string[]|int|int[]|Entry|Entry[]|null $value
     * @return static The query object
     */
    public function entry($value)
    {
        return $this->setEntry($value);
    }

    /**
     * @param string|string[]|int|int[]|Entry|Entry[]|null $value
     * @return $this
     */
    public function setEntryId($value)
    {
        return $this->setEntry($value);
    }

    /**
     * @param string|string[]|int|int[]|Entry|Entry[]|null $value
     * @return self The query object itself
     */
    public function entryId($value)
    {
        return $this->setEntry($value);
    }

    /**
     * @param $value
     * @return int
     * @throws QueryAbortedException
     */
    protected function parseEntryValue($value)
    {
        $return = QueryHelper::prepareParam(
            $value,
            function (string $slug) {
                $value = (new Query())
                    ->select(['id'])
                    ->from(['{{%elements_sites}} elements_sites'])
                    ->where(['slug' => $slug])
                    ->scalar();
                return empty($value) ? false : $value;
            }
        );

        if ($return !== null && empty($return)) {
            throw new QueryAbortedException();
        }

        return $return;
    }
}
