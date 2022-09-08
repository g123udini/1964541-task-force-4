<?php

namespace app\controllers;

use app\controllers\AccessControllers\AnonymousController;
use app\models\forms\LoginForm;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

class LoginController extends AnonymousController
{

    public function actionIndex()
    {
        $this->layout = 'landing';
        $loginForm = new LoginForm();

        if ($loginForm->load(Yii::$app->request->post())) {
            if ($loginForm->validate()) {
                $user = $loginForm->getUser();
                Yii::$app->user->login($user);

                return $this->goHome();
            }
        }

        return $this->render('landing', ['model' => $loginForm]);
    }

    public function actionValidation()
    {
        $this->layout = 'landing';
        $loginForm = new LoginForm();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            ActiveForm::validate($loginForm);
        }
    }

}