<?php

namespace nfourtythree\entriessubset;

use craft\base\Plugin as BasePlugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use nfourtythree\entriessubset\fields\EntriesSubsetField;
use nfourtythree\entriessubset\services\EntriesSubsetService;
use yii\base\Event;
use yii\base\InvalidConfigException;

/**
 * Entries Subset plugin for Craft CMS 4.x
 *
 * Craft field type plugin that extends the core Entries field type to give extra settings with ability to restrict by entry type
 *
 * @link      http://n43.me
 * @copyright Copyright (c) 2017 Nathaniel Hammond (nfourtythree)
 * @author    Nathaniel Hammond (nfourtythree)
 * @package   EntriesSubset
 * @since     1.0.0
 *
 * @property-read EntriesSubsetService $service
 */
class Plugin extends BasePlugin
{
    /**
     * @inheritdoc
     */
    public string $minVersionRequired = '1.2.3';

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        $this->setComponents([
          'service' => EntriesSubsetService::class,
        ]);

        // Register our fields
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            static function(RegisterComponentTypesEvent $event) {
                $event->types[] = EntriesSubsetField::class;
            }
        );
    }

    /**
     * Returns the Entries Subset service
     *
     * @return EntriesSubsetService The customers service
     * @throws InvalidConfigException
     */
    public function getService(): EntriesSubsetService
    {
        return $this->get('service');
    }
}
