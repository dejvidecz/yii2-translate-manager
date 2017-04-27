<?php

namespace dlds\translatemanager\bundles;

use yii\web\AssetBundle;

/**
 * FrontendTranslation Plugin asset bundle
 *
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.2
 */
class FrontendTranslationPluginAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@dlds/translatemanager/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'javascripts/helpers.js',
        'javascripts/frontend-translation.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'dlds\translatemanager\bundles\TranslationPluginAsset',
    ];
}
