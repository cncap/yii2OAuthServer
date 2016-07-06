<?php
namespace cncap\yii2\oauth2server\controllers;
use Yii;
use yii\helpers\ArrayHelper;
use cncap\yii2\oauth2server\filters\ErrorToExceptionFilter;
class DefaultController extends \yii\rest\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'exceptionFilter' => [
                'class' => ErrorToExceptionFilter::className()
            ],
        ]);
    }

    public function actionToken()
    {
        $response = $this->module->getServer()->handleTokenRequest();
        return $response->getParameters();
    }
    /**
     * @return mixed
     */
    public function actionAuthorize()
    {
        $request = new Request(Yii::$app->request->post());

        $response = $this->module->getServer()->handleAuthorizeRequest($request, $response = new Response(), true);
        return $response->getParameters();
    }

    public function actionRevoke()
    {
        $server = $this->module->getServer();
        $request = $this->module->getRequest();
        $response = $server->handleRevokeRequest($request);
        return $response->getParameters();
    }

}