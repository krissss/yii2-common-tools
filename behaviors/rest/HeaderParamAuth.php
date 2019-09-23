<?php

namespace kriss\behaviors\rest;

use yii\web\Request;
use yii\web\User;

class HeaderParamAuth extends \yii\filters\auth\QueryParamAuth
{
    /**
     * @inheritDoc
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
