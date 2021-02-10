<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\queries;

use craft\db\Query;
use craft\db\QueryAbortedException;
use craft\records\Section;
use flipbox\craft\ember\helpers\QueryHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait EntrySectionAttributeTrait
{
    /**
     * The entry(s) that the resulting types must have.
     *
     * @var string|string[]|int|int[]|null $value
     */
    public $entrySection;

    /**
     * @param string|string[]|int|int[]|null $value
     * @return self The query object itself
     */
    public function setEntrySection($value)
    {
        $this->entrySection = $value;
        return $this;
    }

    /**
     * @param string|string[]|int|int[]|null $value
     * @return self The query object itself
     */
    public function setSection($value)
    {
        $this->entrySection = $value;
        return $this;
    }

    /**
     * @param string|string[]|int|int[]|null $value
     * @return static The query object
     */
    public function entrySection($value)
    {
        return $this->setEntrySection($value);
    }

    /**
     * @param string|string[]|int|int[]|null $value
     * @return static The query object
     */
    public function section($value)
    {
        return $this->setEntrySection($value);
    }

    /**
     * @param string|string[]|int|int[]|null $value
     * @return $this
     */
    public function setEntrySectionId($value)
    {
        return $this->setEntrySection($value);
    }

    /**
     * @param string|string[]|int|int[]|null $value
     * @return $this
     */
    public function setSectionId($value)
    {
        return $this->setEntrySection($value);
    }

    /**
     * @param string|string[]|int|int[]|null $value
     * @return self The query object itself
     */
    public function entrySectionId($value)
    {
        return $this->setEntrySection($value);
    }

    /**
     * @param string|string[]|int|int[]|null $value
     * @return self The query object itself
     */
    public function sectionId($value)
    {
        return $this->setEntrySection($value);
    }

    /**
     * @param $value
     * @return int
     * @throws QueryAbortedException
     */
    protected function parseEntrySectionValue($value)
    {
        $return = QueryHelper::prepareParam(
            $value,
            function (string $identifier) {
                $value = (new Query())
                    ->select(['id'])
                    ->from([Section::tableName()])
                    ->where(['handle' => $identifier])
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
