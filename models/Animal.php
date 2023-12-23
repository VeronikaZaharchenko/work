<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "animal".
 *
 * @property int $id_animal
 * @property string $name_animal
 * @property string|null $photo
 * @property string $gender
 * @property int $category_id
 * @property string $timestamp
 *
 * @property Category $category
 * @property Record[] $records
 */
class Animal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'animal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_animal', 'name_animal', 'gender', 'category_id'], 'required'],
            [['id_animal', 'category_id'], 'integer'],
            [['timestamp'], 'safe'],
            [['name_animal', 'gender'], 'string', 'max' => 20],
            [['photo'], 'file', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'maxSize' => 1024*1024*2],            [['id_animal'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id_category']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_animal' => 'Id Animal',
            'name_animal' => 'Name Animal',
            'photo' => 'Photo',
            'gender' => 'Gender',
            'category_id' => 'Category ID',
            'timestamp' => 'Timestamp',
        ];
    }
    
        public function beforeValidate()
    {
        $this->photo=UploadedFile::getInstanceByName('photo');
        return parent::beforeValidate();
    }
    
    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id_category' => 'category_id']);
    }

    /**
     * Gets query for [[Records]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecords()
    {
        return $this->hasMany(Record::class, ['animal_id' => 'id_animal']);
    }
}
