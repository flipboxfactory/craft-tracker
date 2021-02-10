<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\objects;

/**
 * @property int|null $entryId
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait EntryAttributeTrait
{
    use EntryMutatorTrait;

    /**
     * @var int|null
     */
    private $entryId;

    /**
     * @inheritDoc
     */
    protected function internalSetEntryId(int $id = null)
    {
        $this->entryId = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function internalGetEntryId()
    {
        return $this->entryId === null ? null : (int)$this->entryId;
    }
}
