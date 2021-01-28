<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\records;

use flipbox\craft\ember\records\ActiveRecordTrait;
use flipbox\craft\tracker\Tracker;
use craft\elements\Entry as EntryElement;
use craft\records\Entry as EntryRecord;
use yii\db\ActiveQueryInterface;

/**
 *
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait EntryAttributeTrait
{
    use ActiveRecordTrait,
        EntryRulesTrait,
        EntryMutatorTrait;

    /**
     * @inheritdoc
     */
    public function entryAttributes(): array
    {
        return [
            'entryId'
        ];
    }

    /**
     * @inheritdoc
     */
    public function entryAttributeLabels(): array
    {
        return [
            'entryId' => Tracker::t('Entry Id')
        ];
    }

    /**
     * @inheritDoc
     */
    protected function internalSetEntryId(int $id = null)
    {
        $this->setAttribute('entryId', $id);
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function internalGetEntryId()
    {
        if (null === ($id = $this->getAttribute('entryId'))) {
            return null;
        }
        return (int) $id;
    }

    /**
     * @return EntryElement|null
     */
    protected function resolveEntry()
    {
        if ($model = $this->resolveEntryFromRelation()) {
            return $model;
        }

        return $this->resolveEntryFromId();
    }

    /**
     * @return EntryElement|null
     */
    private function resolveEntryFromRelation()
    {
        if (false === $this->isRelationPopulated('entryRecord')) {
            return null;
        }

        if (null === ($record = $this->getRelation('entryRecord'))) {
            return null;
        }

        /** @var EntryRecord $record */

        return EntryElement::findOne($record->id);
    }

    /**
     * Returns the associated entry record.
     *
     * @return ActiveQueryInterface
     */
    protected function getEntryRecord(): ActiveQueryInterface
    {
        return $this->hasOne(
            EntryRecord::class,
            ['id' => 'entryId']
        );
    }
}
