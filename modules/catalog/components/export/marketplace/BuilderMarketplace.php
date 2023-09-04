<?php
namespace common\modules\catalog\components\export\marketplace;

abstract class BuilderMarketplace
{
    /**
     * @var Product $_product
     */
    protected $_product;

    public function createNewProduct() {
        $this->_product = new Product();

    }

    public function build($product) {
        $this->_product->setProduct($product);

        self::buildId($product);
        self::buildCategory($product);
        self::buildVendor($product);
        static::buildModel($product);
        self::buildPrice($product);
        self::buildComment($product);

    }

    private function buildId($product) {
        $this->_product->setId($product['id']);
    }

    private function buildCategory($product) {
        $this->_product->setCategory([
            'id' => $product['category_id'],
            'title' => $product['category_title'],
        ]);
    }

    private function buildVendor($product) {
        $this->_product->setVendor($product['manufacturer_title']);
    }

    private function buildModel($product) {
        $this->_product->setModel($product['title']);
    }

    private function buildPrice($product) {
        $this->_product->setPrice($product['price']);
    }

    private function buildComment($product) {
        if ($code = $product['code_1c']) $code = 'Код: '.$code;
        $this->_product->setComment($code.'. Официальный импортер в РБ. Профессиональная консультация. Все виды платежей, Официальная гарантия, чек. Скидки. Доставка по РБ. Вся Сантехника и Керамическая Плитка - в магазине kranik.by.');
    }


    public abstract function getProduct();
}