<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "record".
 *
 * @property int $id_record
 * @property string $recording_date
 * @property int $user_id
 * @property int $animal_id
 * @property string $status_record
 *
 * @property Animal $animal
 * @property User $user
 */
class Record extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recording_date', 'user_id', 'animal_id'], 'required'],
            [['user_id', 'animal_id'], 'integer'],
            [['recording_date'], 'safe'],
            [['status_record'], 'match', 'pattern'=>'/^(Обрабатывается)+$|^(Подтверждена)+$|^(Отменена)+$/u', 'message'=>'Статус может быть только: Обрабатывается, Подтверждена, Отменена'],
            [['id_record'], 'unique'],
            [['animal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Animal::class, 'targetAttribute' => ['animal_id' => 'id_animal']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id_user']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_record' => 'Id Record',
            'recording_date' => 'Recording Date',
            'user_id' => 'User ID',
            'animal_id' => 'Animal ID',
            'status_record' => 'Status Record',
        ];
    }

    /**
     * Gets query for [[Animal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimal()
    {
        return $this->hasOne(Animal::class, ['id_animal' => 'animal_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id_user' => 'user_id']);
    }
}
