<?php
namespace common\modules\catalog\components\import\excell;

use Yii;
use yii\web\UploadedFile;
//use yii\base\HttpException;
use yii\web\HttpException;
use common\modules\catalog\models\backend\Photo;
use common\modules\catalog\models\backend\PhotoInsert;
use common\modules\catalog\models\backend\Element;


class ImportFotoKranik
{

    public function run()
    {
        // Получаем коды 1С без картинок
        $products = Element::find()
            ->select('code_1c')
            ->from('{{%catalog_element}} ce')
            ->leftJoin('{{%catalog_photo}} cf', 'cf.id_element = ce.id')
            ->where('cf.id IS NULL AND ce.code_1c IS NOT NULL')
            //->createCommand()->sql;
            ->asArray()->all();

        // Формируем нормальный массив
        $codes = array_column($products,'code_1c');

        // Посылаем запрос на краник для получения картинок
        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query(['codes'=>$codes]),
            ],
        ];
        $context  = stream_context_create($options);
        $products = file_get_contents('http://old.kranik.by/module/integration/photo.php', false, $context);


        if ($products === FALSE) {
            throw new HttpException(400, 'Ответ от old.kranik.by не получен.');
        }

        $products = json_decode($products);

        // Скачиваем фотки и добавляем товарам
        $count = [
            'products' => 0,
            'photos' => 0
        ];


        foreach($products as $key=>$product) {

            $code_1c = $product->code_1c;
            $images = $product->images;

            if (is_array($images) && count($images) > 0) {
                $id_element = Element::find()->select('id')->where('code_1c = :code_1c',[':code_1c'=>$code_1c])->asArray()->one()['id'];


                if (is_null($id_element)) {
                    $id_element = Element::find()->select('id')->where('article = :article',[':article'=>$code_1c])->asArray()->one()['id'];
                }
                unset($_FILES);
                if (!is_null($id_element)) {
                    foreach ($images as $image) self::addToFiles($image);

//                    Element::updateElement($id_element, $key);
                    Element::updateElement($id_element);

                    $count['photos'] += count($images);
                    $count['products']++;
                }
            }

        }

        return $count;
    }

    /**
     * Add to $_FILES from external url
     * sample usage: addToFiles('http://google.com/favicon.ico');
     * @param string $url sample http://some.tld/path/to/file.ext
     */
    public function addToFiles($url)
    {
        $tempName = tempnam('/tmp', 'php_files');
        $originalName = basename(parse_url($url, PHP_URL_PATH));

        $url = 'https://kranik.by' . str_replace(' ', '%20', $url);

        if(self::get_http_response_code($url) != "200"){
            echo "error: ".$url."<br/>";
        } else {
            $imgRawData = file_get_contents($url);
            if (file_put_contents($tempName, $imgRawData)) {
                $_FILES['Photo']['name']['file'][] = $originalName;
                $_FILES['Photo']['type']['file'][] = mime_content_type($tempName);
                $_FILES['Photo']['tmp_name']['file'][] = $tempName;
                $_FILES['Photo']['error']['file'][] = 0;
                $_FILES['Photo']['size']['file'][] = strlen($imgRawData);
            } else {
                echo "Не загрузилось фото: " . $originalName . "<br/>";
            }
        }
    }

    public function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

}
