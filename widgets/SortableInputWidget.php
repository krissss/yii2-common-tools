<?php

namespace common\widgets;

use common\forms\SortableForm;
use kartik\sortable\Sortable;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\widgets\InputWidget;

/**
 * @link https://demos.krajee.com/sortable#usage
 * @require kartik-v/yii2-sortable
 *
 * @usage
 * echo $form->field($model, 'sorted', [
 *     'template' => '<div class="col-sm-12">{input}{error}</div>'
 * ])->widget(SortableInputWidget::class)
 */
class SortableInputWidget extends InputWidget
{
    /**
     * @var array|Model[]
     */
    public $sortItemModels;
    /**
     * @var string|callable
     */
    public $sortContentAttribute;
    /**
     * @var array|callable
     */
    public $sortItemOptions = [];
    /**
     * @var array|callable
     */
    public $sortableOptions = [];
    /**
     * @var string
     */
    public $sortValueAttribute = 'id';
    /**
     * @var int
     */
    public $liMinHeight = 20;

    public function init()
    {
        parent::init();
        if ($this->model instanceof SortableForm) {
            if (!$this->sortItemModels) {
                $this->sortItemModels = $this->model->getSortItemModels();
            }
            if (!$this->sortContentAttribute) {
                $this->sortContentAttribute = $this->model->getSortContentAttribute();
            }
        }
    }

    public function run()
    {
        $css = <<<CSS
.sortable.grid li {
    min-height: {$this->liMinHeight}px;
}
CSS;
        $this->view->registerCss($css);

        $html = [];
        $html[] = Sortable::widget($this->getSortableOptions());
        $html[] = $this->renderInputHtml('hidden');
        return implode("\n", $html);
    }

    /**
     * @return array
     */
    protected function getSortableItems()
    {
        if (is_string($this->sortContentAttribute)) {
            return array_map(function ($item) {
                return [
                    'content' => $item[$this->sortContentAttribute],
                    'options' => $this->getSortableItemOptions($item)
                ];
            }, $this->sortItemModels);
        } elseif (is_callable($this->sortContentAttribute)) {
            return array_map(function ($item) {
                return [
                    'content' => call_user_func($this->sortContentAttribute, $item),
                    'options' => $this->getSortableItemOptions($item)
                ];
            }, $this->sortItemModels);
        }
        throw new InvalidConfigException('sortContentAttribute config error');
    }

    /**
     * @param $item
     * @return array
     */
    protected function getSortableItemOptions($item)
    {
        if (is_callable($this->sortItemOptions)) {
            $this->sortItemOptions = call_user_func($this->sortItemOptions, $item);
        } elseif (is_array($this->sortItemOptions)) {
        } else {
            throw new InvalidConfigException('sortItemOptions config error');
        }
        return array_merge($this->sortItemOptions, [
            'data-id' => $item[$this->sortValueAttribute]
        ]);
    }

    /**
     * @return array
     */
    protected function getSortableOptions()
    {
        $elId = $this->options['id'];
        $sortUpdateJs = <<<JS
var newResult = [];
$.each(e.detail.destination.items, function(index, item) {
  newResult.push(item.dataset.id)
});
$('#{$elId}').val(newResult.join(','));
JS;

        $options = [
            'type' => 'grid',
            'items' => $this->getSortableItems(),
            'pluginEvents' => [
                'sortupdate' => 'function(e) {' . $sortUpdateJs . '}'
            ],
        ];

        if ($this->sortableOptions) {
            if (is_array($this->sortableOptions)) {
                $options = array_merge($options, $this->sortableOptions);
            } elseif (is_callable($this->sortableOptions)) {
                $options = call_user_func($this->sortableOptions, $options);
            }
        }

        return $options;
    }
}
