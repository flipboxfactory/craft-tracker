<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\queries;

use craft\db\QueryAbortedException;
use craft\helpers\Db;
use craft\records\Entry as EntryRecord;
use flipbox\craft\ember\queries\ElementAttributeTrait;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait QueryTrait
{
    use ElementAttributeTrait,
        EntryAttributeTrait,
        EntrySectionAttributeTrait,
        EntryAuthorAttributeTrait;


    abstract function andWhere($condition, $params = []);

    abstract function innerJoin($table, $on = '', $params = []);

    /**
     * @var string|string[]|null
     */
    public $title;

    /**
     * @var string|string[]|null
     */
    public $event;

    /**
     * The maximum Post Date that resulting entries can have.
     *
     * @var string|array|\DateTime
     */
    public $before;

    /**
     * The minimum Post Date that resulting entries can have.
     *
     * @var string|array|\DateTime
     */
    public $after;

    /**
     * Flag if the table is already joined (to prevent subsequent joins)
     *
     * @var bool
     */
    private $entriesTableJoined = false;

    /**
     * @param string|string[]|null $value
     * @return static The query object
     */
    public function setTitle($value)
    {
        $this->title = $value;
        return $this;
    }

    /**
     * @param string|string[]|null $value
     * @return static The query object
     */
    public function title($value)
    {
        return $this->setTitle($value);
    }

    /**
     * @param string|string[]|null $value
     * @return static The query object
     */
    public function setEvent($value)
    {
        $this->event = $value;
        return $this;
    }

    /**
     * @param string|string[]|null $value
     * @return static The query object
     */
    public function event($value)
    {
        return $this->setEvent($value);
    }


    public function before($value)
    {
        $this->before = $value;
        return $this;
    }

    public function after($value)
    {
        $this->after = $value;
        return $this;
    }

    /**
     * @throws QueryAbortedException
     */
    public function applyParams()
    {
        $attributes = ['event', 'title'];

        foreach ($attributes as $attribute) {
            if (null !== ($value = $this->{$attribute})) {
                $this->andWhere(Db::parseParam($attribute, $value));
            }
        }

        if ($this->before) {
            $this->andWhere(Db::parseDateParam('tracker.dateCreated', $this->before, '<'));
        }
        if ($this->after) {
            $this->andWhere(Db::parseDateParam('tracker.dateCreated', $this->after, '>='));
        }

        $this->applyEntryParam();
        $this->applyEntryAuthorParam();
        $this->applyEntrySectionParam();
        $this->applyElementParam();
    }

    /**
     * @return void
     * @throws QueryAbortedException
     */
    protected function applyEntryParam()
    {
        // Is the query already doomed?
        if ($this->entry !== null && empty($this->entry)) {
            throw new QueryAbortedException();
        }

        if (empty($this->entry)) {
            return;
        }

        $this->andWhere(
            Db::parseParam('entryId', $this->parseEntryValue($this->entry))
        );
    }

    /**
     * @return void
     * @throws QueryAbortedException
     */
    protected function applyEntryAuthorParam()
    {
        // Is the query already doomed?
        if ($this->entryAuthor !== null && empty($this->entryAuthor)) {
            throw new QueryAbortedException();
        }

        if (empty($this->entryAuthor)) {
            return;
        }

        $alias = $this->joinEntryTable();

        $this->andWhere(
            Db::parseParam($alias . '.authorId', $this->parseEntryAuthorValue($this->entryAuthor))
        );
    }

    /**
     * @return void
     * @throws QueryAbortedException
     */
    protected function applyEntrySectionParam()
    {
        // Is the query already doomed?
        if ($this->entrySection !== null && empty($this->entrySection)) {
            throw new QueryAbortedException();
        }

        if (empty($this->entrySection)) {
            return;
        }

        $alias = $this->joinEntryTable();

        $this->andWhere(
            Db::parseParam($alias . '.sectionId', $this->parseEntrySectionValue($this->entrySection))
        );
    }

    /**
     * @return void
     * @throws QueryAbortedException
     */
    protected function applyElementParam()
    {
        // Is the query already doomed?
        if ($this->element !== null && empty($this->element)) {
            throw new QueryAbortedException();
        }

        if (empty($this->element)) {
            return;
        }

        $this->andWhere(
            Db::parseParam('elementId', $this->parseElementValue($this->element))
        );
    }

    /************************************************************
     * JOIN TABLES
     ************************************************************/

    /**
     * @return string
     */
    protected function joinEntryTable(): string
    {
        $alias = "entries";

        if ($this->entriesTableJoined === false) {
            $this->innerJoin(
                EntryRecord::tableName() . ' ' . $alias,
                '[[' . $alias . '.id]] = [[entryId]]'
            );

            $this->entriesTableJoined = true;
        }

        return $alias;
    }
}
