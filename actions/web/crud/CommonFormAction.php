<?php

namespace kriss\actions\web\crud;

use kriss\actions\helper\ActionTools;
use kriss\actions\traits\AjaxViewTrait;
use kriss\actions\traits\FlashMessageTrait;
use kriss\actions\traits\ModelClassActionTrait;
use Yii;

class CommonFormAction extends AbstractAction
{
    use ModelClassActionTrait;
    use FlashMessageTrait;
    use AjaxViewTrait;

    /**
     * @var string
     */
    public $doMethod;
    /**
     * @var string|array
     */
    public $successRedirect;

    public function run()
    {
        $model = $this->newModel();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $result = ActionTools::invokeClassMethod($model, $this->doMethod);
            $this->setFlashMessage($result, $model);
            if ($result !== false) {
                if ($this->successRedirect) {
                    return $this->controller->redirect($this->successRedirect);
                } else {
                    return $this->redirectPrevious();
                }
            }
        }

        return $this->render($this->controller, [
            'model' => $model
        ]);
    }
}
