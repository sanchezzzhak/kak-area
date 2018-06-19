<?php
namespace kak\widgets\area\bundles;

use yii\web\AssetBundle;

/**
 * Class TmplAsset
 * @package kak\widgets\area
 * @docs https://github.com/blueimp/JavaScript-Templates
 */
class AreaAsset extends AssetBundle
{
    public $sourcePath = '@vendor/kak/area/assets';
    public $depends = [
        'yii\web\JqueryAsset'
    ];
    public $js = [
        'area.js'
    ];
}