<?php

namespace kak\widgets\area;
use yii\helpers\Html;

class Area extends \yii\bootstrap\Widget
{
    /**
     * @var array the HTML attributes for the widget container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /** @var array */
    public $data = [];

    public $tmplRemove = '<button type="button" class="btn btn-danger">-</button>';


    public function init()
    {

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        $areaId = $this->getAreaId();

        echo Html::button('+' , ['class' => 'btn btn-info']);
        echo Html::beginTag('script',[
            'id' =>  $this->options['id'],
            'type' => 'text/x-tmpl'
        ]);
        $this->getView()->beginBlock($areaId,true);

    }

    public function getAreaId()
    {
       return 'areaId' . $this->options['id'];
    }


    public function run()
    {
        $this->getView()->endBlock();
        Html::endTag('script');
        $view = $this->getView();
        TmplAsset::register($view);
    }
}
