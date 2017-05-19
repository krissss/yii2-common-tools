# config 

main.php

    'export-data' => [
        'class' => \kriss\modules\exportData\Module::className(),
        'exportDataNameSpace' => '\common\models',
        'exportData' => [
            'Admin', 'User', 'UserGoods', 'Account', 'AccountLog', 'Goods',
            'Settings'
        ],
        'zipSuffix' => '.zip'
    ]

# visit

export data: http://user-site/export-data