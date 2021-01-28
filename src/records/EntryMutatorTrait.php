<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\records;

use Craft;
use craft\elements\Entry;

/** *
 * @property Entry|null $entry
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
trait EntryMutatorTrait
{
    /**
     * @var Entry|null
     */
    private $entry;

    /**
     * Internally set the Entry Id.  This can be overridden. A record for example
     * should use `setAttribute`.
     *
     * @param int|null $id
     * @return $this
     */
    abstract protected function internalSetEntryId(int $id = null);

    /**
     * Internally get the Entry Id.  This can be overridden.  A record for example
     * should use `getAttribute`.
     *
     * @return int|null
     */
    abstract protected function internalGetEntryId();

    /**
     * @return bool
     */
    public function isEntrySet(): bool
    {
        return null !== $this->entry;
    }

    /**
     * Set associated entryId
     *
     * @param $id
     * @return $this
     */
    public function setEntryId(int $id = null)
    {
        $this->internalSetEntryId($id);

        if (null !== $this->entry && $id != $this->entry->id) {
            $this->entry = null;
        }

        return $this;
    }

    /**
     * Get associated entryId
     *
     * @return int|null
     */
    public function getEntryId()
    {
        if (null === $this->internalGetEntryId() && null !== $this->entry) {
            $this->setEntryId($this->entry->id);
        }

        return $this->internalGetEntryId();
    }

    /**
     * Associate a entry
     *
     * @param mixed $entry
     * @return $this
     */
    public function setEntry($entry = null)
    {
        $this->entry = null;
        $this->internalSetEntryId(null);

        if (null !== ($entry = $this->verifyEntry($entry))) {
            /** @var Entry entry */
            $this->entry = $entry;
            $this->internalSetEntryId($entry->id);
        }

        return $this;
    }

    /**
     * @return Entry|null
     */
    public function getEntry()
    {
        if ($this->entry === null) {
            $entry = $this->resolveEntry();
            $this->setEntry($entry);
            return $entry;
        }

        $entryId = $this->internalGetEntryId();
        if ($entryId !== null && $entryId != $this->entry->id) {
            $this->entry = null;
            return $this->getEntry();
        }

        return $this->entry;
    }

    /**
     * @return Entry|null
     */
    protected function resolveEntry()
    {
        if ($entry = $this->resolveEntryFromId()) {
            return $entry;
        }

        return null;
    }

    /**
     * @return Entry|null
     */
    private function resolveEntryFromId()
    {
        if (null === ($entryId = $this->internalGetEntryId())) {
            return null;
        }

        return Craft::$app->getEntries()->getEntryById($entryId);
    }

    /**
     * @param mixed $entry
     * @return Entry|null
     */
    protected function verifyEntry($entry = null)
    {
        if (null === $entry) {
            return null;
        }

        if ($entry instanceof Entry) {
            return $entry;
        }

        if (is_numeric($entry)) {
            return Craft::$app->getEntries()->getEntryById($entry);
        }

        if (is_string($entry)) {
            $element = Craft::$app->getElements()->getElementByUri($entry);
            if ($element instanceof  Entry) {
                return $element;
            }
        }

        return null;
    }
}
