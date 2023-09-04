<?php
namespace common\modules\catalog\components\export\marketplace;

use Yii;

class BuilderYandexOnekby extends BuilderYandex
{

    public function build($product)
    {
        $this->_product->setProduct($product);
        parent::build($product);
    }

    public function buildModel($product)
    {
        if ($product['tp_1k_by_title']) {
            $this->_product->setModel($product['tp_1k_by_title']);
        } else {
            $this->_product->setModel($product['title']);
        }
    }

}