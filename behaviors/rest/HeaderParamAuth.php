<?php

namespace kriss\behaviors\rest;

use yii\web\IdentityInterface;
use yii\web\Request;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use yii\web\User;

class HeaderParamAuth extends \yii\filters\auth\QueryParamAuth
{
    /**
     * @param $user User
     * @param $request Request
     * @param $response Response
     * @return IdentityInterface|null
     * @throws UnauthorizedHttpException
     */
    public function authenticate($user, $request, $response)
    {
        $accessToken = $request->headers->get($this->tokenParam);
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
