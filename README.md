system requirements:\
``
composer
``\
```php 8.1```

Installation:\
``composer install``

Application calculates commission fee for provided data:

USAGE:
put commissions into csv file in /data catalog with following template:

date,userId,clientType,operationType,amount,currency\
2014-12-31,4,private,withdraw,1200.00,EUR

Run command to generate output:\
``php bin/console  app:commission:calculate  input.csv   ``

