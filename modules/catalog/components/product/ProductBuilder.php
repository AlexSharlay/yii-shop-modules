<?php
namespace common\modules\catalog\components\product;

class ProductBuilder
{

    /**
     * @var BuilderProductJson $_builderProduct
     */
    private $_builderProduct;

    public function setBuilderProduct(BuilderProduct $product) {
        $this->_builderProduct = $product;
    }


    public function constructProduct() {
        // Информация о товаре
        $this->_builderProduct->buildInfo();
        $this->_builderProduct->buildPhoto();
        $this->_builderProduct->buildInstruction();
        $this->_builderProduct->buildModelChildren();
        $this->_builderProduct->buildModelParent();
        $this->_builderProduct->buildComplectChildren();
        $this->_builderProduct->buildComplectParent();
        $this->_builderProduct->buildKitChildren();
        $this->_builderProduct->buildFields();
        // Скидочки
        $this->_builderProduct->buildDiscounts();
        // Доставка с учётом цены со скидкой
        $this->_builderProduct->buildDelivery();
        // Счётчик просмотров
        $this->_builderProduct->counter();
    }


    public function getProduct() {
        return $this->_builderProduct->getProductJson();
    }
}