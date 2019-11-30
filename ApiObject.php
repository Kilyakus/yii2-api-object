<?php
namespace kilyakus\api\components;

use Yii;
use yii\base\Object;
use kilyakus\imageprocessor\Image;

class ApiObject extends Object
{
    public $transferClasses = [];

    public $model;

    public function __construct($model){
        $this->model = $model;

        foreach($model->attributes as $attribute => $value){
            if($this->canSetProperty($attribute)){
                $this->{$attribute} = $value;
            }
        }

        $this->init();
    }

    public function init(){}

    public function getId(){
        return $this->model->primaryKey;
    }

    public function thumb($attribute, $width = null, $height = null, $crop = true)
    {
        if($this->{$attribute} && ($width || $height)){
            return Image::thumb($this->{$attribute}, $width, $height, $crop);
        }
        return '';
    }

    public function seo($attribute, $default = ''){
        return !empty($this->model->seo->{$attribute}) ? $this->model->seo->{$attribute} : $default;
    }

    public function translate($attribute, $default = ''){
        return !empty($this->model->translate->{$attribute}) ? $this->model->translate->{$attribute} : $default;
    }

    public function __get($name){
       if (array_key_exists($name, $this->transferClasses))
           return $this->transferClasses[$name];

       return parent::__get($name);
    }

    public function __set($name, $value){
       if (array_key_exists($name, $this->transferClasses))
           $this->transferClasses[$name] = $value;

       else parent::__set($name, $value);
    }

    public function put($attribute)
    {
        $this->transferClasses[$attribute] = null;
        $this->__set($attribute, null);
    }
}