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
<?php \kak\widgets\Area::begin(); ?>
  <div> ... Content for duplication ... {actions} </div>
<?php \kak\widgets\Area::end(); ?>

```