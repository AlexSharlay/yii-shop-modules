<?php
namespace common\modules\catalog\components\product;

class Product
{
    private $_id;
    private $_id_kit;
    private $_title;
    private $_alias;
    private $_desc_full;
    private $_code_1c;
    private $_title_model;
    private $_price;
    private $_price_old;
    private $_guarantee;
    private $_in_stock;
    private $_category_id;
    private $_category_id_1c;
    private $_category_title;
    private $_category_alias;
    private $_manufacturer_id;
    private $_manufacturer_title;
    private $_manufacturer_alias;
    private $_measurement_id;
    private $_measurement_title;
    private $_measurement_code;
    private $_photo = [];
    private $_model_children = [];
    private $_model_parent = [];
    private $_complect_children = [];
    private $_complect_parent = [];
    private $_kit_children = [];
    private $_fields;
    private $_delivery;
    private $_discounts;
    private $_reviews;


    public function __construct($id, $id_kit)
    {
        $this->setId($id);
        $this->setIdKit($id_kit);
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
    public function getIdKit()
    {
        return $this->_id_kit;
    }

    /**
     * @param mixed $id_kit
     */
    public function setIdKit($id_kit)
    {
        $this->_id_kit = $id_kit;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }


    /**
     * @return mixed
     */
    public function getTitleBefore()
    {
        return $this->_title_before;
    }

    /**
     * @param mixed $title_before
     */
    public function setTitleBefore($title_before)
    {
        $this->_title_before = $title_before;
    }


    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->_alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias)
    {
        $this->_alias = $alias;
    }

    /**
     * @return mixed
     */
    public function getDescFull()
    {
        return $this->_desc_full;
    }

    /**
     * @param mixed $desc_full
     */
    public function setDescFull($desc_full)
    {
        $this->_desc_full = $desc_full;
    }

    /**
     * @return mixed
     */
    public function getCode1c()
    {
        return $this->_code_1c;
    }

    /**
     * @param mixed $code_1c
     */
    public function setCode1c($code_1c)
    {
        $this->_code_1c = $code_1c;
    }


    /**
     * @return mixed
     */
    public function getVendorCode()
    {
        return $this->_vendor_code;
    }

    /**
     * @param mixed $vendor_code
     */
    public function setVendorCode($vendor_code)
    {
        $this->_vendor_code = $vendor_code;
    }


    /**
     * @return mixed
     */
    public function getInfoManufacturer()
    {
        return $this->_info_manufacturer;
    }

    /**
     * @param mixed $info_manufacturer
     */
    public function setInfoManufacturer($info_manufacturer)
    {
        $this->_info_manufacturer = $info_manufacturer;
    }


    /**
     * @return mixed
     */
    public function getTitleModel()
    {
        return $this->_title_model;
    }

