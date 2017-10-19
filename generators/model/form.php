<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator kriss\generators\model\Generator */

echo $this->render('@gii-model/form', [
    'form' => $form,
    'generator' => $generator
]);
echo $form->field($generator, 'generateDao')->checkbox();
echo $form->field($generator, 'daoNs');

$js = <<<JS
    // model generator: hide class name inputs when table name input contains *
    $('#generator-tablename').change(function () {
        var show = ($(this).val().indexOf('*') === -1);
        $('.field-generator-modelclass').toggle(show);
        if ($('#generator-generatequery').is(':checked')) {
            $('.field-generator-queryclass').toggle(show);
        }
    }).change();

    $('#generator-tablename').on('blur', function () {
        var tableName = $(this).val();
        var tablePrefix = $(this).attr('table_prefix') || '';
        if (tablePrefix.length) {
            // if starts with prefix
            if (tableName.slice(0, tablePrefix.length) === tablePrefix) {
                // remove prefix
                tableName = tableName.slice(tablePrefix.length);
            }
        }
        if ($('#generator-modelclass').val() === '' && tableName && tableName.indexOf('*') === -1) {
            var modelClass = '';
            $.each(tableName.split('_'), function() {
                if(this.length>0)
                    modelClass+=this.substring(0,1).toUpperCase()+this.substring(1);
            });
            $('#generator-modelclass').val(modelClass).blur();
        }
    });
JS;
$this->registerJs($js);
