<?php

namespace kriss\behaviors\rest;

class QueryParamAuth extends \yii\filters\auth\QueryParamAuth
{
    /**
     * @var string
     */
    public $tokenParam = 'token';

    /**
     * 调整：post 接口通过 post 接收
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        if($request->isPost){
            $accessToken = $request->post($this->tokenParam);
        }else{
            $accessToken = $request->get($this->tokenParam);
        }
        if (is_string($accessToken)) {
            $identity = $user->loginByAccessToken($accessToken, get_class($this));
            if ($identity !== null) {
                return $identity;
            }
        }
        if ($accessToken !== null) {
            $this->handleFailure($response);
        }

        return null;
    }
}