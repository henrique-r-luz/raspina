<?php

namespace app\controllers;

use app\models\Install;
use app\modules\setting\models\Setting;
use app\modules\user\models\AuthAssignment;
use app\modules\user\models\User;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class InstallController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
//        $this->module->layoutPath = Yii::$app->params['templateLayout'];
        $this->layout = 'install.php';
//        $this->viewPath = Yii::$app->params['templateLayout'];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new Install();
        $model->dbms = 'mysql';
        $model->db_host = 'localhost';
        $model->table_prefix = 'rs_';
        $model->url = 'http://www.';
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            try {
                $settingModel = new Setting();
                $settingModel->title = $model->title;
                $settingModel->url = $model->url;
                $settingModel->description = $model->description;
                $settingModel->language = $model->language;
                $settingModel->time_zone = $model->time_zone;
                $settingModel->page_size = 20;
                $settingModel->template = 'theme01';
                $settingModel->date_format = 'HH:mm - yyyy/MM/dd';
                $settingModel->sult = substr(md5(time()),0,10);
                $settingModel->direction = $settingModel->getLanguageDir($settingModel->language);
                $settingModel->save(false);

                $userModel = new User();
                $userModel->scenario = 'create';
                $userModel->status = 10;
                $userModel->username = $model->username;
                $userModel->last_name = $model->last_name;
                $userModel->surname = $model->surname;
                $userModel->email = $model->email;
                $userModel->setPassword($model->password);
                $userModel->generateAuthKey();
                $userModel->save(false);

                $assignmentModel = new AuthAssignment();
                $assignmentModel->item_name = 'admin';
                $assignmentModel->user_id = $userModel->id;
                $assignmentModel->save(false);

                Yii::$app->session->setFlash('success', 'Installation completed successfully, please delete "controllers/InstallController.php"');
            }
            catch(\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('index', ['model' => $model]);
    }

    public function actionMigration()
    {
        $webApp = \Yii::$app;
        new \yii\console\Application([
            'id' => 'Command runner',
            'basePath' => '@app',
            'components' => [
                'db' => $webApp->db,
            ],
        ]);
        $result = \Yii::$app->runAction('migrate/up', ['interactive' => false]);
        return $result;
    }

    public function actionTestConnection()
    {
        $request = Yii::$app->request->post();

        try {
            $db = new yii\db\Connection([
                'dsn' => "{$request['dbms']}:host={$request['db_host']};dbname={$request['db_name']}",
                'username' => $request['db_username'],
                'password' => $request['db_password'],
                'charset' => 'utf8',
                'tablePrefix' => $request['db_table_prefix'],
            ]);
            $db->open();

            $dbConfig = '<?php' . "\n";
            $dbConfig .= "define('DBMS','{$request['dbms']}');\n";
            $dbConfig .= "define('DB_HOST','{$request['db_host']}');\n";
            $dbConfig .= "define('DB_NAME','{$request['db_name']}');\n";
            $dbConfig .= "define('DB_USER_NAME','{$request['db_username']}');\n";
            $dbConfig .= "define('DB_PASSWORD','{$request['db_password']}');\n";
            $dbConfig .= "define('TBL_PREFIX','{$request['db_table_prefix']}');\n";
            file_put_contents(Yii::getAlias('@webroot') . '/config/db_config.php',$dbConfig);
            echo 1;
        }
        catch(\Exception $e) {
            echo 0;
        }
    }

}
