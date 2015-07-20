Area content widgets for Yii2
================
The widget can, Duplicate area by clicking the button
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

Usage
-----
Once the extension is installed, simply use it in your code by  :
```php
<?php kak\widgets\area\Area::begin([
        'label' => 'add params',
        'items' => [
            ['key' => 'key.name']
            ['key' => 'key.name1']
            ['key' => 'key.name2' , 'value' => '2']
            ['key' => 'key.name3']
        ]
]);?>
    <div class="row form-group">
        <div class="col-xs-4">
            <input type="text" name="params[key][]"  placeholder="Key"  value="{%=o.key%}" class="form-control"/>
        </div>
        <div class="col-xs-4">
            <input type="text" name="params[value][]" placeholder="Value"  value="{%=o.value%}" class="form-control"/>
        </div>
        <div class="col-xs-4">
            <button type="button" class="btn btn-danger" role="area.remove" >-</button>
        </div>
    </div>
<?php kak\widgets\area\Area::end();?>

```