<?php

namespace nfourtythree\entriessubset\migrations;

use Craft;
use craft\db\Migration;
use craft\db\Query;
use craft\db\Table;
use craft\helpers\Json;
/**
 * m190121_220906_id migration.
 */
class m190121_220906_id extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
      // If regards to the change from ID to UID in craft 3.1 we need to update
      // the field settings for each subset field
      // https://craftcms.com/guides/updating-plugins-for-craft-3-1#element-source-keys
      $fields = (new Query())
          ->select(['id', 'settings', 'type'])
          ->from([Table::FIELDS])
          ->where(['type'=>'nfourtythree\entriessubset\fields\EntriesSubsetField'])
          ->all();

      $sectionIds = [];

      foreach ($fields as $field) {
          if ($field['settings']) {
              $settings = Json::decodeIfJson($field['settings']) ?: [];
          } else {
              $settings = [];
          }

          if (!empty($settings['sources']) && is_array($settings['sources'])) {
              foreach ($settings['sources'] as $source) {
                  if (strpos($source, ':') !== false) {
                      list(, $sectionIds[]) = explode(':', $source);
                  }
              }
          }

      }

      $sections = (new Query())
          ->select(['id', 'uid'])
          ->from([Table::SECTIONS])
          ->where(['id' => $sectionIds])
          ->pairs();

      foreach ($fields as $field) {
          if ($field['settings']) {
              $settings = Json::decodeIfJson($field['settings']) ?: [];
          } else {
              $settings = [];
          }

          if (!empty($settings['sources']) && is_array($settings['sources'])) {
              $newSources = [];

              foreach ($settings['sources'] as $source) {
                  $source = explode(':', $source);
                  if (count($source) > 1) {
                      $newSources[] = $source[0] . ':' . ($sections[$source[1]] ?? $source[1]);
                  } else {
                      $newSources[] = $source[0];
                  }
              }

              $settings['sources'] = $newSources;
          }

          $settings = Json::encode($settings);

          $this->update(Table::FIELDS, ['settings' => $settings], ['id' => $field['id']], [], false);
      }

      return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m190121_220906_id cannot be reverted.\n";
        return false;
    }
}
