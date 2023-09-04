<?php

namespace common\modules\catalog;

use Yii;

/**
 * Module [[Catalog]]
 * Yii2 catalog module.
 */
class Module extends \common\modules\base\components\Module
{

    public static $name = 'catalog';

    /*
    public $previewPath = '@statics/web/catalog/previews/';
    //public $previewPath = '@statics/web/blogs/previews/';
    public $imagesTempPath = '@statics/temp/catalog/images/';
    //public $imagesTempPath = '@statics/temp/blogs/images/';
    public $previewUrl = '/statics/catalog/previews';
    //public $previewUrl = '/statics/blogs/previews';
    public $imagePath = '@statics/web/catalog/images/';
    //public $imagePath = '@statics/web/blogs/images/';
    public $imageUrl = '/statics/catalog/images';
    //public $imageUrl = '/statics/blogs/images';
    */

    /*
     *  Категории
     */

    // Fileapi - картинки
    public $categoryTempPath = '@statics/temp/catalog/category/images';
    public $categoryPath = '@statics/web/catalog/category/images';
    public $categoryUrl = '/statics/catalog/category/images';

    // Imperavi - картинки
    public $contentUrl = '/statics/catalog/category/content';
    public $contentPath = '@statics/web/catalog/category/content';

    // Imperavi - файлы
    public $fileUrl = '/statics/catalog/category/files';
    public $filePath = '@statics/web/catalog/category/files';

    /*
     *  Страны
     */

    // Fileapi - картинки
    public $countryTempPath = '@statics/temp/catalog/country/images';
    public $countryPath = '@statics/web/catalog/country/images';
    public $countryUrl = '/statics/catalog/country/images';

    /*
     *  Производители
     */

    // Fileapi - картинки
    public $manufacturerTempPath = '@statics/temp/catalog/manufacturer/images';
    public $manufacturerPath = '@statics/web/catalog/manufacturer/images';
    public $manufacturerUrl = '/statics/catalog/manufacturer/images';

    // Imperavi - картинки
    public $contentManufacturerUrl = '/statics/catalog/manufacturer/content';
    public $contentManufacturerPath = '@statics/web/catalog/manufacturer/content';

    // Imperavi - файлы
    public $fileManufacturerUrl = '/statics/catalog/manufacturer/files';
    public $fileManufacturerPath = '@statics/web/catalog/manufacturer/files';

    /*
     *  Товары
     */

    // Fileapi - картинки
    public $elementTempPath = '@statics/temp/catalog/element/images';
    public $elementPath = '@statics/web/catalog/element/images';
    public $elementUrl = '/statics/catalog/element/images';

    // Imperavi - картинки
    public $contentElementUrl = '/statics/catalog/element/content';
    public $contentElementPath = '@statics/web/catalog/element/content';

    // Imperavi - файлы
    public $fileElementUrl = '/statics/catalog/element/files';
    public $fileElementPath = '@statics/web/catalog/element/files';

    /*
     *  Коллекции
     */

    // Imperavi - картинки
    public $contentCollectionUrl = '/statics/catalog/collection/content';
    public $contentCollectionPath = '@statics/web/catalog/collection/content';

    // Imperavi - файлы
    public $fileCollectionUrl = '/statics/catalog/collection/files';
    public $fileCollectionPath = '@statics/web/catalog/collection/files';

    public function __construct($id, $parent = null, $config = [])
    {

        if (!isset($config['viewPath'])) {
            if ($this->isBackend === true) {
                $config['viewPath'] = '@common/modules/catalog/views/backend';
            } else {
                $config['viewPath'] = '@common/modules/catalog/views/frontend';
            }
        }

        parent::__construct($id, $parent, $config);
    }

}
