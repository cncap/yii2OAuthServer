<?php

namespace cncap\yii2\oauth2server;

use yii\web\GroupUrlRule;

class Bootstrap implements \yii\base\BootstrapInterface
{
    /**
     * @var array Model's map
     */
    private $_modelMap = [
        'OauthClients'               => 'cncap\yii2\oauth2server\models\OauthClients',
        'OauthAccessTokens'          => 'cncap\yii2\oauth2server\models\OauthAccessTokens',
        'OauthAuthorizationCodes'    => 'cncap\yii2\oauth2server\models\OauthAuthorizationCodes',
        'OauthRefreshTokens'         => 'cncap\yii2\oauth2server\models\OauthRefreshTokens',
        'OauthScopes'                => 'cncap\yii2\oauth2server\models\OauthScopes',
    ];
    
    /**
     * @var array Storage's map
     */
    private $_storageMap = [
        'access_token'          => 'cncap\yii2\oauth2server\storage\Pdo',
        'authorization_code'    => 'cncap\yii2\oauth2server\storage\Pdo',
        'client_credentials'    => 'cncap\yii2\oauth2server\storage\Pdo',
        'client'                => 'cncap\yii2\oauth2server\storage\Pdo',
        'refresh_token'         => 'cncap\yii2\oauth2server\storage\Pdo',
        'user_credentials'      => 'cncap\yii2\oauth2server\storage\Pdo',
        'public_key'            => 'cncap\yii2\oauth2server\storage\Pdo',
        'jwt_bearer'            => 'cncap\yii2\oauth2server\storage\Pdo',
        'scope'                 => 'cncap\yii2\oauth2server\storage\Pdo',
    ];
    
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('oauth2') && ($module = $app->getModule('oauth2')) instanceof Module) {
            $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);
            foreach ($this->_modelMap as $name => $definition) {
                \Yii::$container->set("cncap\\yii2\\oauth2server\\models\\" . $name, $definition);
                $module->modelMap[$name] = is_array($definition) ? $definition['class'] : $definition;
            }
            
            $this->_storageMap = array_merge($this->_storageMap, $module->storageMap);
            foreach ($this->_storageMap as $name => $definition) {
                \Yii::$container->set($name, $definition);
                $module->storageMap[$name] = is_array($definition) ? $definition['class'] : $definition;
            }
            
            if ($app instanceof \yii\console\Application) {
                $module->controllerNamespace = 'cncap\yii2\oauth2server\commands';
            }
        }
    }
}