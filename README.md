# Free-Mobile.yii
[![Release](http://img.shields.io/packagist/v/cedx/yii2-free-mobile.svg)](https://packagist.org/packages/cedx/yii2-free-mobile) [![License](http://img.shields.io/packagist/l/cedx/yii2-free-mobile.svg)](http://dev.belin.io/free-mobile.yii/src/master/LICENSE.txt) [![Downloads](http://img.shields.io/packagist/dt/cedx/yii2-free-mobile.svg)](https://packagist.org/packages/cedx/yii2-free-mobile)

[Free Mobile](http://mobile.free.fr) logging for [Yii](http://www.yiiframework.com), high-performance [PHP](http://php.net) framework.

This package provides a single class, `yii\log\FreeMobileTarget`
which is a log target allowing to send log messages by SMS to a mobile phone.

To use it, you must have a Free Mobile account and have enabled SMS Notifications
in the Options of your [Subscriber Area](https://mobile.free.fr/moncompte).

![Screenshot](http://api.belin.io/free-mobile.yii/img/screenshot.jpg)

## Documentation
- [API Reference](http://api.belin.io/free-mobile.yii)

## Installing via [Composer](https://getcomposer.org)
From a command prompt, run:

```shell
$ composer require cedx/yii2-free-mobile
```

Now in your application configuration file, you can use the following log route:

```php
return [
  'bootstrap' => [ 'log' ],
  'components' => [
    'log' => [
      'targets' => [
        [
          'class' => 'yii\log\FreeMobileTarget',
          'levels' => [ 'error' ],
          'password' => '<your Free Mobile identification key>',
          'userName' => '<your Free Mobile user name>'
        ]
      ]
    ]
  ]
];
```

## License
[Free-Mobile.yii](https://packagist.org/packages/cedx/yii2-free-mobile) is distributed under the MIT License.
