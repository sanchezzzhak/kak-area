<?php

namespace kak\widgets\area;
use yii\helpers\Html;

class Area extends \yii\base\Widget
{
    /**
     * @var array the HTML attributes for the widget container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
    public $itemOptions = [];

    /** @var array */
    public $label;
    public $items = [];

    public function init()
    {
        parent::init();
        $this->initDefaultOptions();
        $this->renderButtonAdd();

        echo Html::beginTag('script',[
            'id' => $this->options['id'],
            'type' => 'text/x-tmpl'
        ]);
        $this->getView()->beginBlock( $this->getAreaId() ,true);
            echo Html::beginTag('div',$this->itemOptions);
    }

    protected function initDefaultOptions()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        Html::addCssClass($this->itemOptions,'areaItem');
    }

    protected function renderButtonAdd()
    {
        $btn = Html::button('+',['class' => 'btn btn-info',
            'role' => 'area.add',
            'data-tmpl' => $this->options['id']
        ]);
        echo Html::tag('div', $btn . ' '. Html::tag('label', $this->label ) ,[ 'class' => 'form-group' ]);
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
        echo Html::beginTag('div',[
            'id' => $this->getAreaId()
        ]);
        foreach ($this->items as $item) {
            $keys = array_keys($item);
            $clip = (string)$this->getView()->blocks[$this->getAreaId()];
            foreach ($keys as $key) {
                $clip = str_replace('{%=o.'.$key.'%}',$item[$key],$clip);
            }
            $clip= preg_replace('#\{\%\=o(.*)\%\}#ism','',$clip);
            echo $clip;
        }

        echo Html::endTag('div');
        $view = $this->getView();
        TmplAsset::register($view);
        AreaAsset::register($view);


    }
}
