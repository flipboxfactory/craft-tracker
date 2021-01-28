<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/tracker/license
 * @link       https://www.flipboxfactory.com/software/tracker/
 */

namespace flipbox\craft\tracker\controllers;

use Craft;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\base\Model;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class TransformFilter extends ActionFilter
{
    /**
     * The default data transformer.  If a transformer cannot be resolved via an action mapping,
     * this transformer will be used.
     *
     * @var string|callable
     */
    public $transformer;

    /**
     * @var array this property defines the transformers for each action.
     * Each action that should only support one transformer.
     *
     * You can use `'*'` to stand for all actions. When an action is explicitly
     * specified, it takes precedence over the specification given by `'*'`.
     *
     * For example,
     *
     * ```php
     * [
     *   'create' => SomeClass::class,
     *   'update' => 'transformerHandle',
     *   'delete' => function() { return ['foo' => 'bar'] },
     *   '*' => SomeOtherClass::class,
     * ]
     * ```
     */
    public $actions = [];

    /**
     * Indicating whether to transform empty data
     *
     * @var bool
     */
    public $transformEmpty = false;

    /**
     * @param Action $action
     * @param mixed $result
     * @return array|mixed|null
     */
    public function afterAction($action, $result)
    {
        if (!$this->shouldTransform($action, $result)) {
            return $result;
        }
        return $this->transform($result);
    }

    /*******************************************
     * TRANSFORM
     *******************************************/

    /**
     * @param $data
     * @return array|null
     */
    protected function transform($data)
    {
        if ($data instanceof Model && $data->hasErrors()) {
            return $this->transformModelErrors($data);
        } elseif (!is_array($data)) {
            return $this->transformData($data);
        }
        return $data;
    }

    /**
     * Serializes the validation errors in a model.
     * @param Model $model
     * @return array the array representation of the errors
     */
    protected function transformModelErrors(Model $model): array
    {
        if (Craft::$app->getResponse()->getIsSuccessful()) {
            Craft::$app->getResponse()->setStatusCode(400, 'Errors');
        }

        return $model->getErrors();
    }

    /**
     * @param $data
     * @return array|null
     */
    protected function transformData($data)
    {
        if (Craft::$app->getRequest()->getIsHead()) {
            return null;
        }

        if ($data instanceof Model) {
            return $data->getAttributes();
        }

        return null;
    }

    /*******************************************
     * ACTION UTILITIES
     *******************************************/

    /**
     * Checks whether this filter should transform the specified action data.
     * @param Action $action the action to be performed
     * @param mixed $data the data to be transformed
     * @return bool `true` if the transformer should be applied, `false` if the transformer should be ignored
     */
    protected function shouldTransform($action, $data): bool
    {
        if ($this->matchData($data) &&
            $this->matchCustom($action, $data)) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed $data the data to be transformed
     * @return bool whether the transformer should be applied
     */
    protected function matchData($data)
    {
        return empty($data) && $this->transformEmpty !== true ? false : true;
    }

    /**
     * @param Action $action the action to be performed
     * @param mixed $data the data to be transformed
     * @return bool whether the transformer should be applied
     */
    protected function matchCustom($action, $data)
    {
        return empty($this->matchCallback) || call_user_func($this->matchCallback, $this, $action, $data);
    }
}
