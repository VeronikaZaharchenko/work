<?php
namespace app\controllers;

use app\models\User;
use app\models\LoginForm;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class UserController extends FunctionController
{
    public $modelClass = 'app\models\User';

    public function actionCreate() //регистрация
    {
        $data=Yii::$app->request->post();
        $user=new User();
        $user->load($data, '');
        $user->password=Yii::$app->getSecurity()->generatePasswordHash($user->password); //хеширование пароля
        $user->token=Yii::$app->getSecurity()->generateRandomString(80); //для генерации случайного токена
        if (!$user->validate()) return $this->validation($user);
        $user->save(); //производит создание новой модели или обновления существующей
        $data=['data'=>['status'=>'ОК', 'id'=>(int)$user->id_user]]; 
        return $this->send(200, $data); //возвращает ответ
    }

    public function actionLogin() //аутентификация пользователя
    {
        $data=\Yii::$app->request->post();
        $login_data=new LoginForm();
        $login_data->load($data, '');
        if (!$login_data->validate()) return $this->validation($login_data);
        $user=User::find()->where(['phone'=>$login_data->phone])->one();
        if (!is_null($user)) {
        if (\Yii::$app->getSecurity()->validatePassword($login_data->password, $user->password)) {
        $token = \Yii::$app->getSecurity()->generateRandomString(80);
        $user->token = $token;
        $user->save(false); //false — произвести запись без валидации
        $data = ['data' => ['token' => $token]];
        return $this->send(200, $data);
        }
        }
        return $this->send(401, $this->unauth);
    }

}