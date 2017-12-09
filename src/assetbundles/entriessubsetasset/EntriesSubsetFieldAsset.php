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

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * FieldAsset AssetBundle
 *
 * AssetBundle represents a collection of asset files, such as CSS, JS, images.
 *
 * Each asset bundle has a unique name that globally identifies it among all asset bundles used in an application.
 * The name is the [fully qualified class name](http://php.net/manual/en/language.namespaces.rules.php)
 * of the class representing it.
 *
 * An asset bundle can depend on other asset bundles. When registering an asset bundle
 * with a view, all its dependent asset bundles will be automatically registered.
 *
 * http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
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
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = "@nfourtythree/entriessubset/assetbundles/entriessubsetasset/dist";

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'js/entriessubset.js',
        ];

        // $this->css = [
        //     'css/.css',
        // ];

        parent::init();
    }
}
