<?php
namespace common\modules\catalog\components\category;

class CategoryBuilder
{
    /**
     * @var CategoryPage $_builderCategory
     */
    private $_builderCategory;

    public function setBuilderCategory(CategoryAbstract $category) {
        $this->_builderCategory = $category;
    }

    public function constructCategory() {
        $this->_builderCategory->buildQuery();
        $this->_builderCategory->buildCategory();
        $this->_builderCategory->buildManufacturer();
        $this->_builderCategory->buildPrice();
        $this->_builderCategory->buildGroup();
        $this->_builderCategory->buildPageAndOffset();
        $this->_builderCategory->buildSqlOrder();
        $this->_builderCategory->buildSqlManufacturer();
        $this->_builderCategory->buildSqlPrice();
        $this->_builderCategory->buildFields();
        $this->_builderCategory->buildWhere();
        $this->_builderCategory->buildProductsGroup();
        $this->_builderCategory->buildDiscounts();
        $this->_builderCategory->buildPagination();
        $this->_builderCategory->buildProducts();
    }


    public function getCategory() {
        return $this->_builderCategory->getCategory();
    }
}