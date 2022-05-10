<?php
/**
 * Entries Subset plugin for Craft CMS 3.x
 *
 * Craft field type plugin that extends the core Entries field type to give extra settings with ability to restrict by entry type
 *
 * @link      http://n43.me
 * @copyright Copyright (c) 2017 Nathaniel Hammond (nfourtythree)
 */

namespace nfourtythree\entriessubset\assetbundles\entriessubsetasset;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 *
 * @author    Nathaniel Hammond (nfourtythree)
 * @package   EntriesSubset
 * @since     1.0.0
 */
class EntriesSubsetFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init(): void
    {
        $this->sourcePath = "@nfourtythree/entriessubset/assetbundles/entriessubsetasset/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/entriessubset.js',
        ];

        parent::init();
    }
}
