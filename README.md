Area content widgets for Yii2
================
The widget can, Duplicate area by clicking the button
Preview
-----------
<img src="https://lh3.googleusercontent.com/-G6BBKhyQqVg/Va0CmtW6v-I/AAAAAAAAACg/FnU_Qc9DyiU/s512-Ic42/areaPreview.png">

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kak/area "*"
```

or add

```
"kak/area": "*"
```

to the require section of your `composer.json` file.

Usage area widget
-----
Once the extension is installed, simply use it in your code by:
```php
<?php kak\widgets\area\Area::begin([
        'label' => 'add params',
        'items' => [
            ['key' => 'key.name'],
            ['key' => 'key.name1'],
            ['key' => 'key.name2' , 'value' => '2'],
            ['key' => 'key.name3'],
        ]
]);?>
    <div class="row form-group">
        <div class="col-xs-1">
            <button type="button" class="btn btn-danger" role="area.remove" >-</button>
        </div>    
        <div class="col-xs-4">
            <input type="text" name="params[key][]"  placeholder="Key"  value="{%=o.key%}" class="form-control"/>
        </div>
        <div class="col-xs-4">
            <input type="text" name="params[value][]" placeholder="Value"  value="{%=o.value%}" class="form-control"/>
        </div>
    </div>
<?php kak\widgets\area\Area::end();?>

```
Usage area widget render template file
-----
Once the extension is installed, simply use it in your code by:
```php
<?php
$models = PostParams::findOne($id);
kak\widgets\area\Area::widget([
    'item' => new PostParams,
    'items' => $models,
    'viewParams' => [
        'form' => $form
    ],
    'viewItem' => '@app\views\post\_item-post-param'
]);
```
viewItem is closure function support 
```php
<?php
 kak\widgets\area\Area::widget([
     'item' => new PostParams,
     'items' => $models,
     'viewParams' => [
         'form' => $form
     ],
     'viewItem' => function($params, $thisArea){
        
     },
 ]);   
```

## kak\widgets\area\Area
Class Area
@package kak\widgets\area

*public* `buttonLabel` string text button

*public* `buttonOptions` array html options button add item

*public* `viewItem` String|Closure render view file templated

*public* `viewParams` array addintion view params

*public* `template` string template btn layout

*public* `item` array default item or model

*public* `items` array data array or models

*public* `itemOptions` array html options block item

*public* `label` string text label

*public* `labelOptions` array html options label
