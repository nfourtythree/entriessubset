<?php

namespace nfourtythree\entriessubset\utilities;

use markhuot\CraftQL\Events\GetFieldSchema;
use markhuot\CraftQL\Types\EntryInterface;

/**
 * Class CraftQLListener
 * @package EntriesSubset
 */
class CraftQLListener {
  /**
   * @param GetFieldSchema $event
   */
  public static function onCraftQlGetFieldSchema(GetFieldSchema $event) {
    $request = $event->schema->request;
    $field = $event->sender;

    $event->handled = true;

    // Borrowed from \markhuot\CraftQL\Listeners\GetEntriesFieldSchema.
    $event->schema->addField($field)
      ->type(EntryInterface::class)
      ->lists()
      ->resolve(function ($root, $args, $context, $info) use ($field, $request) {
        return $request->entries($root->{$field->handle}, $root, $args, $context, $info)->all();
      });
  }
}
