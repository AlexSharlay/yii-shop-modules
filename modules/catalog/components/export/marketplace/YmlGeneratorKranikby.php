<?php
namespace common\modules\catalog\components\export\marketplace;

class YmlGeneratorKranikby extends YmlGenerator {

    protected function shopInfo() {
        return [
            'name'=>'Kranik.by',
            'company'=>'ООО Сантехпром',
            'url'=>'http://kranik.by',
        ];
    }

    protected function currencies($currencies) {
        foreach($currencies as $currecy => $rate) {
            $this->addCurrency($currecy, $rate);
        }
    }

    protected function categories($categories) {
        foreach($categories as $category) {
            $this->addCategory($category['title'], $category['id'], $category['id_parent']);
        }
    }

    protected function offers($products) {
        foreach($products as $product) {
            $idOffer = $product['id'];
            unset($product['id']);
            $this->addOffer($idOffer, $product);
        }
    }

}
