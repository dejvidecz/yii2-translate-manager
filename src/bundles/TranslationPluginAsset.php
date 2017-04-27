<?php

namespace dlds\translatemanager\bundles;

use yii\web\AssetBundle;

/**
 * Translation Plugin asset bundle
 *
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */
class TranslationPluginAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@dlds/translatemanager/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'javascripts/md5.js',
        'javascripts/lajax.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'dlds\translatemanager\bundles\LanguageItemPluginAsset',
    ];
}
