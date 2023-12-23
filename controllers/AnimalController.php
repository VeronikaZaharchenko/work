<?php
namespace app\controllers;

use app\models\Animal;
use app\models\Category;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UploadedFile;

use yii\rest\ActiveController;
class AnimalController extends FunctionController
{
    public $modelClass = 'app\models\Animal';
    
        public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
        'class' => HttpBearerAuth::class,
        'only'=>['create']
        ];
        return $behaviors;
    }
    
        public function actionCreate()
    {
        if (!$this->admin()) return $this->send(403, $this->auth_adm);
        $data=Yii::$app->request->post();
        $animal=new Animal();
        $animal->load($data, '');
        if (!$animal->validate()) return $this->validation($animal);

        if (UploadedFile::getInstanceByName('photo')){
            $animal->photo=UploadedFile::getInstanceByName('photo');
            $hash=hash('md5', $tariff->photo->baseName) . '.' . $animal->photo->extension;
            $animal->photo->saveAs(\Yii::$app->basePath. '/assets/upload/' . $hash);
            $animal->photo=$hash;
        }
    
      
    public function actionView() //вывод каталога животных
    {
        $animals=Animal::find()->select(['id_animal', 'name_animal', 'gender','category_id'])->all();
        if ($animals)
        {
            $answer=['data'=>['animals'=>$animals]]; 
            return $this->send(200, $answer);
        }
        return $this->send(404, $this->not_found);
    }

}