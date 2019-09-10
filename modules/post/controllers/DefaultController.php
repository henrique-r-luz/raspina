<?php
namespace app\modules\post\controllers;
use app\modules\post\models\Comment;
use app\modules\post\models\Post;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use Yii;


/**
 * Default controller for the `posts` module
 */
class DefaultController extends \app\components\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $this->module->layoutPath = Yii::$app->params['templateLayout'];
        $this->layout = 'main.twig';
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $postModel = Post::getAll(Yii::$app->request->get());
        $dataProvider = new ActiveDataProvider([
            'query' => $postModel,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize']
            ]
        ]);

        return $this->render('@theme/posts.twig', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Post model.
     * @param string $id
     * @return mixed
     */
    public function actionShareLink($id)
    {
        $id = Yii::$app->hashids->decode($id);
        if(isset($id[0]))
        {
            $postModel = Post::findOne($id[0]);
            if($postModel !== null)
            {
                $this->redirect(['view', 'id' => $id[0], 'title' => $postModel->slug]);
            }
        }
    }

    /**
     * Displays a single Post model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id,$title)
    {
        $postModel = $this->findModel($id,$title);
        $postModel->plusView();

//        $model->tags = !empty($model->tags)? explode(',',$model->tags) : null;
        $this->view->params['keywords'] = $postModel->keywords;
        $this->view->params['description'] = $postModel->meta_description;

        $commentModel = new Comment();
        $commentModel->scenario = 'post-view';
        // insert comment
        $commentModel->ip = $_SERVER['REMOTE_ADDR'];
        $commentModel->post_id = $id;

        $request = Yii::$app->request->post();
        if($commentModel->load($request) && $postModel->enable_comments && $commentModel->save())
        {
            Yii::$app->getSession()->setFlash('success', Yii::t('app','Comment successfully sent'));
            $commentModel = new Comment();
        }

        return $this->render('@theme/post.twig', [
            'model' => $postModel,
            'commentModel' => $commentModel,
        ]);
    }

    public function actionRss()
    {
        $this->module->layoutPath = '';
        $this->layout = '@app/views/layouts/blank';

        $posts = Post::find()
            ->select(["p.id","p.title","p.short_text","p.created_at","u.username"])
            ->from('{{%post}}')
            ->alias('p')
            ->leftJoin("{{%user}} AS u","p.created_by = u.id")
            ->groupBy("p.id")
            ->orderBy(['p.pin_post' => SORT_DESC, 'p.id' => SORT_DESC])
            ->where(['p.status' => 1])
            ->limit(Yii::$app->params['pageSize'])
            ->all();

        return $this->render('@theme/rss.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id,$title)
    {
        if (($model = Post::findOne(['id' => $id, 'slug' => $title])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}