<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\migrations;

use craft\db\Migration;
use craft\records\Element as ElementRecord;
use craft\records\Entry as EntryRecord;
use flipbox\craft\tracker\records\Track as TrackRecord;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTables();
        $this->createIndexes();
        $this->addForeignKeys();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTableIfExists(TrackRecord::tableName());

        return true;
    }

    /**
     * Creates the tables.
     *
     * @return void
     */
    protected function createTables()
    {
        $this->createTable(TrackRecord::tableName(), [
            'id' => $this->primaryKey(),
            'entryId' => $this->integer()->notNull(),
            'event' => $this->string()->notNull(),
            'title' => $this->string(),
            'elementId' => $this->integer(),
            'metadata' => $this->text(),
            'remoteIp' => $this->string(45),
            'userAgent' => $this->string(255),
            'clientOs' => $this->string(45),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid()
        ]);
    }

    /**
     * Creates the indexes.
     *
     * @return void
     */
    protected function createIndexes()
    {
        $this->createIndex(
            $this->db->getIndexName(
                TrackRecord::tableName(),
                'entryId',
                false,
                true
            ),
            TrackRecord::tableName(),
            'entryId',
            false
        );
        $this->createIndex(
            $this->db->getIndexName(
                TrackRecord::tableName(),
                'elementId',
                false,
                true
            ),
            TrackRecord::tableName(),
            'elementId',
            false
        );
        $this->createIndex(
            $this->db->getIndexName(
                TrackRecord::tableName(),
                'event',
                false,
                true
            ),
            TrackRecord::tableName(),
            'event',
            false
        );
    }

    /**
     * Adds the foreign keys.
     *
     * @return void
     */
    protected function addForeignKeys()
    {

        $this->addForeignKey(
            $this->db->getForeignKeyName(
                TrackRecord::tableName(),
                'entryId'
            ),
            TrackRecord::tableName(),
            'entryId',
            EntryRecord::tableName(),
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(
                TrackRecord::tableName(),
                'elementId'
            ),
            TrackRecord::tableName(),
            'elementId',
            ElementRecord::tableName(),
            'id',
            'CASCADE',
            'CASCADE'
        );
    }
}
