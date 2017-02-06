<?php

namespace kak\widgets\area;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Area extends \yii\base\Widget
{
    /**
     * @var array the HTML attributes for the widget container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
    public $itemOptions = [];
    public $buttonOptions = [];

    /** @var array */
    public $label;
    public $templateButtonAdding = '{button} {label}';
    public $items = [];

    public function init()
    {
        parent::init();
        $this->initDefaultOptions();
        $this->renderButtonAdd();

        echo Html::beginTag('script', [
            'id' => $this->options['id'],
            'type' => 'text/x-tmpl'
        ]);
        $this->getView()->beginBlock($this->getAreaId(), true);
        echo Html::beginTag('div', $this->itemOptions);
    }

    protected function initDefaultOptions()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        Html::addCssClass($this->itemOptions, 'areaItem');
    }

    protected function renderButtonAdd()
    {
        Html::addCssClass($this->buttonOptions, 'btn');
        $this->buttonOptions = ArrayHelper::merge($this->buttonOptions, [
            'role' => 'area.add',
            'data-tmpl' => $this->options['id']
        ]);

        $templateButtonAdding = strtr($this->templateButtonAdding, [
            '{button}' => Html::button('+', $this->buttonOptions),
            '{label}' => Html::tag('label', $this->label)
        ]);
        echo Html::tag('div', $templateButtonAdding, ['class' => 'form-group']);
    }

    public function getAreaId()
    {
        return 'areaId' . $this->options['id'];
    }

    public function run()
    {
        echo Html::endTag('div');
        $this->getView()->endBlock();
        echo Html::endTag('script');
        echo Html::beginTag('div', [
            'id' => $this->getAreaId()
        ]);
        foreach ($this->items as $item) {
            $keys = array_keys($item);
            $clip = (string)$this->getView()->blocks[$this->getAreaId()];
            foreach ($keys as $key) {
                $value = Html::encode($item[$key]);
                $clip = str_replace('{%=o.' . $key . '%}', $value, $clip);
            }
            $clip = preg_replace('#\{\%\=o(.*)\%\}#ism', '', $clip);
            echo $clip;
        }

        echo Html::endTag('div');
        $view = $this->getView();
        TmplAsset::register($view);
        AreaAsset::register($view);


    }
}
