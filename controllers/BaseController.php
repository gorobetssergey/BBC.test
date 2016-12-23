<?php
/**
 * Created by PhpStorm.
 * User: Alpha3
 * Date: 20.12.2016
 * Time: 17:12
 */

namespace app\controllers;


use app\models\News;
use app\models\User;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;

class BaseController extends Controller
{

    public function actionNewsAdd()
    {
        $model = new News();
        if(Yii::$app->request->isPost):
            $params = User::getStatus();
            $post = Yii::$app->request->post();
            if($model->load($post) && $model->saves($params)):
                Yii::$app->getSession()->setFlash('news_add_ok', Yii::t('site','news_add'));
                return (Yii::$app->user->identity->role == User::ROLE_USER) ? $this->redirect(Url::toRoute('cabinet/news-self')) : $this->refresh();
            else:
                Yii::$app->getSession()->setFlash('news_add_no', Yii::t('site','news_add_err'));
                return $this->refresh();
            endif;
        endif;
        return $this->render('../admin/newsAdd',[
            'model' => $model
        ]);
    }
    protected function findModeNews($id)
    {
        if (($model = News::findOne($id)) !== null) {
            if(User::isUser()):
                if($model->user_id == Yii::$app->user->identity->id):
                    return $model;
                endif;
            elseif (User::isAdmin() || User::isModerator()):
                return $model;
            endif;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionViewNews($id)
    {
        $model = $this->findModeNews($id);
        $params = [
            'block' => ($model->status == News::NEWS_BLOCK) ? true : false,
            'active' => ($model->status == News::NEWS_ACTIVE) ? true : false,
            'new' => ($model->status == News::NEWS_NEW) ? true : false,
            'user' => (User::isUser()) ? true : false,
        ];
        return $this->render('../admin/viewNews', [
            'model' => $model,
            'params' => $params
        ]);
    }
    public function actionUpdateNews($id)
    {
        $model = $this->findModeNews($id);
        if(Yii::$app->request->isPost):
            if($model->user_id != Yii::$app->user->identity->id)
                $model->moderator_id = Yii::$app->user->identity->id;
            $post = Yii::$app->request->post();
            $model->forbidden_at = date('Y-m-d H:m:i',strtotime('now'));
            if($model->load($post) && $model->update()) {
                Yii::$app->getSession()->setFlash('news_update_ok', Yii::t('site', 'news_update_ok'));
                return $this->redirect(Url::toRoute('view-news?id=' . $id));
            }else {
                Yii::$app->getSession()->setFlash('news_update_err', Yii::t('site', 'news_update_err'));
                return $this->redirect(Url::toRoute('view-news?id=' . $id));
            }
        endif;

        return $this->render('updateNews', [
            'model' => $model,
        ]);
    }
    public function actionDeleteNews($id)
    {
        $model = $this->findModeNews($id);
        $url = (User::isUser()) ? 'news-self' : 'news-all';
        if(Yii::$app->request->isPost):
            if($model->delete()):
                Yii::$app->getSession()->setFlash('news_delete_ok', Yii::t('site','news_delete_ok'));
                return $this->redirect(Url::toRoute($url));
            else:
                Yii::$app->getSession()->setFlash('news_delete_err', Yii::t('site','news_delete_err'));
                return $this->redirect(Url::toRoute($url));
            endif;
        endif;

        return $this->redirect(Url::toRoute('news-all'));
    }
    public function actionBlockNews($id)
    {
        $model = $this->findModeNews($id);
        $model->status = News::NEWS_BLOCK;
        $model->forbidden_at = date('Y-m-d H:m:i',strtotime('now'));
        if($model->update()):
            Yii::$app->getSession()->setFlash('news_block_ok', Yii::t('site','news_block_ok'));
            return $this->redirect(Url::toRoute('moderation'));
        else:
            Yii::$app->getSession()->setFlash('news_block_err', Yii::t('site','news_block_err'));
            return $this->redirect(Url::toRoute('moderation'));
        endif;
    }
    public function actionAllowNews($id)
    {
        $model = $this->findModeNews($id);
        $model->status = News::NEWS_ACTIVE;
        $model->moderator_id = Yii::$app->user->identity->id;
        $model->forbidden_at = date('Y-m-d H:m:i',strtotime('now'));
        if($model->update()):
            $model->on(News::EVENT_NEWS_MODERATION_OK, [$model, 'newsModerationOk']);
            $model->newsModeration();
            Yii::$app->getSession()->setFlash('news_allow_ok', Yii::t('site','news_allow_ok'));
            return $this->redirect(Url::toRoute('news-all'));
        else:
            Yii::$app->getSession()->setFlash('news_allow_err', Yii::t('site','news_allow_err'));
            return $this->redirect(Url::toRoute('news-all'));
        endif;
    }
}