    /**
     * @param mixed $title_model
     */
    public function setTitleModel($title_model)
    {
        $this->_title_model = $title_model;
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
    public function getPriceOld()
    {
        return $this->_price_old;
    }

    /**
     * @param mixed $price_old
     */
    public function setPriceOld($price_old)
    {
        $this->_price_old = $price_old;
    }

    /**
     * @return mixed
     */
    public function getGuarantee()
    {
        return $this->_guarantee;
    }

    /**
     * @param mixed $guarantee
     */
    public function setGuarantee($guarantee)
    {
        $this->_guarantee = $guarantee;
    }

    /**
     * @return mixed
     */
    public function getInStock()
    {
        return $this->_in_stock;
    }

    /**
     * @param mixed $in_stock
     */
    public function setInStock($in_stock)
    {
        $this->_in_stock = $in_stock;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->_category_id;
    }

    /**
     * @return mixed
     */
    public function getCategoryId1c()
    {
        return $this->_category_id_1c;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->_category_id = $category_id;
    }

    /**
     * @param mixed $category_id_1c
     */
    public function setCategoryId1c($category_id_1c)
    {
        $this->_category_id_1c = $category_id_1c;
    }

    /**
     * @return mixed
     */
    public function getCategoryTitle()
    {
        return $this->_category_title;
    }

    /**
     * @param mixed $category_title
     */
    public function setCategoryTitle($category_title)
    {
        $this->_category_title = $category_title;
    }

    /**
     * @return mixed
     */
    public function getCategoryAlias()
    {
        return $this->_category_alias;
    }

    /**
     * @param mixed $catalog_alias
     */
    public function setCategoryAlias($category_alias)
    {
        $this->_category_alias = $category_alias;
    }

    /**
     * @return mixed
     */
    public function getManufacturerId()
    {
        return $this->_manufacturer_id;
    }

    /**
     * @param mixed $manufacturer_id
     */
    public function setManufacturerId($manufacturer_id)
    {
        $this->_manufacturer_id = $manufacturer_id;
    }

    /**
     * @return mixed
     */
    public function getManufacturerTitle()
    {
        return $this->_manufacturer_title;
    }

    /**
     * @param mixed $manufacturer_title
     */
    public function setManufacturerTitle($manufacturer_title)
    {
        $this->_manufacturer_title = $manufacturer_title;
    }

    /**
     * @return mixed
     */
    public function getManufacturerAlias()
    {
        return $this->_manufacturer_alias;
    }

    /**
     * @param mixed $manufacturer_alias
     */
    public function setManufacturerAlias($manufacturer_alias)
    {
        $this->_manufacturer_alias = $manufacturer_alias;
    }

    /**
     * @return mixed
     */
    public function getManufacturerCountry()
    {
        return $this->_manufacturer_country;
    }

    /**
     * @param mixed $manufacturer_country
     */
    public function setManufacturerCountry($manufacturer_country)
    {
        $this->_manufacturer_country = $manufacturer_country;
    }


    /**
     * @return mixed
     */
    public function getManufacturerImg()
    {
        return $this->_manufacturer_img;
    }

    /**
     * @param mixed $manufacturer_img
     */
    public function setManufacturerImg($manufacturer_img)
    {
        $this->_manufacturer_img = $manufacturer_img;
    }

    /**
     * @return mixed
     */
    public function getCountryIco()
    {
        return $this->_country_ico;
    }

    /**
     * @param mixed $country_ico
     */
    public function setCountryIco($country_ico)
    {
        $this->_country_ico = $country_ico;
    }


    /**
     * @return mixed
     */
    public function getMeasurementId()
    {
        return $this->_measurement_id;
    }

    /**
     * @param mixed $measurement_id
     */
    public function setMeasurementId($measurement_id)
    {
        $this->_measurement_id = $measurement_id;
    }

    /**
     * @return mixed
     */
    public function getMeasurementTitle()
    {
        return $this->_measurement_title;
    }

    /**
     * @param mixed $measurement_title
     */
    public function setMeasurementTitle($measurement_title)
    {
        $this->_measurement_title = $measurement_title;
    }

    /**
     * @return mixed
     */
    public function getMeasurementCode()
    {
        return $this->_measurement_code;
    }

    /**
     * @param mixed $measurement_code
     */
    public function setMeasurementCode($measurement_code)
    {
        $this->_measurement_code = $measurement_code;
    }

    /**
     * @return array
     */
    public function getPhoto()
    {
        return $this->_photo;
    }

    /**
     * @param array $photo
     */
    public function setPhoto($photo)
    {
        $this->_photo[] = $photo;
    }


    /**
     * @return array
     */
    public function getInstruction()
    {
        return $this->_instruction;
    }

    /**
     * @param array $instruction
     */
    public function setInstruction($instruction)
    {
        $this->_instruction[] = $instruction;
    }


    /**
     * @return array
     */
    public function getModelChildren()
    {
        return $this->_model_children;
    }

    /**
     * @param array $model_children
     */
    public function setModelChildren($model_children)
    {
        $this->_model_children[] = $model_children;
    }

    /**
     * @return array
     */
    public function getModelParent()
    {
        return $this->_model_parent;
    }

    /**
     * @param array $model_parent
     */
    public function setModelParent($model_parent)
    {
        $this->_model_parent[] = $model_parent;
    }

    /**
     * @return array
     */
    public function getComplectChildren()
    {
        return $this->_complect_children;
    }

    /**
     * @param array $complect_children
     */
    public function setComplectChildren($complect_children)
    {
        $this->_complect_children[] = $complect_children;
    }

    /**
     * @return array
     */
    public function getComplectParent()
    {
        return $this->_complect_parent;
    }

    /**
     * @param array $complect_parent
     */
    public function setComplectParent($complect_parent)
    {
        $this->_complect_parent[] = $complect_parent;
    }

    /**
     * @return array
     */
    public function getKitChildren()
    {
        return $this->_kit_children;
    }

    /**
     * @param array $kit_children
     */
    public function setKitChildren($kit_children)
    {
        $this->_kit_children[] = $kit_children;
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @param mixed $fields
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;
    }

    /**
     * @return mixed
     */
    public function getDelivery()
    {
        return $this->_delivery;
    }

    /**
     * @param mixed $delivery
     */
    public function setDelivery($delivery)
    {
        $this->_delivery = $delivery;
    }

    /**
     * @return mixed
     */
    public function getDiscounts()
    {
        return $this->_discounts;
    }

    /**
     * @param mixed $discounts
     */
    public function setDiscounts($discounts)
    {
        $this->_discounts = $discounts;
    }

    /**
     * @return mixed
     */
    public function getReviews()
    {
        return $this->_reviews;
    }

    /**
     * @param mixed $reviews
     */
    public function setReviews($reviews)
    {
        $this->_reviews = $reviews;
    }
}