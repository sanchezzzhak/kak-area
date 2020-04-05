<?php

namespace kak\widgets\area;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * use example
 */
/*
```php






```;
 */


/**
 * Class Area
 * @package kak\widgets\area
 */
class Area extends \yii\base\Widget
{
    public const JS_KEY = 'kak-area';
    public const POSITION_TOP = 'top';
    public const POSITION_BOTTOM = 'bottom';


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

    public $clientOptions = [];

    public $insertControlPos = self::POSITION_TOP;

    public $insertPosition = self::POSITION_BOTTOM;

    public function init()
    {
        parent::init();
        $this->initDefaultOptions();

        echo Html::beginTag('div', $this->options);

        // render insert constrol is position top
        if ($this->insertControlPos === self::POSITION_TOP) {
            echo $this->renderInsertControl();
        }

        // render template script start block
        echo Html::beginTag('script', [
            'id' => $this->getTemplateId(),
            'type' => 'text/x-tmpl'
        ]);
        $this->getView()->beginBlock($this->getAreaId(), true);
        echo Html::beginTag('div', $this->itemOptions);
    }

    /**
     * set default options
     */
    protected function initDefaultOptions()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        Html::addCssClass($this->itemOptions, 'area-item');
        Html::addCssClass($this->buttonOptions, 'btn');
    }

    /**
     * render html template placeholder
     * @return string
     */
    protected function renderInsertControl()
    {
        $template = strtr($this->template, [
            '{button}' => Html::button($this->buttonLabel, $this->buttonOptions),
            '{label}' => Html::label($this->label, $this->labelOptions)
        ]);
        return Html::tag('div', $template, ['class' => 'form-group']);
    }

    /**
     * render store items, view
     * @param $item
     * @return mixed|string
     */
    protected function renderTemplateByViewItem($item)
    {
        $params = $this->viewParams;
        $params['item'] = $item;

        if ($this->viewItem instanceof \Closure) {
            return call_user_func($this->viewItem, $params, $this);
        }
        return $this->render($this->viewItem, $params);
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

    /**
     * @return string generate uuid wrapper id
     */
    protected function getAreaId()
    {
        return sprintf('template-area-wrapper-%s', $this->getId());
    }

    /**
     * @return string generate uuid template id
     */
    protected function getTemplateId()
    {
        return sprintf('template-area-%s', $this->getId());
    }

    public function run()
    {
        // render template script end block

        if ($this->viewItem !== null) {
            echo $this->renderTemplateByViewItem($this->item);
        }
        echo Html::endTag('div');
        $this->getView()->endBlock();
        echo Html::endTag('script');

        // render store blocks
        echo Html::beginTag('div', [
            'id' => $this->getAreaId()
        ]);
        foreach ($this->items as $item) {
            echo Html::beginTag('div', $this->itemOptions);
            if ($this->viewItem !== null) {
                echo $this->renderTemplateByViewItem($item);
            } else {
                echo $this->renderTemplateBlock($item);
            }
            echo Html::endTag('div');
        }
        echo Html::endTag('div');

        // render insert control is position bottom
        if ($this->insertControlPos === self::POSITION_BOTTOM) {
            echo $this->renderInsertControl();
        }
        echo Html::endTag('div');

        $view = $this->getView();
        // register js and assets
        TmplAsset::register($view);
        AreaAsset::register($view);

        $id = $this->getId();
        $this->clientOptions['areaId'] = $this->getAreaId();
        $this->clientOptions['tmplId'] = $this->getTemplateId();
        $this->clientOptions['templData'] = $this->item;
        $this->clientOptions['insertPosition'] = $this->insertPosition;

        $clientOptions = Json::htmlEncode($this->clientOptions);
        $view->registerJs(
            "jQuery('#{$id}').kakArea({$clientOptions});",
            $view::POS_READY,
            sprintf("%s-%s", self::JS_KEY, $id)
        );
    }
}
