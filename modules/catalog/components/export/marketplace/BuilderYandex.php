<?php
namespace common\modules\catalog\components\export\marketplace;

use Yii;

class BuilderYandex extends BuilderMarketplace
{

    public function build($product) {
        $this->_product->setProduct($product);

        parent::build($product);
        self::buildUrl($product);
        self::buildOldPrice($product);
        self::buildTypePrefix($product);
        self::buildExpiry($product);
        self::buildPicture($product);
    }

    private function buildUrl($product) {
        $this->_product->setUrl('http://kranik.by/catalog/'.$product['manufacturer_alias'].'/'.$product['alias'].'/');
    }

    private function buildOldPrice($product) {
        if ($price = $product['price_old']) {
            $this->_product->setOldPrice($price);
        }
    }

    private function buildTypePrefix($product) {
        if ($title_before = $product['title_before']) {
            $this->_product->setTypePrefix($title_before);
        }
    }

    private function buildExpiry($product) {
        if ($expiry = $product['life_time']) {
            // @todo: Приветси к одному виду срок
            $this->_product->setExpiry('Y'.$expiry); // Y - месяцев
        }
    }

    private function buildPicture($product) {
        if ($photo = $product['photo']) {
            $this->_product->setPicture('http://kranik.by/statics/catalog/photo/images/'.$photo);
        }
    }

    public function getProduct() {
        return [
            'id' => $this->_product->getId(),
            'url' => $this->_product->getUrl(),
            'price' => $this->_product->getPrice(),
            'oldprice' => $this->_product->getOldPrice(),
            'currencyId' => $this->_product->getCurrency(),
            'categoryId' => $this->_product->getCategory('id'),
            'picture' => $this->_product->getPicture(),
            'delivery' => true,
            'typePrefix' => $this->_product->getTypePrefix(),
            'vendor' => $this->_product->getVendor(),
            'model' => $this->_product->getModel(),
            'description' => $this->_product->getComment(),
            'expiry' => $this->_product->getExpiry(),
        ];
    }

}