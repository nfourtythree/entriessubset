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
use craft\services\Sections;

/**
 * Entries Subset Service
 *
 * @author Nathaniel Hammond (nfourtythree)
 * @package EntriesSubset
 * @since 1.0.0
 */
class EntriesSubsetService extends Component
{
  public function getEntryTypes(): array
  {
    $sections = Craft::$app->sections->getAllSections();
    $entryTypes = [];

    if ( !empty( $sections ) ) {
      foreach ( $sections as $section ) {
        $entryTypes[ $section->handle ] = Craft::$app->sections->getEntryTypesBySectionId( $section->id );
      }
    }

    return $entryTypes;
  }

  public function getEntryTypeOptions()
  {
    $sectionIds = Craft::$app->sections->getAllSectionIds();
    $entryTypes = [];

    foreach ( $sectionIds as $id ) {
      $entryTypes = array_merge( $entryTypes, Craft::$app->sections->getEntryTypesBySectionId( $id ) );
    }

    $entryTypeOptions = [ '*' => [ ] ];
    foreach ( $entryTypes as $type ) {
      if ( !isset( $entryTypeOptions[ $type->section->handle ] ) ) {
        $entryTypeOptions[ $type->section->handle ] = [];
      }
      $entryTypeOptions[ $type->section->handle ][] = [ 'label' => $type->name, 'value' => $type->id ];
    }

    return $entryTypeOptions;
  }

}
