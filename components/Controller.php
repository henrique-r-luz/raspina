<?php
namespace app\components;

class Controller extends \yii\web\Controller
{
    public function init()
    {
        $app = \Yii::$app->request->get('app');
        if($app != null)
        {
            \Yii::$app->urlManager->baseUrl .= '\\' . \Yii::$app->request->get('app');
        }

        parent::init(); // TODO: Change the autogenerated stub
    }
}
