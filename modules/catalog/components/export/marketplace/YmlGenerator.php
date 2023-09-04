<?php
namespace common\modules\catalog\components\export\marketplace;

use XMLWriter;

abstract class YmlGenerator  {

    public $encoding = 'windows-1251';

    public $outputFolder;

    public $indentString = "\t";

    public $shopInfoElements = ['name','company','url','platform','version','agency','email'];

    public $offerElements = array('url', 'price', 'currencyId', 'categoryId', 'market_category', 
            'picture', 'store', 'pickup', 'delivery', 'local_delivery_cost','typePrefix', 
            'vendor', 'vendorCode', 'name' ,'model', 'description', 'sales_notes', 'manufacturer_warranty',
            'seller_warranty','country_of_origin', 'downloadable', 'age','barcode','cpa',
            'rec','expiry','weight','dimensions','param');

    protected $_tmpFile;
    protected $_engine;
    
    public function run($marketplace, $currencies, $categories, $products) {

        $this->beforeWrite($marketplace);

        $this->writeShopInfo();
        $this->writeCurrencies($currencies);
        $this->writeCategories($categories);
        $this->writeOffers($products);
        
        $this->afterWrite();
    }

    protected function beforeWrite($marketplace)
    {
        $this->_tmpFile = $_SERVER['DOCUMENT_ROOT'] . '/statics/web/catalog/export/' . $marketplace . '.xml';

        $engine = $this->getEngine();
        $engine->openURI($this->_tmpFile);
        if ($this->indentString) {
            $engine->setIndentString($this->indentString);
            $engine->setIndent(true);
        }
        $engine->startDocument('1.0', $this->encoding);
        $engine->startElement('yml_catalog');
        $engine->writeAttribute('date', date('Y-m-d H:i'));
        $engine->startElement('shop');
    }
    
    protected function afterWrite() {
        $engine = $this->getEngine();
        $engine->fullEndElement();
        $engine->fullEndElement();
        $engine->endDocument(); 
        
        if (null !== $this->outputFolder)
            rename($this->_tmpFile, $this->outputFolder);
    }
    
    protected function getEngine() {
        if (null === $this->_engine) {
            $this->_engine = new XMLWriter();
        }
        return $this->_engine;
    }

    protected function writeShopInfo() {
        $engine = $this->getEngine();
        foreach($this->shopInfo() as $elm=>$text) {
            if (in_array($elm,$this->shopInfoElements)) {
                $engine->writeElement($elm, $text);
            }
        }
    }
    
    protected function writeCurrencies($currencies) {
        $engine = $this->getEngine();
        $engine->startElement('currencies');
        $this->currencies($currencies);
        $engine->fullEndElement();
    }
    
    protected function writeCategories($categories) {
        $engine = $this->getEngine();
        $engine->startElement('categories');
        $this->categories($categories);
        $engine->fullEndElement();
    }
    
    protected function writeOffers($products) {
        $engine = $this->getEngine();
        $engine->startElement('offers');
        $this->offers($products);
        $engine->fullEndElement();
    }
    
    /**
     * Adds <currency> element. (See http://help.yandex.ru/partnermarket/currencies.xml)
     * @param string $id "id" attribute 
     * @param mixed $rate "rate" attribute
     */
    protected function addCurrency($id,$rate = 1) {
        $engine = $this->getEngine();
        $engine->startElement('currency');
        $engine->writeAttribute('id', $id);
        $engine->writeAttribute('rate', $rate);
        $engine->endElement();
    }
    
    /**
     * Adds <category> element. (See http://help.yandex.ru/partnermarket/categories.xml)
     * @param string $name category name
     * @param int $id "id" attribute
     * @param int $parentId "parentId" attribute
     */
    protected function addCategory($name,$id,$parentId = null) {
        $engine = $this->getEngine();
        $engine->startElement('category');
        $engine->writeAttribute('id', $id);
        if ($parentId)
            $engine->writeAttribute('parentId', $parentId);
        $engine->text($name);
        $engine->fullEndElement();
    }
    
    /**
     * Adds <offer> element. (See http://help.yandex.ru/partnermarket/offers.xml)
     * @param int $id "id" attribute
     * @param array $data array of subelements as elementName=>value pairs
     * @param array $params array of <param> elements. Every element is an array: array(NAME,UNIT,VALUE) (See http://help.yandex.ru/partnermarket/param.xml)
     * @param boolean $available "available" attribute
     * @param string $type "type" attribute
     * @param numeric $bid "bid" attribute
     * @param numeric $cbid "cbid" attribute
     */
    protected function addOffer($id, $data, $params = [], $available = true, $type = 'vendor.model', $bid = null, $cbid = null) {
        $engine = $this->getEngine();
        $engine->startElement('offer');
        $engine->writeAttribute('id', $id);
        if ($type) 
            $engine->writeAttribute('type', $type);
        $engine->writeAttribute('available', $available ? 'true' : 'false');
        if ($bid) {
            $engine->writeAttribute('bid', $bid);
            if ($cbid) 
                $engine->writeAttribute('cbid', $cbid);
        }
        foreach($data as $elm=>$val) {
            if (in_array($elm,$this->offerElements)) {
                if (!is_array($val)) {
                    $val = array($val);
                }
                foreach($val as $value) {
                    $engine->writeElement($elm, $value);
                }
            }
        }
        foreach($params as $param) {
             $engine->startElement('param');
             $engine->writeAttribute('name', $param[0]);
             if ($param[1])
                 $engine->writeAttribute('unit', $param[1]);
             $engine->text($param[2]);
             $engine->endElement();
        }
        $engine->fullEndElement();



    }

    protected abstract function shopInfo();
    protected abstract function currencies($currencies);
    protected abstract function categories($categories);
    protected abstract function offers($products);

}
