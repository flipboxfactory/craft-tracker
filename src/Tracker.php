<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker;

use Craft;
use craft\base\Plugin;
use flipbox\craft\tracker\models\Settings as SettingsModel;
use flipbox\craft\ember\modules\LoggerTrait;
use craft\web\twig\variables\CraftVariable;
use flipbox\craft\tracker\web\twig\variables\Track as TrackVariable;
use yii\base\Event;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @method SettingsModel getSettings()
 *
 */
class Tracker extends Plugin
{
    use LoggerTrait;

    /**
     * @var string
     */
    public static $category = 'tracker';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // Twig variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('tracker', TrackVariable::class);
            }
        );
    }

    /**
     * @inheritdoc
     * @return SettingsModel
     */
    public function createSettingsModel()
    {
        return new SettingsModel();
    }

    /*******************************************
     * TRANSLATE
     *******************************************/

    /**
     * Translates a message to the specified language.
     *
     * This is a shortcut method of [[\Craft::t()]].
     *     *
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code (e.g. `en-US`, `en`). If this is null, the current
     * [[\yii\base\Application::language|application language]] will be used.
     * @return string the translated message.
     */
    public static function t($message, $params = [], $language = null)
    {
        return Craft::t('tracker', $message, $params, $language);
    }
}
