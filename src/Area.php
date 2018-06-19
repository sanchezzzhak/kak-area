<?php

namespace kak\widgets\area;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use kak\widgets\area\bundles\TmplAsset;
use kak\widgets\area\bundles\AreaAsset;


/**
 * Class Area
 * @package kak\widgets\area
 */
class Area extends \yii\base\Widget
{
    /**
     * @var array the HTML attributes for the widget container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /** @var string text button */
    public $buttonLabel = '+';

    /** @var array html options button add item */
    public $buttonOptions = [];

    /** @var String|Closure render view file templated */
    public $viewItem;

    /** @var array addintion view params */
    public $viewParams;

    /** @var string template btn layout */
    public $template = '{button} {label}';

    /** @var array default item or model */
    public $item;

    /** @var array data array or models */
    public $items = [];

    /** @var array html options block item */
    public $itemOptions = [];

    /** @var string text label */
    public $label = 'add item';

    /** @var array html options label */
    public $labelOptions = [];

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

    /**
     * @return string generate uuid block id
     */
    public function getAreaId()
    {
        return 'areaId' . $this->options['id'];
    }

    /**
     * set default html options
     */
    protected function initDefaultOptions()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        Html::addCssClass($this->itemOptions, 'areaItem');
        Html::addCssClass($this->buttonOptions, 'btn');
    }

    /**
     * render html template placeholder
     */
    protected function renderButtonAdd()
    {
        $this->buttonOptions = ArrayHelper::merge($this->buttonOptions, [
            'role' => 'area.add',
            'data-tmpl' => $this->options['id']
        ]);
        $template = strtr($this->template, [
            '{button}' => Html::button($this->buttonLabel, $this->buttonOptions),
            '{label}' => Html::label($this->label, $this->labelOptions)
        ]);
        echo Html::tag('div', $template, ['class' => 'form-group']);
    }

    /**
     * render default item, view
     * @return mixed|string
     */
    protected function renderTemplateItemForm()
    {
        if($this->viewItem===null){
            return '';
        }
        return $this->renderTemplateItem($this->item);
    }

    /**
     * render store items, view
     * @param $item
     * @return mixed|string
     */
    protected function renderTemplateItem($item)
    {
        $params = $this->viewParams;
        $params['item'] = $item;

        if($this->viewItem instanceof \Closure){
            return call_user_func($this->viewItem, $params, $this);
        }
        return $this->getView()->render($this->viewItem, $params);
    }

    /**
     * render store items, block template
     * @param $item
     * @return mixed
     */
    protected function renderTemplateBlock($item)
    {
        $keys = $item instanceof Model ? $item->getAttributes() : array_keys($item);
        $clip = (string)$this->getView()->blocks[$this->getAreaId()];
        foreach ($keys as $key) {
            $value = Html::encode($item[$key]);
            $clip = str_replace('{%=o.' . $key . '%}', $value, $clip);
        }
        return $clip = preg_replace('#\{\%\=o(.*)\%\}#ism', '', $clip);
    }


    public function run()
    {
        TmplAsset::register($this->getView());
        AreaAsset::register($this->getView());

        echo $this->renderTemplateItemForm();
        echo Html::endTag('div');

        $this->getView()->endBlock();

        echo Html::endTag('script');

        echo Html::beginTag('div', [
            'id' => $this->getAreaId()
        ]);
        foreach ($this->items as $item) {
            echo Html::beginTag('div', $this->itemOptions);
            if($this->viewItem !==null){
                echo $this->renderTemplateItem($item);
            }else{
                echo $this->renderTemplateBlock($item);
            }
            echo Html::endTag('div');
        }
        echo Html::endTag('div');


    }
}
