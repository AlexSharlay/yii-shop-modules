<?php
namespace common\modules\catalog\components\export\marketplace;

class Builder
{

    /**
     * @var BuilderYandex, BuilderOnliner $_builderMarketplace
     */
    private $_builderMarketplace;

    public function setBuilder($marketplace) {
        $this->_builderMarketplace = $marketplace;
    }

    public function constructProduct() {
        $this->_builderMarketplace->createNewProduct();
    }

    public function getProduct($product) {
        $this->_builderMarketplace->build($product);
        return $this->_builderMarketplace->getProduct();
    }

}