# Free-Mobile.yii
[![Version](http://img.shields.io/packagist/v/cedx/free-mobile-yii.svg?style=flat-square)](https://packagist.org/packages/cedx/free-mobile-yii) [![Downloads](http://img.shields.io/packagist/dt/cedx/free-mobile-yii.svg?style=flat-square)](https://packagist.org/packages/cedx/free-mobile-yii) [![License](http://img.shields.io/packagist/l/cedx/free-mobile-yii.svg?style=flat-square)](https://github.com/cedx/free-mobile.yii/blob/master/LICENSE.txt)

[Free Mobile](http://mobile.free.fr) logging for [Yii Framework](http://www.yiiframework.com).

This package provides a single class, `CFreeMobileLogRoute`
which is a log route allowing to send log messages by SMS to a mobile phone.

To use it, you must have a Free Mobile account and have enabled SMS Notifications
in the Options of your [Subscriber Area](https://mobile.free.fr/moncompte).

![Screenshot](http://dev.belin.io/free-mobile.yii/img/screenshot.jpg)

## Documentation
- [API Reference](http://dev.belin.io/free-mobile.yii/api)

## Installing via [Composer](https://getcomposer.org)

#### 1. Depend on it
Add this to your project's `composer.json` file:

```json
{
  "require": {
    "cedx/free-mobile-yii": "*"
  }
}
```

#### 2. Install it
From the command line, run:

```shell
$ php composer.phar install
```

#### 3. Import it
Now in your application configuration file, you can use the following log route:

```php
return [
  'components'=>[
    'log'=>[
      'class'=>'system.logging.CLogRouter',
      'routes'=>[
        [
          'class'=>'ext.cedx.free-mobile-yii.lib.CFreeMobileLogRoute',
          'password'=>'<your Free Mobile identification key>',
          'userName'=>'<your Free Mobile user name>'
        ]
      ]
    ]
  ]
];
```

Adjust the values as needed. Here, it's supposed that `CApplication->extensionPath`,
that is the `ext` path alias, has been set to Composer's `vendor` directory.

## License
[Free-Mobile.yii](https://packagist.org/packages/cedx/free-mobile-yii) is distributed under the MIT License.
