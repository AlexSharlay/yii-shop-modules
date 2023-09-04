<?php

namespace common\modules\shop;

use Yii;

/**
 * Module [[Shop]]
 * Yii2 shop module.
 */
class Module extends \common\modules\base\components\Module
{
    /**
     * @inheritdoc
     */
    public static $name = 'shop';

    private $_isBackend;

    /*
     *  Доставка
     */

    public $contentPaymentUrl = '/statics/shop/payment/content';
    public $contentPaymentPath = '@statics/web/shop/payment/content';

    public $filePaymentUrl = '/statics/shop/payment/files';
    public $filePaymentPath = '@statics/web/shop/payment/files';

    /*
     *  Оплата
     */

    public $contentDeliveryUrl = '/statics/shop/delivery/content';
    public $contentDeliveryPath = '@statics/web/shop/delivery/content';

    public $fileDeliveryUrl = '/statics/shop/delivery/files';
    public $fileDeliveryPath = '@statics/web/shop/delivery/files';

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, $config = [])
    {
        if (!isset($config['viewPath'])) {
            if ($this->isBackend === true) {
                $config['viewPath'] = '@common/modules/shop/views/backend';
            } else {
                $config['viewPath'] = '@common/modules/shop/views/frontend';
            }
        }

        parent::__construct($id, $parent, $config);
    }
}
