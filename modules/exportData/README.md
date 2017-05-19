# config 

main.php

    'export-data' => [
        'class' => \kriss\modules\exportData\Module::className(),
        'authTokenParam' => 'this_set_for_yourself',
        'exportDataNameSpace' => '\common\models',
        'exportData' => [
            'Admin', 'User', 'UserGoods', 'Account', 'AccountLog', 'Goods',
            'Settings'
        ],
        'zipSuffix' => '.zip'
    ]

# visit

export data: http://user-site/export-data?token=this_set_for_yourself