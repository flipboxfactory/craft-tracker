<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\queries;

use craft\db\QueryAbortedException;
use craft\helpers\Db;
use flipbox\craft\ember\queries\CacheableActiveQuery;
use flipbox\craft\ember\queries\ElementAttributeTrait;
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
    use ElementAttributeTrait,
        EntryAttributeTrait;

    /**
     * @var string|string[]|null
     */
    public $title;

    /**
     * @var string|string[]|null
     */
    public $event;

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
        $attributes = ['event', 'title'];

        foreach ($attributes as $attribute) {
            if (null !== ($value = $this->{$attribute})) {
                $this->andWhere(Db::parseParam($attribute, $value));
            }
        }

        $this->applyEntryParam();
        $this->applyElementParam();

        return parent::prepare($builder);
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
}
