<?php

namespace nfourtythree\entriessubset\migrations;

use Craft;
use craft\db\Migration;
use craft\db\Query;
use craft\db\Table;
use craft\helpers\Json;
use craft\services\Fields;
use nfourtythree\entriessubset\fields\EntriesSubsetField;

/**
 * m190906_102859_entry_type_id_to_uid migration.
 */
class m190906_102859_entry_type_id_to_uid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $fields = (new Query())
                    ->select(['id', 'uid', 'settings'])
                    ->from([Table::FIELDS])
                    ->where(['type' => EntriesSubsetField::class])
                    ->all();

        $entryTypeIds = [];

        foreach ($fields as $field) {
            if ($field['settings']) {
                $settings = Json::decodeIfJson($field['settings']) ?: [];
            } else {
                $settings = [];
            }

            if (!empty($settings['entryTypes'])) {
                $entryTypeIds = array_merge($entryTypeIds, $settings['entryTypes']);
            }
        }

        $entryTypes = (new Query())
                    ->select(['id', 'uid'])
                    ->from([Table::ENTRYTYPES])
                    ->where(['id' => $entryTypeIds])
                    ->pairs();

        $projectConfig = Craft::$app->getProjectConfig();
        $projectConfig->muteEvents = true;

        foreach ($fields as $field) {
            if ($field['settings']) {
                $settings = Json::decodeIfJson($field['settings']) ?: [];
            } else {
                $settings = [];
            }

            if (array_key_exists('entryTypes', $settings)) {
                foreach ($settings['entryTypes'] as $key => $entryTypeId) {
                    $settings['entryTypes'][$key] = $entryTypes[$entryTypeId] ?? null;
                }
            }

            $projectConfig->set(Fields::CONFIG_FIELDS_KEY.'.'.$field['uid'].'.settings', $settings);

            $this->update(Table::FIELDS, ['settings' => Json::encode($settings)], ['id' => $field['id']], [], false);
        }

        $projectConfig->muteEvents = false;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m190906_102859_entry_type_id_to_uid cannot be reverted.\n";
        return false;
    }
}
