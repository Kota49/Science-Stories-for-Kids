<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\components\useraction;

use app\components\TBaseWidget;

/**
 * User action buttons
 */
class UserAction extends TBaseWidget
{

    /**
     *
     * @var unknown
     */
    public $model;

    /**
     *
     * @var string
     */
    public $attribute =  "state_id";

    /**
     *
     * @var integer add the states for user action
     */
    public $states;

    /**
     *
     * @var string path for view file
     */
    public $actions;

    /**
     *
     * @var string
     */
    public $allowed;

    /**
     *
     * @var string
     */
    public $buttons;

    /**
     *
     * @var string visibility check for user
     */
    public $visible;

    /**
     *
     * @var string path for view file
     */
    public $title;

    /**
     *
     * @var string path for view file
     */
    public $style = 'button-action';

    public function getButtonColor($id)
    {
        if (is_string($id)) {
            $id = array_search($id, $this->allowed);
        }

        if (method_exists($this->model, 'getStateBadgeClassList')) {
            $list = $this->model->getStateBadgeClassList();
        }
        if (isset($list[$id]))
            return $list[$id];
        return 'primary';
    }

    public function init()
    {
        if (! isset($this->visible)) {
            $this->visible = $this->model->isAllowed();
        }
        if (! $this->model->hasAttribute($this->attribute)) {
            $this->visible = false;
            return;
        }
        if (empty($this->actions))
            $this->actions = $this->states;

        if (empty($this->allowed)) {
            if (method_exists($this->model, 'getStateWorkflow')) {
                $this->allowed = [];
                foreach ($this->model->getStateWorkflow()[$this->model->{$this->attribute}] as $id) {
                    $this->allowed[$id] = $this->actions[$id];
                }
            }
            if (empty($this->allowed)) {
                $this->allowed = $this->actions;
                $this->allowed[$this->model->{$this->attribute}] = null;
                $this->allowed = array_filter($this->allowed);
            }
        }

        if (method_exists($this->model, 'getActionOptions')) {
            $this->buttons = $this->model->getActionOptions();
        } else {
            $this->buttons = $this->actions;
        }

        $this->title = '';

        parent::init();
    }

    public function renderHtml()
    {
        if (isset($_POST['workflow'])) {
            $submit = trim($_POST['workflow']);
            $state_list = $this->states;
            $allowed = $this->allowed;

            $state_id = $submit; // array_search($submit, $actions);

            $ok = array_search($submit, $allowed);

            if ($ok >= 0 && $state_id >= 0 && $state_id != $this->model->{$this->attribute}) {
                $old_state = $state_list[$this->model->{$this->attribute}];
                $new_state = $state_list[$state_id];

                $this->model->{$this->attribute} = $state_id;
                if ($this->model->isAllowed() && $this->model->save()) {
                    \Yii::$app->session->setFlash('user-action', 'State Changed.');
                    $msg = 'State Changed : ' . $old_state . ' to ' . $new_state;
                    $this->model->updateHistory($msg);
                    \Yii::$app->session->setFlash('user-action', $msg);

                    \Yii::$app->controller->redirect($this->model->getUrl());
                } else {
                    $error = 'You are not allowed to perform this operation.' . $this->model->getErrorsString();

                    \Yii::$app->session->setFlash('user-action', $error);
                }
            }
        }

        if (! empty($this->model))
            echo $this->render($this->style, [
                'model' => $this->model,
                'allowed' => $this->allowed,
                'buttons' => $this->buttons,
                'attribute' => $this->attribute,
                'title' => $this->title
            ]);
    }
}
