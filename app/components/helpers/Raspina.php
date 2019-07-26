<?php
namespace app\components\helpers;
use app\modules\link\models\Link;
use app\modules\post\models\Category;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class Raspina
{
    public static function title($title = null)
    {
        $title = Yii::t('app', $title);
        if($title !== null)
        {
            Yii::$app->view->title .= ' - ' . $title;
        }
        return Yii::$app->view->title;
    }

    public static function t($message, $params = [])
    {
        return Yii::t('app', $message, $params);
    }

    public static function dump($var)
    {
        var_dump($var);
    }

    public static function a($text, $url)
    {
        return Html::a($text, $url);
    }

    public static function date($date)
    {
        return Yii::$app->date->asDatetime($date);
    }

    public static function description()
    {
        return Yii::$app->params['siteDescription'];
    }

    public static function keywords()
    {
        return Yii::$app->params['keywords'];
    }

    public static function csrfMetaTags()
    {
        return Yii::$app->params['csrfMetaTags'];
    }

    public static function charset()
    {
        return Yii::$app->params['charset'];
    }

    public static function templateUrl()
    {
        return Yii::$app->params['templateUrl'];
    }

    public static function templateDir()
    {
        return Yii::$app->params['templateDir'] . '/';
    }

    public static function siteDescription()
    {
        return Yii::$app->params['siteDescription'];
    }

    public static function subject()
    {
        return Yii::$app->params['subject'];
    }

    public static function url()
    {
        return Yii::$app->params['url'];
    }

    public static function isAvatar()
    {
        $avatarPath = Yii::getAlias('@user_avatar') . DIRECTORY_SEPARATOR . Yii::$app->hashids->encode(Yii::$app->user->id) . '.jpg';
        return file_exists($avatarPath);
    }

    public static function avatarImage()
    {
        return Yii::$app->view->params['url'] . 'common/files/avatar/' . Yii::$app->hashids->encode(Yii::$app->user->id) . '.jpg';
    }

    public static function authorName()
    {
        return Yii::$app->view->params['about']['name'];
    }

    public static function aboutText()
    {
        return Yii::$app->view->params['about']['short_text'];
    }

    public static function siteModel()
    {
        return Yii::$app->params['index'];
    }

    public static function categories($withUrl = true)
    {
        $result = Category::getAll();
        if($withUrl)
        {
            foreach ($result as $key => $value)
            {
                $result[$key]['url'] = Url::to(['/home/default/index', 'category' => $result[$key]['id'],'title' => $result[$key]['slug']]);
            }
        }
        return $result;
    }

    public static function links()
    {
        return Link::getAll();
    }

    public static function param($id)
    {
        return Yii::$app->view->params[$id];
    }

    public static function lang()
    {
        return Yii::$app->language;
    }

    public static function direction()
    {
        return Yii::$app->params['direction'];
    }
}
