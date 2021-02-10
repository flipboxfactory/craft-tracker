<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\queries;

use craft\db\Query;
use craft\db\QueryAbortedException;
use craft\elements\User as UserElement;
use craft\records\User as UserRecord;
use flipbox\craft\ember\helpers\QueryHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait EntryAuthorAttributeTrait
{
    /**
     * The entry(s) that the resulting types must have.
     *
     * @var string|string[]|int|int[]|UserElement|UserElement[]|null $value
     */
    public $entryAuthor;

    /**
     * @param string|string[]|int|int[]|UserElement|UserElement[]|null $value
     * @return self The query object itself
     */
    public function setEntryAuthor($value)
    {
        $this->entryAuthor = $value;
        return $this;
    }

    /**
     * @param string|string[]|int|int[]|UserElement|UserElement[]|null $value
     * @return self The query object itself
     */
    public function setAuthor($value)
    {
        $this->entryAuthor = $value;
        return $this;
    }

    /**
     * @param string|string[]|int|int[]|UserElement|UserElement[]|null $value
     * @return static The query object
     */
    public function entryAuthor($value)
    {
        return $this->setEntryAuthor($value);
    }

    /**
     * @param string|string[]|int|int[]|UserElement|UserElement[]|null $value
     * @return static The query object
     */
    public function author($value)
    {
        return $this->setEntryAuthor($value);
    }

    /**
     * @param string|string[]|int|int[]|UserElement|UserElement[]|null $value
     * @return $this
     */
    public function setEntryAuthorId($value)
    {
        return $this->setEntryAuthor($value);
    }

    /**
     * @param string|string[]|int|int[]|UserElement|UserElement[]|null $value
     * @return $this
     */
    public function setAuthorId($value)
    {
        return $this->setEntryAuthor($value);
    }

    /**
     * @param string|string[]|int|int[]|UserElement|UserElement[]|null $value
     * @return self The query object itself
     */
    public function entryAuthorId($value)
    {
        return $this->setEntryAuthor($value);
    }

    /**
     * @param string|string[]|int|int[]|UserElement|UserElement[]|null $value
     * @return self The query object itself
     */
    public function authorId($value)
    {
        return $this->setEntryAuthor($value);
    }

    /**
     * @param $value
     * @return int
     * @throws QueryAbortedException
     */
    protected function parseEntryAuthorValue($value)
    {
        $return = QueryHelper::prepareParam(
            $value,
            function (string $identifier) {
                $value = (new Query())
                    ->select(['id'])
                    ->from([UserRecord::tableName()])
                    ->where(['email' => $identifier])
                    ->orWhere(['username' => $identifier])
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
