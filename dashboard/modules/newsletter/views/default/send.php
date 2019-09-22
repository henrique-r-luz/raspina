<?php
use dashboard\components\helpers\Html;
use kartik\select2\Select2;
use dashboard\components\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model dashboard\modules\newsletter\models\Newsletter */

$this->title = Yii::t('app', 'Send Newsletter');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Newsletter'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::beginPanel($this->title) ?>

    <?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'text')->widget(TinyMce::className(), [
    'options' => ['rows' => 20],
    'language' =>  Yii::$app->params['lang'],
    'clientOptions' => [
        'content_css' => \yii\helpers\Url::to(['/web/css/tinymce.css']),
        'directionality' => Yii::$app->params['direction'],
        'entity_encoding' => "utf-8",
        'relative_urls' => false,
        'menubar' => false,
        'plugins' => [
            "advlist autolink lists link charmap visualblocks code media table contextmenu image media codesample code"
        ],
        'toolbar' => "underline italic bold styleselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | media image  table link | codesample | code"
    ]
]) ?>

    <div class="form-group center ">
        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-success']) ?>
    </div>

<?php
echo Select2::widget([
    'name' => 'emails',
    'id' => 'emails',
    'value' => $model->getAllMails(),
    'class' => 'form-control',
    'data' => $model->getAllMails(),
    'options' => ['multiple' => true],
    'pluginOptions' => [
        'maximumInputLength' => 100
    ],
]);
?>
    <?php ActiveForm::end(); ?>
<?= Html::endPanel() ?>

<?//= Html::beginPanel($this->title, 3) ?>
<!--            --><?//= GridView::widget([
//                'dataProvider' => $dataProvider,
//                'columns' => [
//                    [
//                        'attribute' => 'email',
//                        'contentOptions' => ['style' => 'text-align: left'],
//                    ],
//                    [
//                        'class' => 'yii\grid\ActionColumn',
//                        'template' => '{delete}',
//                    ],
//                ],
//            ]); ?>
<!--            --><?php //if($dataProvider->totalCount > 10): ?>
<!--                <a href="--><?//= Url::base() . '/newsletter'; ?><!--">-->
<!--                    --><?//= Html::submitButton(Yii::t('app', 'All List'), ['class' => 'btn btn-primary newsletter-all-list']) ?>
<!--                </a>-->
<!--            --><?php //endif; ?>
<?//= Html::endPanel() ?>