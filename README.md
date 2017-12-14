# yii2 common class

Some usage classes in Yii2 Project for quickly develop

# Install

## use composer
 
    php composer.phar require kriss/yii2-common-class -vvv

## use github (use this for newest)

    git clone https://github.com/krissss/yii2-common-tools.git kriss
    
Then add Alias in Yii2

Advanced: \common\config\bootstrap.php write after other Alisa

```php
Yii::setAlias('@kriss', dirname(dirname(__DIR__)) . '/kriss');
```

Basic: \common\config\web.php write before other config

```php
Yii::setAlias('@kriss', dirname(__DIR__) . '/kriss' );
```

# Usage And Example

Not available