<?php
/**
 * 带有跳转的LinkPager
 */

namespace kriss\widgets;

use Yii;
use yii\helpers\Html;

class LinkPagerWithJump extends \yii\widgets\LinkPager
{
    /**
     * {pageButtons} {customPage}
     */
    public $template = '{pageButtons} {customPage}';

    public $customPageBtnClass = 'btn btn-primary';

    public $customPageBtnText = '转';

    public $customPageInputWidth = 20;

    public $customPageOptions = ['class' => 'form-control' ,'style'=>'border-top-left-radius:3px;border-bottom-left-radius:3px'];


    public function init() {
        parent::init();
        $this->registerJs();
    }

    public function run() {
        if ($this->registerLinkTags) {
            $this->registerLinkTags();
        }
        echo $this->renderPageContent();
    }

    protected function renderPageContent() {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) {
            $name = $matches[1];
            if ('customPage' == $name) {
                return $this->renderCustomPage();
            } else if ('pageButtons' == $name) {
                return $this->renderPageButtons();
            }
            return "";
        }, $this->template);
    }

    protected function renderCustomPage() {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }
        $page = 1;
        $params = Yii::$app->getRequest()->queryParams;
        if (isset($params[$this->pagination->pageParam])) {
            $page = intval($params[$this->pagination->pageParam]);
            if ($page < 1) {
                $page = 1;
            } else if ($page > $this->pagination->getPageCount()) {
                $page = $this->pagination->getPageCount();
            }
        }
        $inputGroup = Html::input('number',$this->pagination->pageParam, $page, $this->customPageOptions) .
            Html::tag('span',
                Html::tag('button', $this->customPageBtnText, ['class' => $this->customPageBtnClass, 'id'=>'btn-custom-page']),
                ['class' => 'input-group-btn']
            );
        return Html::tag('div',
            Html::tag('div', $inputGroup, ['class' => 'input-group']),
            ['class'=>'hidden-xs hidden-sm', 'style' => 'display:inline-block;margin:20px 5px;width:120px;']
        );
    }

    protected function registerJs(){
        $urlStr = $this->pagination->createUrl(0);
        $js = <<<JS
$("#btn-custom-page").click(function(){
    var pageValue = $(this).parents(".input-group").find("input[name=page]").val(),
    str = '$urlStr';
    window.location.href = str.replace('page=1', 'page='+pageValue);
});
JS;
        $this->getView()->registerJs($js);
    }
}