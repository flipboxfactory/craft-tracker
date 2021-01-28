<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\controllers;

use craft\helpers\ArrayHelper;
use flipbox\craft\ember\filters\FlashMessageFilter;
use flipbox\craft\ember\filters\ModelErrorFilter;
use flipbox\craft\ember\filters\RedirectFilter;
use flipbox\craft\ember\controllers\AbstractController;
use flipbox\craft\tracker\actions\TrackEvent;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class TrackController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'redirect' => [
                    'class' => RedirectFilter::class,
                    'actions' => [
                        'event' => '/'
                    ]
                ],
                'error' => [
                    'class' => ModelErrorFilter::class
                ],
                'flash' => [
                    'class' => FlashMessageFilter::class
                ],
                'transform' => [
                    'class' => TransformFilter::class,
                    'actions' => [
                        'event'
                    ]
                ],
            ]
        );
    }

    /**
     * @return array
     */
    protected function verbs(): array
    {
        return [
            'event' => ['post'],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return ArrayHelper::merge(
            parent::actions(),
            [
                'event' => [
                    'class' => TrackEvent::class
                ],
            ]
        );
    }
}