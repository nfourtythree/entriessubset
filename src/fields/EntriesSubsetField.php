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

use Craft;
use craft\base\ElementInterface;

use craft\db\Table;
use craft\elements\Entry;
use craft\fields\Entries;
use craft\helpers\Db;
use nfourtythree\entriessubset\assetbundles\entriessubsetasset\EntriesSubsetFieldAsset;
use nfourtythree\entriessubset\Plugin;

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
    /**
     * @var array|null
     */
    public ?array $entryTypes = null;

    /**
     * @var array|null
     */
    public ?array $userGroups = null;

    /**
     * @var array|null
     */
    public ?array $users = null;

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
    public static function elementType(): string
    {
        return Entry::class;
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
     * @throws \yii\base\InvalidConfigException
     */
    public function getSettingsHtml(): ?string
    {
        Craft::$app->getView()->registerAssetBundle(EntriesSubsetFieldAsset::class);

        Craft::$app->getView()->registerJs("$.fn['EntriesSubset']();");

        $service = Plugin::getInstance()->getService();

        return parent::getSettingsHtml() . Craft::$app->getView()->renderTemplate('entriessubset/settings', [
            'settings' => $this->getSettings(),
            'entryTypesBySection' => $service->getEntryTypeOptions(),
            'userGroups' => $service->getUserGroups(),
            'users' => $service->getUsers(),
            'type' => self::displayName(),
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function inputTemplateVariables($value = null, ElementInterface $element = null): array
    {
        $vars = parent::inputTemplateVariables($value, $element);

        $settings = $this->getSettings();

        if (isset($settings['entryTypes']) && is_array($settings['entryTypes']) and !empty($settings['entryTypes'])) {
            foreach ($settings['entryTypes'] as $typeUid) {
                $typeId = Db::idByUid(Table::ENTRYTYPES, $typeUid);

                if (is_numeric($typeId)) {
                    $entryType = Craft::$app->sections->getEntryTypeById($typeId);

                    // Make sure there is a valid entry type
                    if ($entryType !== null) {
                        $vars['criteria']['type'][] = $entryType->handle;
                    }
                }
            }
        }

        if (isset($settings['users']) and count($settings['users'])) {
            foreach ($settings['users'] as $userId) {
                if (is_numeric($userId)) {
                    $vars['criteria']['authorId'][] = $userId;
                }
            }
        }

        if (isset($settings['userGroups']) and count($settings['userGroups'])) {
            foreach ($settings['userGroups'] as $userGroupId) {
                $vars['criteria']['authorGroupId'][] = $userGroupId;
            }
        }

        return $vars;
    }
}
