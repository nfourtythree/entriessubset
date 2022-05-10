<?php

/**
 * Entris Subset Service
 *
 * @link      http://n43.me
 * @copyright Copyright (c) 2017 Nathaniel Hammond (nfourtythree)
 **/

namespace nfourtythree\entriessubset\services;

use Craft;

use craft\base\Component;
use craft\elements\User;
use craft\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

/**
 * Entries Subset Service
 *
 * @author Nathaniel Hammond (nfourtythree)
 * @package EntriesSubset
 * @since 1.0.0
 *
 * @property-read array $userGroups
 * @property-read array $entryTypes
 * @property-read array[]|array $entryTypeOptions
 * @property-read array $users
 */
class EntriesSubsetService extends Component
{
    /**
     * @return array
     */
    public function getEntryTypes(): array
    {
        $sections = Craft::$app->sections->getAllSections();
        $entryTypes = [];

        if (!empty($sections)) {
            foreach ($sections as $section) {
                $entryTypes[$section->handle] = Craft::$app->sections->getEntryTypesBySectionId($section->id);
            }
        }

        return $entryTypes;
    }

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function getEntryTypeOptions(): array
    {
        $sectionIds = Craft::$app->sections->getAllSectionIds();
        $entryTypes = [];

        foreach ($sectionIds as $id) {
            $entryTypes = [...$entryTypes, ...Craft::$app->sections->getEntryTypesBySectionId($id)];
        }

        $entryTypeOptions = ['*' => []];
        foreach ($entryTypes as $type) {
            if (!isset($entryTypeOptions[$type->getSection()->handle])) {
                $entryTypeOptions[$type->getSection()->handle] = [];
            }
            $entryTypeOptions[$type->getSection()->handle][] = ['label' => $type->name, 'value' => $type->uid];
        }

        return $entryTypeOptions;
    }

    /**
     * Get user groups
     *
     * @return array an array of user groups keyed by the handle
     */
    public function getUserGroups(): array
    {
        $userGroups = [];
        $allUserGroups = Craft::$app->userGroups->getAllGroups();

        if (count($allUserGroups)) {
            $userGroups = ArrayHelper::map($allUserGroups, 'id', 'name');
        }

        return $userGroups;
    }

    /**
     * Get Users
     * @return array Array of users keyed by their ID
     */
    public function getUsers(): array
    {
        $users = [];
        $allUsers = User::find()->all();

        if (count($allUsers)) {
            $users = ArrayHelper::index($allUsers, 'id');

            foreach ($users as $id => $user) {
                $users[$id] = implode(' - ', array_filter([$user->fullName, ($user->email != $user->username) ? $user->username : '', $user->email]));
            }
        }

        return $users;
    }
}
