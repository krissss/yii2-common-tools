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
    });

    // model generator: translate table name to model class
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

    // model generator: translate model class to query class
    $('#generator-modelclass').on('blur', function () {
        var modelClass = $(this).val();
        if (modelClass !== '') {
            var queryClass = $('#generator-queryclass').val();
            if (queryClass === '') {
                queryClass = modelClass + 'Query';
                $('#generator-queryclass').val(queryClass);
            }
        }
    });
    
    // model generator: synchronize query namespace with model namespace
    $('#generator-ns').on('blur', function () {
        var stickyValue = $('#model-generator .field-generator-queryns .sticky-value');
        var input = $('#model-generator #generator-queryns');
        if (stickyValue.is(':visible') || !input.is(':visible')) {
            var ns = $(this).val();
            stickyValue.html(ns);
            input.val(ns);
        }
    });

    // model generator: toggle query fields
    $('form #generator-generatequery').change(function () {
        $('form .field-generator-queryns').toggle($(this).is(':checked'));
        $('form .field-generator-queryclass').toggle($(this).is(':checked'));
        $('form .field-generator-querybaseclass').toggle($(this).is(':checked'));
    }).change();
    
    // model generator: toggle dao fields
    $('form #generator-generatequery').change(function () {
        $('form .field-generator-queryns').toggle($(this).is(':checked'));
        $('form .field-generator-queryclass').toggle($(this).is(':checked'));
        $('form .field-generator-querybaseclass').toggle($(this).is(':checked'));
    }).change();
JS;
$this->registerJs($js);
