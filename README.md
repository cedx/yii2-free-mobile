# Free Mobile for Yii
![Runtime](https://img.shields.io/badge/php-%3E%3D7.1-brightgreen.svg) ![Release](https://img.shields.io/packagist/v/cedx/yii2-free-mobile.svg) ![License](https://img.shields.io/packagist/l/cedx/yii2-free-mobile.svg) ![Downloads](https://img.shields.io/packagist/dt/cedx/yii2-free-mobile.svg) ![Coverage](https://coveralls.io/repos/github/cedx/yii2-free-mobile/badge.svg) ![Build](https://travis-ci.org/cedx/yii2-free-mobile.svg)

[Free Mobile](http://mobile.free.fr) connector for [Yii](http://www.yiiframework.com), high-performance [PHP](https://secure.php.net) framework.

This package provides classes allowing to send SMS messages to a [Free Mobile](http://mobile.free.fr) account.
To use it, the target account must have enabled SMS Notifications in the Options of its [Subscriber Area](https://mobile.free.fr/moncompte).

## Requirements
The latest [PHP](https://secure.php.net) and [Composer](https://getcomposer.org) versions.
If you plan to play with the sources, you will also need the latest [Phing](https://www.phing.info) version.

## Installing via [Composer](https://getcomposer.org)
From a command prompt, run:

```shell
composer global require fxp/composer-asset-plugin
composer require cedx/yii2-free-mobile
```

## Usage

### As a component: the `Client` class
In your application configuration file, you can use the following component:

```php
use yii\freemobile\{Client};

return [
  'components' => [
    'freemobile' => [
      'class' => Client::class,
      'password' => '<your Free Mobile identification key>',
      'username' => '<your Free Mobile user name>'
    ]
  ]
];
```

Once the `yii\freemobile\Client` component initialized with your credentials, you can use the `sendMessage()` method:

```php
try {
  $client = \Yii::$app->freemobile;
  $client->sendMessage('Hello World!');
  echo 'The message was sent successfully.';
}

catch (\Throwable $e) {
  echo 'An error occurred: ', $e->getMessage();
}
```

The text of the messages will be automatically truncated to 160 characters: you can't send multipart messages using this library.

### As a log target: the `LogTarget` class
In your application configuration file, you can use the following log target:

```php
use yii\freemobile\{LogTarget};

return [
  'bootstrap' => [ 'log' ],
  'components' => [
    'log' => [
      'targets' => [
        [
          'class' => LogTarget::class,
          'client' => 'freemobile',
          'levels' => [ 'error' ]
        ]
      ]
    ]
  ]
];
```

The `LogTarget::client` property accepts a `Client` instance or the application component ID of a Free Mobile client.

As text of the log messages are truncated to 160 characters, you should not change the default value of the `exportInterval` and `logVars` properties.

## Events
The `yii\freemobile\Client` class triggers some events during its life cycle.

### The `request` event
Emitted every time a request is made to the remote service:

```php
use yii\freemobile\{Client};
use yii\httpclient\{RequestEvent};

$client->on(Client::EVENT_REQUEST, function(RequestEvent $event) {
  echo 'Client request: ', $event->request->url;
});
```

### The `response` event
Emitted every time a response is received from the remote service:

```php
use yii\freemobile\{Client};
use yii\httpclient\{RequestEvent};

$client->on(Client::EVENT_RESPONSE, function(RequestEvent $event) {
  echo 'Server response: ', $event->response->statusCode;
});
```

## Unit Tests
In order to run the tests, you must set two environment variables:

```shell
export FREEMOBILE_USERNAME="<your Free Mobile user name>"
export FREEMOBILE_PASSWORD="<your Free Mobile identification key>"
```

Then, you can run the `test` script from the command prompt:

```shell
composer test
```

## See also
- [API reference](https://cedx.github.io/yii2-free-mobile)
- [Code coverage](https://coveralls.io/github/cedx/yii2-free-mobile)
- [Continuous integration](https://travis-ci.org/cedx/yii2-free-mobile)

## License
[Free Mobile for Yii](https://github.com/cedx/yii2-free-mobile) is distributed under the MIT License.
