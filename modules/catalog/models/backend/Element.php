<?php
namespace common\modules\catalog\models\backend;

//use common\modules\catalog\components\category\Category;
use common\modules\catalog\components\Convert;
use Yii;
use yii\db\Query;
use yii\web\UploadedFile;
use yii\web\HttpException;
use Imagine\Gd\Imagine;

setlocale(LC_NUMERIC, "C");

class Element extends \common\modules\catalog\models\Element
{

    private $_created;
    private $_updated;

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->_created) {
                $this->created_at = Yii::$app->formatter->asTimestamp($this->_created);
            }
            if ($this->_updated) {
                $this->updated_at = Yii::$app->formatter->asTimestamp($this->_updated);
            }
            return true;
        } else {
            return false;
        }
    }

    public function getDateCreate()
    {
        if (!$this->isNewRecord && $this->_created === null) {
            $this->_created = Yii::$app->formatter->asDate($this->created_at);
        }
        return $this->_created;
    }

    public function setDateCreate($value)
    {
        $this->_created = $value;
    }

    public function getDateUpdate()
    {
        if (!$this->isNewRecord && $this->_updated === null) {
            $this->_updated = Yii::$app->formatter->asDate($this->updated_at);
        }
        return $this->_updated;
    }

    public function setUpdatedAtJui($value)
    {
        $this->_updated = $value;
    }

    public function getCollectionRels()
    {
        return $this->hasMany(CollectionRel::className(), ['id_element' => 'id']);
    }

    public static function updateElement($id_element)
    {

        if (isset($_FILES) && count($_FILES)) {

            // Поиск есть ли главное изображение
            $photo = Photo::findOne(['id_element' => $id_element, 'is_cover' => '1']);
            (!is_null($photo) && $photo->id > 0) ? $is_cover = 0 : $is_cover = 1;

            // Поиск максимальной сортировки файла, если есть
            $sort = Photo::find()->andWhere(['id_element' => $id_element])->select('id')->max('sort');
            if ($sort == 0) $sort = 1; else $sort++;

            $model = new Photo();
            $model->file = UploadedFile::getInstances($model, 'file');
            UploadedFile::reset();

            foreach ($model->file as $file) {

                $name = uniqid() . '.' . $file->extension;

                // Сохраняем на диск
                if ($file->saveAs(Yii::getAlias('@statics') . '/web/catalog/photo/images/' . $name)) {

///////////////////////////////// -images_small
                    $imagine = new Imagine();
                    $image = $imagine->open(Yii::getAlias('@statics') . '/web/catalog/photo/images/' . $name)->thumbnail(new \Imagine\Image\Box(223, 200))->save(Yii::getAlias('@statics') . '/web/catalog/photo/images_small/' . $name);


                    //chmod(Yii::getAlias('@statics') . '/web/catalog/photo/images/'.$name,0755);

                    // Подготавливаем сохранить в бд
                    $modelPhoto = new PhotoInsert();
                    $modelPhoto->id_element = $id_element;
                    $modelPhoto->name = $name;
                    $modelPhoto->sort = $sort;
                    $modelPhoto->is_cover = $is_cover;

                    // Сброс cover
                    $is_cover = 0;

                    // Сохранили
                    if (!$modelPhoto->save()) {
                        throw new HttpException('Фото не сохранилось в бд');
                    }
                } else {
                    throw new HttpException('Фото не сохранилось на диск');
                }

                $sort++;
            }
        }
        return $id_element;
    }

    public function createElement($modelElement, $modelPhoto)
    {
        $id_element = Yii::$app->db->getLastInsertID();
        $files = UploadedFile::getInstances($modelPhoto, 'file');
        if (isset($files) && count($files)) {
            $sort = 1;
            $is_cover = 1;

            // Перебор файлов
            foreach ($files as $file) {

                // Подготавливаем фото
                $modelPhoto = new Photo();
                $modelPhoto->file = $file;
                $name = uniqid() . '.' . $modelPhoto->file->extension;

                // Проверяем
                if ($modelPhoto->validate()) {

                    // Сохраняем на диск
                    if ($modelPhoto->file->saveAs(Yii::getAlias('@statics') . '/web/catalog/photo/images/' . $name)) {

                        ///////////////////////////////// -images_small
                    $imagine = new Imagine();
                    $image = $imagine->open(Yii::getAlias('@statics') . '/web/catalog/photo/images/' . $name)->thumbnail(new \Imagine\Image\Box(223, 200))->save(Yii::getAlias('@statics') . '/web/catalog/photo/images_small/' . $name);


                        // Подготавливаем сохранить в бд
                        $modelPhoto = new PhotoInsert();
                        $modelPhoto->id_element = $id_element;
                        $modelPhoto->name = $name;
                        $modelPhoto->sort = $sort;
                        $modelPhoto->is_cover = $is_cover;

                        // Сброс cover
                        $is_cover = 0;

                        // Сохранили
                        if (!$modelPhoto->save()) {
                            throw new HttpException('Ошибка сохранения имени файла в бд');
                        }
                    } else {
                        throw new HttpException('Ошибка сохранения файла на диск');
                    }
                } else {
                    foreach ($modelPhoto->getErrors('file') as $error) {
                        $modelPhoto->addError('file', $error);
                    }
                }
                if ($modelPhoto->hasErrors('file')) {
                    $modelPhoto->addError(
                        'file',
                        count($modelPhoto->getErrors('file')) . ' of ' . count($files) . ' files not uploaded'
                    );
                }
                $sort++;
            }
        }
        return $id_element;
    }

    public function coverSet($id)
    {
        // Узнать к какому товару относится картинка
        $element = PhotoInsert::find()->andWhere(['id' => $id])->select('id_element')->one();
        // Найти картинку-обложку к этому товару и снять с неё пометку обложки
        $foto = PhotoInsert::find()->andWhere(['id_element' => $element->id_element, 'is_cover' => 1])->select('id')->one();
        $foto->is_cover = 0;
        $foto->update();
        // Выставить is_cover = 1 элементу с id = $id
        $fotoNewCover = PhotoInsert::find()->andWhere(['id' => $id])->select('id')->one();
        $fotoNewCover->is_cover = 1;
        $fotoNewCover->update();
    }

    public function coverSort($json)
    {
        $arrSort = json_decode($json);
        // Узнаём id товара
        $photo = PhotoInsert::find()->andWhere(['id' => $arrSort['0']])->select('id_element')->one();
        $id_element = $photo->id_element;
        // Обновить сортировку
        $ids = PhotoInsert::find()->andWhere(['id_element' => $id_element])->select('id')->all();
        foreach ($ids as $id) {
            $sort = array_search($id->id, $arrSort) + 1;
            echo '$sort:' . $sort . ', $id->id:' . $id->id . ' | ';
            $f = PhotoInsert::find()->andWhere(['id' => $id->id])->select('id,sort')->one();
            $f->sort = $sort;
            $f->update();
        }
    }

    public function deletePhoto($id)
    {
        $foto = PhotoInsert::find()->andWhere(['id' => $id])->select('id_element, name, sort, is_cover')->one();
        $id_element = $foto->id_element;
        $name = $foto->name;
        $sortDef = $sort = $foto->sort;
        $is_cover = $foto->is_cover;
        // Удаление из базы
        $fotoDelete = PhotoInsert::find()->andWhere(['id' => $id])->select('id')->one();
        $fotoDelete->delete();
        // Удаление с диска
        $path = $_SERVER['DOCUMENT_ROOT'] . '/statics/web/catalog/photo/images/';
        $path_small = $_SERVER['DOCUMENT_ROOT'] . '/statics/web/catalog/photo/images_small/';
        if (is_file($path . $name)) {
            unlink($path . $name);
            ///////////////////////////////// -images_small
            unlink($path_small . $name);
        }
        // Обновить сортировку
        $ids = PhotoInsert::find()->andWhere(['id_element' => $id_element])->andWhere('sort > :sort', [':sort' => $sort])->select('id')->orderBy(['sort' => SORT_ASC])->all();
        foreach ($ids as $id) {
            $f = PhotoInsert::find()->andWhere(['id' => $id->id])->select('id,sort')->one();
            $f->sort = $sort;
            if ($is_cover == 1 && $sortDef == $sort) $f->is_cover = 1; // Если удалили обложку
            $f->update();
            $sort++;
        }
    }


    public static function FieldsToJson($id)
    {

        // По id товара узнать id категории
        $category = (new Query())
            ->select('c.id, c.alias, c.parent_filter, c2.id AS id2, c2.alias AS alias2')
            ->from('{{%catalog_element}} e')
            ->leftJoin('{{%catalog_category}} c', 'e.id_category = c.id')
            ->leftJoin('{{%catalog_category}} c2', 'c.id_parent = c2.id')
            ->where(['e.id' => $id])
            ->one();

        if (isset($category['id2']) AND isset($category['alias2']) AND $category['parent_filter'] == 1) {
            $category['id'] = $category['id2'];
            $category['alias'] = $category['alias2'];
        }

//        if (in_array($category['id'], array(93,94,95,96,97,98))) { ////////временно, парсинг
//            $category['id'] = 5;////////////////////////
//            $category['alias'] = 'vanny';////////////////////////
//        }
//
//        if (in_array($category['id'], array(99,100,101,102,103))) { ////////временно, парсинг
//            $category['id'] = 26;////////////////////////
//            $category['alias'] = 'unitazy';////////////////////////
//        }
//
//        if (in_array($category['id'], array(120,121))) { ////////временно, парсинг
//            $category['id'] = 29;////////////////////////
//            $category['alias'] = 'bide';////////////////////////
//        }
//
//        if (in_array($category['id'], array(122,123,124,125,126,127))) { ////////временно, парсинг
//            $category['id'] = 18;////////////////////////
//            $category['alias'] = 'installyacii';////////////////////////
//        }
//        if (in_array($category['id'], array(180,181,182,183))) { ////////временно, парсинг
//            $category['id'] = 23;////////////////////////
//            $category['alias'] = 'moyki-kuhonnye';////////////////////////
//        }
//        if (in_array($category['id'], array(184,185,186,187))) { ////////временно, парсинг
//            $category['id'] = 14;////////////////////////
//            $category['alias'] = 'dushevye-garnitury-i-paneli';////////////////////////
//        }
//        if (in_array($category['id'], array(188,189,190,191,192))) { ////////временно, парсинг
//            $category['id'] = 32;////////////////////////
//            $category['alias'] = 'smesiteli-i-komplektuyuschie';////////////////////////
//        }
//        if (in_array($category['id'], array(228,229))) { ////////временно, парсинг
//            $category['id'] = 20;////////////////////////
//            $category['alias'] = 'polotencesushiteli';////////////////////////
//        }


        if (mb_strpos($category['alias'], '_collection') && $collection = \common\modules\catalog\models\frontend\Category::find()->select('id')->andWhere(['alias' => mb_substr($category['alias'], 0, -11)])->one()['id']) {
            $id_category = $category['id'];;
        } else {
            $id_category = $category['id'];
        }

        $arrFieldsAll = FieldGroup::find()
            ->select('fg.title as group_title, f.*')
            ->from('tbl_catalog_field_group fg')
            ->innerJoin('tbl_catalog_field f', 'f.id_group = fg.id')
            ->andWhere(['fg.id_category' => $id_category])
            ->orderBy('fg.sort ASC, f.sort ASC')
            //->createCommand()->getRawSql();
            ->asArray()->all();

        $arrFields = Element::find()
            ->select('fg.title as group_title, f.name as field_title, f.unit as field_unit, f.variant as field_variant, f.type as field_type, f.dop AS field_dop,
                fv.value as value_value, fv.text as value_text, fv.dop as value_dop')
            ->from('tbl_catalog_field_group fg')
            ->leftJoin('tbl_catalog_field f', 'f.id_group = fg.id')
            ->leftJoin('tbl_catalog_field_element_value_rel fev', 'fev.id_field = f.id')
            ->innerJoin('tbl_catalog_field_value fv', 'fev.id_value = fv.id')
            ->andWhere(['fev.id_element' => $id])
            ->orderBy('fg.sort ASC, f.sort ASC')
            //->createCommand()->getRawSql();
            ->asArray()->all();

        $arr = self::formatFields($arrFieldsAll, $arrFields);
        $arr = self::formatFieldsValue($arr);
        $arr = self::formatFieldsClear($arr);

        return $arr;
    }

    public static function formatFields($arrFieldsAll, $arrFields)
    {
        $arr = [];

        // Создаём группы
        $groups = [];
        foreach ($arrFieldsAll as $field) {
            // Имя группы
            if (!in_array($field['group_title'], $groups)) {
                $arr[]['name'] = $field['group_title'];
                $groups[] = $field['group_title'];
            }
        }
        unset($groups);

        // Создаём поля
        $fields = [];
        foreach ($arrFieldsAll as $field) {
            foreach ($arr as $k => $a) {
                if ($a['name'] == $field['group_title']) {
                    if (!in_array($field['name_filter'], $fields)) {
                        if ($field['type'] == 1) {

                            $check_var = unserialize($field['dop'])['check_var'];

                            if ($check_var == 'with_one') {
                                $arr[$k]['fields'][] = [
                                    'name' => $field['name_filter'],
                                    'field' => [
                                        'type' => $field['type'],
                                        'type_dop' => unserialize($field['dop'])['check_var'],
                                        'sign' => $field['unit'],
                                        'check' => [
                                            [
                                                'val' => 'none',
                                                'active' => 1,
                                            ],
                                            [
                                                'val' => 'Есть',
                                                'active' => 0,
                                            ],
                                            [
                                                'val' => 'Нет',
                                                'active' => 0,
                                            ],
                                        ],
                                    ],
                                ];
                            } else {
                                $arr[$k]['fields'][] = [
                                    'name' => $field['name_filter'],
                                    'field' => [
                                        'type' => $field['type'],
                                        'sign' => $field['unit'],
                                        'type_dop' => unserialize($field['dop'])['check_var']
                                    ],
                                ];
                            }
                        } else if ($field['type'] == 6) {
                            $arr[$k]['fields'][] = [
                                'name' => $field['name_filter'],
                                'field' => [
                                    'type' => $field['type'],
                                    'sign' => $field['unit'],
                                    'delemiter' => unserialize($field['dop'])['razdelitel'],
                                ],
                            ];
                        } else if ($field['type'] == 3) {
                            $arr[$k]['fields'][] = [
                                'name' => $field['name_filter'],
                                'field' => [
                                    'type' => $field['type'],
                                    'sign' => $field['field_unit'],
                                    'variants' => unserialize($field['variant']),
                                ],
                            ];

                        } else {
                            $arr[$k]['fields'][] = [
                                'name' => $field['name_filter'],
                                'field' => [
                                    'type' => $field['type'],
                                    'sign' => $field['unit'],
                                ],
                            ];
                        }
                        $fields[] = $field['name_filter'];
                    }
                }
            }
        }
        unset($fields);

        // Заполняем значения полей
        foreach ($arrFields as $field) {
            foreach ($arr as $k => $a) {
                if ($a['name'] == $field['group_title']) {
                    foreach ($a['fields'] as $k_f => $a_f) {
                        if ($a_f['name'] == $field['field_title']) {

                            $arr[$k]['fields'][$k_f]['field']['text'] = $field['value_text'];

                            if ($field['field_type'] == 5) {
                                $arr[$k]['fields'][$k_f]['field']['value'] = $field['value_text'];
                            }

                            // Значения полей, но пока не обработанные
                            if ($field['field_type'] == 1) {
                                $arr[$k]['fields'][$k_f]['field']['check_'][] = [
                                    'value' => (float)$field['value_value'],
                                    'dop' => $field['value_dop'],
                                ];
                                $check_var = unserialize($field['field_dop'])['check_var'];
                                $arr[$k]['fields'][$k_f]['field']['check_var'] = $check_var;
                            } else if ($field['field_type'] == 3) {
                                $arr[$k]['fields'][$k_f]['field']['check_'][] = [
                                    'value' => (float)$field['value_value'],
                                    'dop' => $field['value_dop'],
                                ];
                            } else if ($field['field_type'] == 6) {
                                $arr[$k]['fields'][$k_f]['field']['check_'][] = [
                                    'value' => (float)$field['value_value'],
                                    'dop' => $field['value_dop'],
                                ];
                            }

                        }
                    }
                }
            }
        }

        return $arr;
    }

    public static function formatFieldsValue($arr)
    {

        foreach ($arr as $key_group => $group) {
            foreach ($group['fields'] as $key_field => $field) {
                $type = $field['field']['type'];
                $variants = $field['field']['variants'];

                if ($type == 1) {
                    $check_var = $field['field']['check_var'];

                    if (in_array($check_var, ['default', 'mass', 'kb'])) {
                        $arr[$key_group]['fields'][$key_field]['field']['value'] = $field['field']['check_']['0']['value'];
                    } else if ($check_var == 'time') {
                        foreach ($arr[$key_group]['fields'][$key_field]['field']['check_'] as $f) {
                            $arr[$key_group]['fields'][$key_field]['field']['value'][($f['dop'] == 1) ? 'one' : 'two'] = $f['value'];
                        }
                    } else if ($check_var == 'with_one') {
                        $temp = [];
                        $dop = null;
                        $dopValue = null;

                        foreach ($arr[$key_group]['fields'][$key_field]['field']['check_'] as $f) {
                            if ($f['dop'] == 0 && !is_null($f['dop'])) {
                                $dop = 0;
                            } else if ($f['dop'] == 1) {
                                $dop = 1;
                                $dopValue = $f['value'];
                            } else {
                                $temp[] = $f['value'];
                            }
                        }

                        $arr[$key_group]['fields'][$key_field]['field']['value'] = implode(',', $temp);
                        $arr[$key_group]['fields'][$key_field]['field']['check'] = [
                            [
                                'val' => 'none',
                                'active' => (is_null($dop)) ? 1 : 0,
                            ],
                            [
                                'val' => 'Есть',
                                'active' => ($dop == 1 && $dopValue == 1) ? 1 : 0,
                            ],
                            [
                                'val' => 'Нет',
                                'active' => ($dop == 1 && $dopValue == 0) ? 1 : 0,
                            ],
                        ];

                    }
                } else if ($type == 3) {
                    $vals = [];
                    foreach ($field['field']['check_'] as $value) {
                        $vals[] = (float)$value['value'];
                    }

                    foreach ($variants as $variant) {
                        if (in_array($variant['db_val'], $vals)) {
                            $arr[$key_group]['fields'][$key_field]['field']['check'][] = [
                                'id' => $variant['db_val'],
                                'name' => $variant['title'],
                                'active' => 1,
                            ];
                            $vals[] = $variant['db_val'];
                        } else {
                            $arr[$key_group]['fields'][$key_field]['field']['check'][] = [
                                'id' => $variant['db_val'],
                                'name' => $variant['title'],
                                'active' => 0,
                            ];
                        }
                    }
                } else if ($type == 5) {
                    //none
                } else if ($type == 6) {
                    foreach ($field['field']['check_'] as $value) {
                        if ($value['dop'] == 1) {
                            $arr[$key_group]['fields'][$key_field]['field']['value']['one'] = $value['value'];
                        } else if ($value['dop'] == 2) {
                            $arr[$key_group]['fields'][$key_field]['field']['value']['two'] = $value['value'];
                        }
                    }
                }
            }
        }

        return $arr;
    }

    public static function formatFieldsClear($arr)
    {
        foreach ($arr as $key_group => $group) {
            foreach ($group['fields'] as $key_field => $field) {

                unset($arr[$key_group]['fields'][$key_field]['field']['variants']);
                unset($arr[$key_group]['fields'][$key_field]['field']['check_']);
                unset($arr[$key_group]['fields'][$key_field]['field']['check_var']);

                $type = $field['field']['type'];

                if ($type == 1) {
                    $check_var = $field['field']['type_dop'];

                    if (in_array($check_var, ['default', 'mass', 'kb'])) {
                        // Для ko
                        if (!array_key_exists('sign', $arr[$key_group]['fields'][$key_field]['field']))
                            $arr[$key_group]['fields'][$key_field]['field']['sign'] = null;
                        if (!array_key_exists('text', $arr[$key_group]['fields'][$key_field]['field']))
                            $arr[$key_group]['fields'][$key_field]['field']['text'] = null;
                        if ($check_var != 'kb' && !array_key_exists('value', $arr[$key_group]['fields'][$key_field]['field']))
                            $arr[$key_group]['fields'][$key_field]['field']['value'] = null;
                    } else if ($check_var == 'time') {
                        foreach ($arr[$key_group]['fields'][$key_field]['field']['check_'] as $f) {
                            $arr[$key_group]['fields'][$key_field]['field']['value'][($f['dop'] == 1) ? 'one' : 'two'] = $f['value'];
                        }
                        // Для ko
                        if (!array_key_exists('one', $arr[$key_group]['fields'][$key_field]['field']['value']))
                            $arr[$key_group]['fields'][$key_field]['field']['value']['one'] = null;
                        if (!array_key_exists('two', $arr[$key_group]['fields'][$key_field]['field']['value']))
                            $arr[$key_group]['fields'][$key_field]['field']['value']['two'] = null;
                    }

                } else if ($type == 3) {
                    // Для ko
                    if (!array_key_exists('text', $arr[$key_group]['fields'][$key_field]['field']))
                        $arr[$key_group]['fields'][$key_field]['field']['text'] = null;
                    if (!array_key_exists('sign', $arr[$key_group]['fields'][$key_field]['field']))
                        $arr[$key_group]['fields'][$key_field]['field']['sign'] = null;
                } else if ($type == 5) {
                    // Для ko
                    if (!array_key_exists('text', $arr[$key_group]['fields'][$key_field]['field']))
                        $arr[$key_group]['fields'][$key_field]['field']['text'] = null;
                    if (!array_key_exists('sign', $arr[$key_group]['fields'][$key_field]['field']))
                        $arr[$key_group]['fields'][$key_field]['field']['sign'] = null;
                    if (!array_key_exists('value', $arr[$key_group]['fields'][$key_field]['field']))
                        $arr[$key_group]['fields'][$key_field]['field']['value'] = null;
                } else if ($type == 6) {
                    foreach ($field['field']['check_'] as $value) {
                        if ($value['dop'] == 1) {
                            $arr[$key_group]['fields'][$key_field]['field']['value']['one'] = $value['value'];
                        } else if ($value['dop'] == 2) {
                            $arr[$key_group]['fields'][$key_field]['field']['value']['two'] = $value['value'];
                        }
                    }
                    // Для ko
                    if (!array_key_exists('one', $arr[$key_group]['fields'][$key_field]['field']['value']))
                        $arr[$key_group]['fields'][$key_field]['field']['value']['one'] = null;
                    if (!array_key_exists('two', $arr[$key_group]['fields'][$key_field]['field']['value']))
                        $arr[$key_group]['fields'][$key_field]['field']['value']['two'] = null;

                    if (!array_key_exists('text', $arr[$key_group]['fields'][$key_field]['field']))
                        $arr[$key_group]['fields'][$key_field]['field']['text'] = null;
                    if (!array_key_exists('sign', $arr[$key_group]['fields'][$key_field]['field']))
                        $arr[$key_group]['fields'][$key_field]['field']['sign'] = null;
                    if (!array_key_exists('delemiter', $arr[$key_group]['fields'][$key_field]['field']))
                        $arr[$key_group]['fields'][$key_field]['field']['delemiter'] = null;
                }

            }
        }
        return $arr;
    }


    public static function FieldsUpdate($id_element, $arr)
    {

        /*
         * Проверенные типы:
         * + 1 default(mass, kb) - текст не проверялся
         * + 3
         * + 5
         *
         * - 1 time
         * - 6
         */

        $arr = Convert::objectToArray($arr);
//        $id_category = Element::find()->select('id_category')->andWhere(['id' => $id_element])->one()['id_category'];
//        $id_cat = Element::find()->asArray()->select('id_category,id_parent,parent_filter')->where(['id' => $id_element])->one();
//        $id_cat = Element::find()->select('element.id_category')->join('LEFT JOIN', 'tbl_catalog_category', 'tbl_catalog_category.id = element.id_category')->where(['element.id' => $id_element])->one();

        $id_cat = (new Query())
            ->select('e.id_category,c.id_parent,c.parent_filter')
            ->from('{{%catalog_element}} e')
            ->leftJoin('{{%catalog_category}} c', 'e.id_category = c.id')
            ->where(['e.id' => $id_element])
            ->one();

        $id_category = (isset($id_cat['id_parent']) AND $id_cat['parent_filter'] == 1) ? $id_cat['id_parent'] : $id_cat['id_category'];

//        if (in_array($id_category, array(93, 94, 95, 96, 97, 98))) {//////////////////////////временно
//            $id_category = 5;////////////////////////
//        }
//        if (in_array($id_category, array(99, 100, 101, 102, 103))) {//////////////////////////временно
//            $id_category = 26;////////////////////////
//        }
//        if (in_array($id_category, array(120, 121))) {//////////////////////////временно
//            $id_category = 29;////////////////////////
//        }
//        if (in_array($id_category, array(122, 123, 124, 125, 126, 127))) {//////////////////////////временно
//            $id_category = 18;////////////////////////
//        }
//        if (in_array($id_category, array(180, 181, 182, 183))) {//////////////////////////временно
//            $id_category = 23;////////////////////////
//        }
//        if (in_array($id_category, array(184, 185, 186, 187))) {//////////////////////////временно
//            $id_category = 14;////////////////////////
//        }
//        if (in_array($id_category, array(188, 189, 190, 191, 192))) {//////////////////////////временно
//            $id_category = 32;////////////////////////
//        }
//        if (in_array($id_category, array(228, 229))) {//////////////////////////временно
//            $id_category = 20;////////////////////////
//        }

        // Получаем из бд все поля характеристик
        $fields_db = Field::find()
            ->select('cf.*, cfg.title as group')
            ->from('tbl_catalog_field cf')
            ->leftJoin('tbl_catalog_field_group cfg', 'cf.id_group = cfg.id')
            ->where('cfg.id_category=:id_category', ['id_category' => $id_category])
            ->asArray()
            ->all();

        // Проверяем есть ли хоть одна характеристика и если есть, то удаляем все харрактеристики
        if (Element::find()->select('id')->from('tbl_catalog_field_element_value_rel')->where('id_element=:id_element', ['id_element' => $id_element])->exists()) {
            $query = "
                    DELETE cfv.*
                    FROM tbl_catalog_field_value cfv
                    LEFT JOIN tbl_catalog_field_element_value_rel cfev ON cfv.id = cfev.id_value
                    WHERE cfev.id_element = :id_element;

                    DELETE cfev.*
                    FROM tbl_catalog_field_element_value_rel cfev
                    WHERE cfev.id_element = :id_element;";

            Yii::$app->db->createCommand($query)->bindParam(':id_element', $id_element)->execute();
        }

        // Значения полей для добавления
        $insert_db_vals = [];

        foreach ($arr as $group) {
            foreach ($group['fields'] as $field) {

                foreach ($fields_db as $field_db) {

                    if ($group['name'] == $field_db['group'] && $field['name'] == $field_db['name']) {

                        $type = $field_db['type'];
                        $id_field = $field_db['id'];
                        $text = $field['field']['text'];

                        if ($type == 1) {
                            $type_dop = $field['field']['type_dop'];
                            if (in_array($type_dop, ['default', 'mass', 'kb'])) {
                                $value = $field['field']['value'];
                                if ($value || $text) {
                                    $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'value' => $value, 'text' => $text];
                                }
                            } else if ($type_dop == 'time') {
                                // Тоже самое что и тип 6
                                $one = $field['field']['value']['one'];
                                $two = $field['field']['value']['two'];
                                if ($one && $two) {
                                    $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'value' => $one, 'dop' => '1', 'text' => $text];
                                    $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'value' => $two, 'dop' => '2', 'text' => $text];
                                } else if ($one && !$two) {
                                    $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'value' => $one, 'dop' => '1', 'text' => $text];
                                } else if (!$one && $two) {
                                    $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'value' => $two, 'dop' => '2', 'text' => $text];
                                } else if (!$one && !$two && $text) {
                                    $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'text' => $text];
                                }
                            } else if ($type_dop == 'with_one') {
                                // Значения
                                $values = $field['field']['check'];
                                foreach ($values as $value) {
                                    if ($value['active']) {
                                        if ($value['val'] == 'Есть') {
                                            $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'value' => '1', 'dop' => '1', 'text' => $text];
                                        } else if ($value['val'] == 'Нет') {
                                            $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'value' => '0', 'dop' => '1', 'text' => $text];
                                        }
                                    }
                                }
                            }
                        } else if ($type == 3) {
                            if (count($field['field']['check']) > 0) {
                                foreach ($field['field']['check'] as $line) {
                                    if ($line['active']) {
                                        $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'value' => $line['id'], 'text' => $text];
                                    }
                                }
                            }
                        } else if ($type == 5) {
                            $text = $field['field']['value'];
                            if ($text) {
                                $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'text' => $text];
                            }
                        } else if ($type == 6) {
                            $one = $field['field']['value']['one'];
                            $two = $field['field']['value']['two'];
                            if ($one && $two) {
                                $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'value' => $one, 'dop' => '1', 'text' => $text];
                                $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'value' => $two, 'dop' => '2', 'text' => $text];
                            } else if ($one && !$two) {
                                $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'value' => $one, 'dop' => '1', 'text' => $text];
                            } else if (!$one && $two) {
                                $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'value' => $two, 'dop' => '2', 'text' => $text];
                            } else if (!$one && !$two && $text) {
                                $insert_db_vals[] = ['id_element' => $id_element, 'id_field' => $id_field, 'text' => $text];
                            }
                        }

                    }

                }

            }
        }

        /*
        echo '<pre>';
        print_r($insert_db_vals);
        echo '</pre>';
        if (1) die();
        */

        // Добавление значений полей в базу
        if (count($insert_db_vals) > 0) {
            foreach ($insert_db_vals as $arrInsertVal) {
                if ($arrInsertVal['id_field'] > 0) {

                    $arrInsertVal['value'] = str_replace(' ', '', $arrInsertVal['value']); // 12 000 -> 12000

                    // Добавляем раз
                    $fv = new FieldValue();
                    $fv->value = (float)$arrInsertVal['value'];
                    $fv->dop = $arrInsertVal['dop'];
                    $fv->text = $arrInsertVal['text'];
                    if ($fv->save()) {
                        $last_id = (int)Yii::$app->db->getLastInsertID();
                        // Добавляем два
                        $fv = new FieldElementValue();
                        $fv->id_element = $arrInsertVal['id_element'];
                        $fv->id_field = $arrInsertVal['id_field'];
                        $fv->id_value = $last_id;
                        if (!$fv->save()) {
                            print_r($fv->errors);
                        }
                    } else {
                        print_r($fv->errors);
                    }

                }
            }
        }

    }

    // Копия с фронта
    public static function getFields($id)
    {
        return FieldElementValue::find()
            ->select('cf.name, cf.description, cf.variant, cf.type, cf.dop, cf.unit, cfg.title as group, cfv.value as v_value, cfv.dop as v_dop, cfv.text as v_text, cfev.id_field')
            ->from('tbl_catalog_field_element_value_rel cfev')
            ->leftJoin('tbl_catalog_field cf', 'cfev.id_field = cf.id')
            ->leftJoin('tbl_catalog_field_group cfg', 'cf.id_group = cfg.id')
            ->leftJoin('tbl_catalog_field_value cfv', 'cfev.id_value = cfv.id')
            ->where('cfev.id_element=:id_element', ['id_element' => $id])
            ->orderBy('cfg.sort, cf.sort ASC')
            ->asArray()->all();
    }
}