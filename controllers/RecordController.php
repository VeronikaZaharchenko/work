<?php
namespace app\controllers;

use app\models\Record;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class RecordController extends FunctionController
{
    public $modelClass = 'app\models\Record';

    public function behaviors()
    {
    $behaviors = parent::behaviors();
    $behaviors['authenticator'] = [
    'class' => HttpBearerAuth::class,
    'only'=>['create', 'change', 'delete']
    ];
    return $behaviors;
    }

    public function actionCreate() //оформление записи
    {
        $data=Yii::$app->request->post();
        $record=new Record();
        $record->load($data, '');
        if (!$record->validate()) return $this->validation($record);
        $record->save();
        $answer=['data'=>['status'=>'ОК', 'id'=>(int)$record->id_record]]; 
        return $this->send(200, $answer);
    }

    public function actionDelete($id) //отмена записи пользователем
    {
        $record=Record::findOne($id);
        if($record){
            if($record->status_record == "Обрабатывается")
            {
                $record->delete();
                $answer=['data'=>['status'=>'ОК']]; 
                return $this->send(200, $answer);
            }
            else 
            {
                $error=['error'=> ['code'=>403, 'message'=>'Access denied',
                'errors'=>'Record status completed or refusal']];
                return $this->send(422, $error);
            }
        }
        return $this->send(404, $this->not_found);
    }

    public function actionChange($id) //изменение статуса записи администратором
    {
        if (!$this->admin()) return $this->send(403, $this->auth_adm);
        $record= Record::findOne($id);
        $status = Yii::$app->request->getBodyParams();
        $record->status_record=$status['status_record'];
        if (!$record->validate()) return $this->validation($record);
        $record->save();
        $answer=['data'=>['status'=>'ОК']]; 
        return $this->send(200, $answer);
    }

}