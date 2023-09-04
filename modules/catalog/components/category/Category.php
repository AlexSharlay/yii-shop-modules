<?php
namespace common\modules\catalog\components\category;

class Category
{
    private $_query;
    private $_category;
    private $_mfr;
    private $_price = [];
    private $_group = 0;
    private $_page = 1;
    private $_limit = 80;
    private $_offset = 0;
    private $_fields = [];
    private $_where = [];
    private $_sql_order = 'hit:desc';
//    private $_sql_order = 'price:asc';
    private $_sql_manufacturer = '';
    private $_sql_price = '';
    private $_products = [];
    private $_result =
        [
            'total' => 0,
            'page' =>
                [
                    'limit' => 0,
                    'items' => 0,
                    'current' => 0,
                    'last' => 0,
                ],
            'products' => [],
        ];

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query)
    {
        $this->_query = $query;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->_category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->_category = $category;
    }

    /**
     * @return array
     */
    public function getMfr()
    {
        return $this->_mfr;
    }

    /**
     * @param array $mfr
     */
    public function setMfr($mfr)
    {
        $this->_mfr = $mfr;
    }

    /**
     * @return array
     */
    public function getPrice()
    {
        return $this->_price;
    }

    /**
     * @param array $price
     */
    public function setPrice($price)
    {
        $this->_price = $price;
    }

    /**
     * @return int
     */
    public function getGroup()
    {
        return $this->_group;
    }

    /**
     * @param int $group
     */
    public function setGroup($group)
    {
        $this->_group = $group;
    }

    /**
     * @return array
     */
    public function getWhere()
    {
        return $this->_where;
    }

    /**
     * @param array $sql_where
     */
    public function setWhere($where)
    {
        $this->_where = $where;
    }

    /**
     * @return string
     */
    public function getSqlOrder()
    {
        return $this->_sql_order;
    }

    /**
     * @param string $sql_order
     */
    public function setSqlOrder($sql_order)
    {
        $this->_sql_order = $sql_order;
    }

    /**
     * @return array
     */
    public function getSqlManufacturer()
    {
        return $this->_sql_manufacturer;
    }

    /**
     * @param array $sql_manufacturer
     */
    public function setSqlManufacturer($sql_manufacturer)
    {
        $this->_sql_manufacturer = $sql_manufacturer;
    }

    /**
     * @return string
     */
    public function getSqlPrice()
    {
        return $this->_sql_price;
    }

    /**
     * @param string $sql_price
     */
    public function setSqlPrice($sql_price)
    {
        $this->_sql_price = $sql_price;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->_page = $page;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->_limit = $limit;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->_offset = $offset;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;
    }

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->_products;
    }

    /**
     * @param array $products
     */
    public function setProducts($products)
    {
        $this->_products = $products;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * @param array $result
     */
    public function setResult($result)
    {
        $this->_result = $result;
    }

}
