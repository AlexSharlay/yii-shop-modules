<?php
namespace common\modules\catalog\components\export\marketplace;

use Yii;

class BuilderOnliner extends BuilderMarketplace
{

    public function build($product) {
        $this->_product->setProduct($product);

        parent::build($product);
        self::buildProducer($product);
        self::buildImporter($product);
        self::buildServiceCenters($product);
        self::buildWarranty($product);
        self::buildDeliveryTownTime();
        self::buildDeliveryTownPrice($product);
        self::buildDeliveryCountryTime();
        self::buildDeliveryCountryPrice($product);
        self::buildProductLifeTime($product);
    }

    public function buildModel($product)
    {
        if ($product['tp_onliner_by_title']) {
            $this->_product->setModel($product['tp_onliner_by_title']);
        } else {
            $this->_product->setModel($product['title']);
        }
    }

    public function buildProducer($product) {
        if ($info_manufacturer = $product['info_manufacturer']) {
            $this->_product->setProducer($info_manufacturer);
        }
    }

    public function buildImporter($product) {
        if ($info_importer = $product['info_importer']) {
            $this->_product->setImporter($info_importer);
        }
    }

    public function buildServiceCenters($product) {
        if ($info_service = $product['info_service']) {
            $this->_product->setServiceCenters($info_service);
        }
    }

    public function buildWarranty($product) {
        if ($guarantee = $product['guarantee']) {
            $this->_product->setWarranty($guarantee);
        }
    }

    public function buildDeliveryTownTime() {
        $this->_product->setDeliveryTownTime(1);
    }

    public function buildDeliveryTownPrice($product) {
        $price = $product['price'];
        $priceDelivery = 0;
        switch ($price) {
            case ($price <= 2000):
                $priceDelivery = 2000;
                break;
            case ($price > 2000 && $price <= 5000):
                $priceDelivery = 100000;
                break;
            case ($price > 5000 && $price <= 10000):
                $priceDelivery = 500;
                break;
            case ($price > 10000):
                $priceDelivery = 0;
                break;
        }
        $this->_product->setDeliveryTownPrice($priceDelivery);
    }

    public function buildDeliveryCountryTime() {
        $this->_product->setDeliveryCountryTime(3);
    }

    public function buildDeliveryCountryPrice($product) {
        $price = $product['price'];
        $priceDelivery = 0;
        switch ($price) {
            case ($price <= 2000):
                $priceDelivery = 3400;
                break;
            case ($price > 2000 && $price <= 5000):
                $priceDelivery = 240000;
                break;
            case ($price > 5000 && $price <= 10000):
                $priceDelivery = 1900;
                break;
            case ($price > 10000 && $price <= 40000):
                $priceDelivery = 140000;
                break;
            case ($price > 40000):
                $priceDelivery = 0;
                break;
        }
        $this->_product->setDeliveryCountryPrice($priceDelivery);
    }

    public function buildProductLifeTime($product) {
        if ($life_time = $product['life_time']) {
            $this->_product->setProductLifeTime($life_time);
        }
    }

    public function getProduct() {
        return [
            'category' => $this->_product->getCategory('title'),
            'vendor' => $this->_product->getVendor(),
            'model' => $this->_product->getModel(),
            'price' => $this->_product->getPrice(),
            'currency' => $this->_product->getCurrency(),
            'comment' => $this->_product->getComment(),
            'producer' => $this->_product->getProducer(),
            'importer' => $this->_product->getImporter(),
            'serviceCenters' => $this->_product->getServiceCenters(),
            'warranty' => $this->_product->getWarranty(),
            'deliveryTownTime' => $this->_product->getDeliveryTownTime(),
            'deliveryTownPrice' => $this->_product->getDeliveryTownPrice(),
            'deliveryCountryTime' => $this->_product->getDeliveryCountryTime(),
            'deliveryCountryPrice' => $this->_product->getDeliveryCountryPrice(),
            'productLifeTime' => $this->_product->getProductLifeTime(),
            'isCashless' => $this->_product->getIsCashless(),
            'isCredit' => $this->_product->getIsCredit(),
        ];
    }

}
