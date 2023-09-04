<?php

namespace common\modules\blogs;

use Yii;

/**
 * Module [[Blogs]]
 * Yii2 blogs module.
 */
class Module extends \common\modules\base\components\Module
{
    /**
     * @inheritdoc
     */
    public static $name = 'blogs';

    /**
     * @var integer Posts per page
     */
    public $recordsPerPage = 10;

    /**
     * @var boolean Whether posts need to be moderated before publishing
     */
    public $moderation = true;

    /**
     * @var string Preview path
     */
    public $previewPath = '@statics/web/blogs/previews/';

    /**
     * @var string Image path
     */
    public $imagePath = '@statics/web/blogs/images/';

    /**
     * @var string Files path
     */
    public $filePath = '@statics/web/blogs/files';

    /**
     * @var string Files path
     */
    public $contentPath = '@statics/web/blogs/content';

    /**
     * @var string Images temporary path
     */
    public $imagesTempPath = '@statics/temp/blogs/images/';

    /**
     * @var string Preview URL
     */
    public $previewUrl = '/statics/blogs/previews';

    /**
     * @var string Image URL
     */
    public $imageUrl = '/statics/blogs/images';

    /**
     * @var string Files URL
     */
    public $fileUrl = '/statics/blogs/files';

    /**
     * @var string Files URL
     */
    public $contentUrl = '/statics/blogs/content';



    /**
     * @var string Image path
     */
    public $imagePathCategory = '@statics/web/blogs/category/';

    /**
     * @var string Images temporary path
     */
    public $imagesTempPathCategory = '@statics/temp/blogs/category/';

    /**
     * @var string Image URL
     */
    public $imageUrlCategory = '/statics/blogs/category';

    /**
     * @var string Files URL
     */
    public $contentUrlCategory = '/statics/blogs/content';

    /**
     * @var string Files path
     */
    public $contentPathCategory = '@statics/web/blogs/content';

    /**
     * @var string Files URL
     */
    public $fileUrlCategory = '/statics/blogs/files';

    /**
     * @var string Files path
     */
    public $filePathCategory = '@statics/web/blogs/files';


    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, $config = [])
    {
        if (!isset($config['viewPath'])) {
            if ($this->isBackend === true) {
                $config['viewPath'] = '@common/modules/blogs/views/backend';
            } else {
                $config['viewPath'] = '@common/modules/blogs/views/frontend';
            }
        }

        parent::__construct($id, $parent, $config);
    }
}
