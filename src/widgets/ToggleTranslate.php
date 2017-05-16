<?php

namespace dlds\translatemanager\widgets;

use Yii;
use yii\base\Widget;
use dlds\translatemanager\Module;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Widget that displays button for switching to translating mode.
 *
 * Simple example:
 *
 * ~~~
 * \dlds\translatemanager\widgets\ToggleTranslate::widget();
 * ~~~
 *
 * Example for changing position:
 *
 * ~~~
 * \dlds\translatemanager\widgets\ToggleTranslate::widget([
 *  'position' => \dlds\translatemanager\widgets\ToggleTranslate::POSITION_TOP_RIGHT,
 * ]);
 * ~~~
 *
 * Example for changing skin:
 *
 * ~~~
 * \dlds\translatemanager\widgets\ToggleTranslate::widget([
 *  'frontendTranslationAsset' => 'dlds\translatemanager\bundles\FrontendTranslationAsset',
 * ]);
 * ~~~
 *
 * Example for changing template and skin:
 *
 * ~~~
 * \dlds\translatemanager\widgets\ToggleTranslate::widget([
 *  'template' => '<a href="javascript:void(0);" id="toggle-translate" class="{position}" data-language="{language}" data-url="{url}"><i></i> {text}</a><div id="translate-manager-div"></div>',
 *  'frontendTranslationAsset' => 'dlds\translatemanager\bundles\FrontendTranslationAsset',
 * ]);
 * ~~~
 *
 * @author Lajos Molnar <lajax.m@gmail.com>
 *
 * @since 1.0
 */
class ToggleTranslate extends Widget
{
    /**
     * Url of the dialog window.
     */
    const DIALOG_URL = '/translatemanager/language/dialog';

    /**
     * Button in top left corner.
     */
    const POSITION_TOP_LEFT = 'top-left';

    /**
     * Button in top right corner.
     */
    const POSITION_TOP_RIGHT = 'top-right';

    /**
     * Button in bottom left corner.
     */
    const POSITION_BOTTOM_LEFT = 'bottom-left';

    /**
     * Button in bottom right corner.
     */
    const POSITION_BOTTOM_RIGHT = 'bottom-right';

    /**
     * @var string The position of the translate mode switch button relative to the screen.
     * Pre-defined positions: bottom-left (default), bottom-right, top-left, top-tright.
     */
    public $position = 'bottom-left';

    /**
     * @var string The template of the translate mode switch button.
     */
    public $template = '<a href="javascript:void(0);" id="toggle-translate" data-language="{language}" data-url="{url}"><i></i> {text}</a><div id="translate-manager-div"></div>';

    /**
     * example: http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
     *
     * @var string added StyleSheets and their dependencies
     */
    public $frontendTranslationAsset = 'dlds\translatemanager\bundles\FrontendTranslationAsset';

    /**
     * example: http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
     *
     * @var string added JavaScripts and their dependencies
     */
    public $frontendTranslationPluginAsset = 'dlds\translatemanager\bundles\FrontendTranslationPluginAsset';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->_registerAssets();

        $isEnabled = Yii::$app->session->has(Module::SESSION_KEY_ENABLE_TRANSLATE);

        $url = Url::current([Module::QP_SWITCH_TRANSLATE => !$isEnabled]);

        $html = Html::beginTag('div', ['id' => 'toggle-translate-wrapper', 'class' => $this->position]);

        $html .= Html::beginTag('div', ['class' => 'tm-title']);

        $html .= Html::a(\Yii::t('language', 'TM'), Url::to('/translatemanager/language/list/'), ['class' => 'title']);

        $html .= Html::endTag('div');

        $html .= Html::a(($isEnabled) ? \Yii::t('yii', 'ON') : \Yii::t('yii', 'OFF'), $url, ['class' => sprintf('switch %s', ($isEnabled) ? 'switch-enabled' : 'switch-disabled')]);

        if ($isEnabled) {

            $html .= strtr($this->template, [
                '{text}' => Yii::t('language', 'SHOW'),
                '{language}' => Yii::$app->language,
                '{url}' => Yii::$app->urlManager->createUrl(self::DIALOG_URL),
            ]);
        }

        $html .= Html::endTag('div');

        return $html;
    }

    /**
     * Registering asset files
     */
    private function _registerAssets()
    {
        if ($this->frontendTranslationAsset) {
            Yii::$app->view->registerAssetBundle($this->frontendTranslationAsset);
        }

        if ($this->frontendTranslationPluginAsset) {
            Yii::$app->view->registerAssetBundle($this->frontendTranslationPluginAsset);
        }
    }
}
