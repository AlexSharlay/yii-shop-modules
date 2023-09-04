<?php
namespace common\modules\catalog\components\export\marketplace;

use Yii;

class Product
{
    private $_product  = [];

    // Онлайнер и Яндекс
    private $_category = [
        'id' => null,
        'title' => null,
    ];

    private $_vendor;
    private $_model;
    private $_price;
    private $_currency = 'BYN';
    private $_comment;

    // Только Онлайнер
    private $_producer;
    private $_importer;
    private $_serviceCenters;
    private $_warranty;
    private $_deliveryTownTime;
    private $_deliveryTownPrice;
    private $_deliveryCountryTime;
    private $_deliveryCountryPrice;
    private $_productLifeTime;
    private $_isCashless = 'Нет';
    private $_isCredit = 'Нет';

    // Только Яндекс
    private $_id;
    private $_url;
    private $_oldPrice;
    private $_picture;
    private $_typePrefix;
    private $_expiry;

    /**
     * @return array
     */
    public function getProduct()
    {
        return $this->_product;
    }

    /**
     * @param array $product
     */
    public function setProduct($product)
    {
        $this->_product = $product;
    }

    /**
     * @return array
     */
    public function getCategory($index)
    {
        return $this->_category[$index];
    }

    /**
     * @param array $category
     */
    public function setCategory($category)
    {
        $this->_category = $category;
    }

    /**
     * @return mixed
     */
    public function getVendor()
    {
        return $this->_vendor;
    }

    /**
     * @param mixed $vendor
     */
    public function setVendor($vendor)
    {
        $this->_vendor = $vendor;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->_model = $model;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->_price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->_price = $price;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->_currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->_currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->_comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->_comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getProducer()
    {
        return $this->_producer;
    }

    /**
     * @param mixed $producer
     */
    public function setProducer($producer)
    {
        $this->_producer = $producer;
    }

    /**
     * @return mixed
     */
    public function getImporter()
    {
        return $this->_importer;
    }

    /**
     * @param mixed $importer
     */
    public function setImporter($importer)
    {
        $this->_importer = $importer;
    }

    /**
     * @return mixed
     */
    public function getServiceCenters()
    {
        return $this->_serviceCenters;
    }

    /**
     * @param mixed $serviceCenters
     */
    public function setServiceCenters($serviceCenters)
    {
        $this->_serviceCenters = $serviceCenters;
    }

    /**
     * @return mixed
     */
    public function getWarranty()
    {
        return $this->_warranty;
    }

    /**
     * @param mixed $warranty
     */
    public function setWarranty($warranty)
    {
        $this->_warranty = $warranty;
    }

    /**
     * @return mixed
     */
    public function getDeliveryTownTime()
    {
        return $this->_deliveryTownTime;
    }

    /**
     * @param mixed $deliveryTownTime
     */
    public function setDeliveryTownTime($deliveryTownTime)
    {
        $this->_deliveryTownTime = $deliveryTownTime;
    }

    /**
     * @return mixed
     */
    public function getDeliveryTownPrice()
    {
        return $this->_deliveryTownPrice;
    }

    /**
     * @param mixed $deliveryTownPrice
     */
    public function setDeliveryTownPrice($deliveryTownPrice)
    {
        $this->_deliveryTownPrice = $deliveryTownPrice;
    }

    /**
     * @return mixed
     */
    public function getDeliveryCountryTime()
    {
        return $this->_deliveryCountryTime;
    }

    /**
     * @param mixed $deliveryCountryTime
     */
    public function setDeliveryCountryTime($deliveryCountryTime)
    {
        $this->_deliveryCountryTime = $deliveryCountryTime;
    }

    /**
     * @return mixed
     */
    public function getDeliveryCountryPrice()
    {
        return $this->_deliveryCountryPrice;
    }

    /**
     * @param mixed $deliveryCountryPrice
     */
    public function setDeliveryCountryPrice($deliveryCountryPrice)
    {
        $this->_deliveryCountryPrice = $deliveryCountryPrice;
    }

    /**
     * @return mixed
     */
    public function getProductLifeTime()
    {
        return $this->_productLifeTime;
    }

    /**
     * @param mixed $productLifeTime
     */
    public function setProductLifeTime($productLifeTime)
    {
        $this->_productLifeTime = $productLifeTime;
    }

    /**
     * @return mixed
     */
    public function getIsCashless()
    {
        return $this->_isCashless;
    }

    /**
     * @param mixed $isCashless
     */
    public function setIsCashless($isCashless)
    {
        $this->_isCashless = $isCashless;
    }

    /**
     * @return mixed
     */
    public function getIsCredit()
    {
        return $this->_isCredit;
    }

    /**
     * @param mixed $isCredit
     */
    public function setIsCredit($isCredit)
    {
        $this->_isCredit = $isCredit;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->_url = $url;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getOldPrice()
    {
        return $this->_oldPrice;
    }

    /**
     * @param mixed $oldPrice
     */
    public function setOldPrice($oldPrice)
    {
        $this->_oldPrice = $oldPrice;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->_picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this->_picture = $picture;
    }

    /**
     * @return mixed
     */
    public function getTypePrefix()
    {
        return $this->_typePrefix;
    }

    /**
     * @param mixed $typePrefix
     */
    public function setTypePrefix($typePrefix)
    {
        $this->_typePrefix = $typePrefix;
    }

    /**
     * @return mixed
     */
    public function getExpiry()
    {
        return $this->_expiry;
    }

    /**
     * @param mixed $expiry
     */
    public function setExpiry($expiry)
    {
        $this->_expiry = $expiry;
    }

}