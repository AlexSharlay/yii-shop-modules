<?php
namespace common\modules\catalog\components\product;

abstract class BuilderProduct
{
    /**
     * @var Product $_product
     */
    protected $_product;

    public function createNewProduct($id, $id_kit) {
        $this->_product = new Product($id, $id_kit);
    }


}