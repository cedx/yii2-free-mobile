# Free-Mobile.yii
[Free Mobile](http://mobile.free.fr) logging for [Yii Framework](http://www.yiiframework.com).
  
This package provides a single class, `CFreeMobileLogRoute`
which is a log route enabling to send log messages by SMS to a mobile phone.

To use it, you must have a Free Mobile account and have enabled SMS Notifications
in the Options of your [Subscriber Area](https://mobile.free.fr/moncompte).

![Screenshot](http://dev.belin.io/free-mobile.yii/img/screenshot.png)

## Documentation
- [API Reference](http://dev.belin.io/free-mobile.yii/api)

## Installing via [Composer](https://getcomposer.org)

### 1. Depend on it
Add this to your project's `composer.json` file:

```json
{
  "require": {
    "cedx/yii-free-mobile": "*"
  }
}
```

### 2. Install it
From the command line, run:

```shell
$ php composer.phar install
```

### 3. Import it
Now in your application configuration file, you can use the following log route:

```php
return [
  'components'=>[
    'log'=>[
      'class'=>'system.logging.CLogRouter',
      'routes'=>[
        [
          'class'=>'ext.cedx.yii-free-mobile.lib.CFreeMobileLogRoute',
          'categories'=>'application.*',
          'levels'=>'error',
          'logFormat'=>'[{level}@{category}] {message}',
          'password'=>'<your Free Mobile identification key>',
          'userName'=>'<your Free Mobile user name>'
        ]
      ]
    ]
  ]
];
```

## License
[Free-Mobile.yii](https://packagist.org/packages/cedx/yii-free-mobile) is distributed under the MIT License.
