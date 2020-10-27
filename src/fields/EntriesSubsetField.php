<?php
/**
 * Entries Subset plugin for Craft CMS 3.x
 *
 * Craft field type plugin that extends the core Entries field type to give extra settings with ability to restrict by entry type
 *
 * @link      http://n43.me
 * @copyright Copyright (c) 2017 Nathaniel Hammond (nfourtythree)
 */

namespace nfourtythree\entriessubset\fields;

use nfourtythree\entriessubset\EntriesSubset;
use nfourtythree\entriessubset\assetbundles\entriessubsetasset\EntriesSubsetFieldAsset;
use nfourtythree\entriessubset\services\EntriesSubsetService;

use Craft;
use craft\base\ElementInterface;
use craft\db\Table;
use craft\elements\Entry;
use craft\fields\BaseRelationField;
use craft\helpers\Db;
use craft\helpers\Json;
use craft\fields\Entries;

/**
 *  Field
 *
 * Whenever someone creates a new field in Craft, they must specify what
 * type of field it is. The system comes with a handful of field types baked in,
 * and we’ve made it extremely easy for plugins to add new ones.
 *
 * https://craftcms.com/docs/plugins/field-types
 *
 * @author    Nathaniel Hammond (nfourtythree)
 * @package   EntriesSubset
 * @since     1.0.0
 */
class EntriesSubsetField extends Entries
{
    // Public Properties
    // =========================================================================

    // Storage for allowable entry types
    public $entryTypes;
    public $userGroups;
    public $users;

    // Static Methods
    // =========================================================================

    /**
     * Returns the display name of this class.
     *
     * @return string The display name of this class.
     */
    public static function displayName(): string
    {
        return Craft::t('entriessubset', 'Entries Subset');
    }

    /**
     * @inheritdoc
     */
    protected static function elementType(): string
    {
        return Entry::class;
    }

    /**
     * @inheritdoc
     */
    public static function defaultSelectionLabel(): string
    {
        return Craft::t('app', 'Add an entry');
    }

    /**
     * @inheritdoc
     */
    public function settingsAttributes(): array
    {
        $attributes = parent::settingsAttributes();
        $attributes[] = 'entryTypes';
        $attributes[] = 'userGroups';
        $attributes[] = 'users';

        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
      Craft::$app->getView()->registerAssetBundle( EntriesSubsetFieldAsset::class );

      Craft::$app->getView()->registerJs( "$.fn['EntriesSubset']();" );

      $parentHtml = parent::getSettingsHtml();

      $plugin = EntriesSubset::getInstance();

      return $parentHtml . Craft::$app->getView()->renderTemplate( 'entriessubset/settings', [
            'settings' => $this->getSettings(),
            'entryTypesBySection' => $plugin->service->getEntryTypeOptions(),
            'userGroups' => $plugin->service->getUserGroups(),
            'users' => $plugin->service->getUsers(),
            'type' => $this->displayName(),
        ] );


    }

    /**
     * @inheritdoc
     */
    protected function inputTemplateVariables( $value = null, ElementInterface $element = null ): array
    {
      $vars = parent::inputTemplateVariables( $value, $element );

      $settings = $this->getSettings();

      if ( isset( $settings[ 'entryTypes' ] ) ) {
        $entryTypes = $settings[ 'entryTypes' ];

        if ($entryTypes and is_array( $entryTypes ) and !empty( $entryTypes ) ) {
          foreach( $entryTypes as $typeUid ) {
            $typeId = Db::idByUid(Table::ENTRYTYPES, $typeUid);

            if ( is_numeric( $typeId ) ) {
              $entryType = Craft::$app->sections->getEntryTypeById( $typeId );

              // Make sure there is a valid entry type
              if ( $entryType !== null ) {
                $vars[ 'criteria' ][ 'type' ][] = $entryType->handle;
              }
            }
          }
        }
      }

      if ( isset( $settings[ 'users' ] ) and count( $settings[ 'users' ] ) ) {
        foreach ( $settings[ 'users' ] as $userId ) {
          if ( is_numeric( $userId ) ) {
            $vars[ 'criteria' ][ 'authorId' ][] = $userId;
          }
        }
      }

      if ( isset( $settings[ 'userGroups' ] ) and count( $settings[ 'userGroups' ] ) ) {
        foreach ( $settings[ 'userGroups' ] as $userGroupId ) {
          $vars[ 'criteria' ][ 'authorGroupId' ][] = $userGroupId;
        }
      }

      return $vars;
    }
}
