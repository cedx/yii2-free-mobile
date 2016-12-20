# Free Mobile for Yii
![Release](https://img.shields.io/packagist/v/cedx/yii2-free-mobile.svg) ![License](https://img.shields.io/packagist/l/cedx/yii2-free-mobile.svg) ![Downloads](https://img.shields.io/packagist/dt/cedx/yii2-free-mobile.svg) ![Code quality](https://img.shields.io/codacy/grade/b7169de7f3c845eb86161f66df6875e2.svg) ![Build](https://img.shields.io/travis/cedx/yii2-free-mobile.svg)

[Free Mobile](http://mobile.free.fr) connector for [Yii](http://www.yiiframework.com), high-performance [PHP](https://secure.php.net) framework.

This package provides classes allowing to send SMS messages to a [Free Mobile](http://mobile.free.fr) account.
To use it, the target account must have enabled SMS Notifications in the Options of its [Subscriber Area](https://mobile.free.fr/moncompte).

## Requirements
The latest [PHP](https://secure.php.net) and [Composer](https://getcomposer.org) versions.
If you plan to play with the sources, you will also need the latest [Phing](https://www.phing.info) version.

## Installing via [Composer](https://getcomposer.org)
From a command prompt, run:

```shell
$ composer require cedx/yii2-free-mobile
```

## Usage

### As a component: the `Client` class
In your application configuration file, you can use the following component:

```php
return [
  'components' => [
    'freemobile' => [
      'class' => 'yii\freemobile\Client',
      'password' => '<your Free Mobile identification key>',
      'username' => '<your Free Mobile user name>'
    ]
  ]
];
```

Once the `yii\freemobile\Client` component initialized with your credentials, you can use the `sendMessage()` method:

```php
$client = \Yii::$app->get('freemobile');
$result = $client->sendMessage('Hello World!');

if ($result) echo 'The message was sent successfully.';
else echo 'An error occurred while sending the message.';
```

The text of the messages will be automatically truncated to 160 characters: you can't send multipart messages using this library.

### As a log target: the `LogTarget` class
In your application configuration file, you can use the following log target:

```php
return [
  'bootstrap' => [ 'log' ],
  'components' => [
    'log' => [
      'targets' => [
        [
          'class' => 'yii\freemobile\LogTarget',
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
use yii\freemobile\{Client, RequestEvent};

$client->on(Client::EVENT_REQUEST, function(RequestEvent $event) {
  echo 'Client request: ', $event->getRequest()->getUri();
});
```

### The `response` event
Emitted every time a response is received from the remote service:

```php
use yii\freemobile\{Client, ResponseEvent};

$client->on(Client::EVENT_RESPONSE, function(ResponseEvent $event) {
  echo 'Server response: ', $event->getResponse()->getStatusCode();
});
```

## Unit Tests
In order to run the tests, you must set two environment variables:

```shell
$ export FREEMOBILE_USERNAME="<your Free Mobile user name>"
$ export FREEMOBILE_PASSWORD="<your Free Mobile identification key>"
```

Then, you can run the `test` script from the command prompt:

```shell
$ phing test
```

## See Also
- [Code Quality](https://www.codacy.com/app/cedx/yii2-free-mobile)
- [Continuous Integration](https://travis-ci.org/cedx/yii2-free-mobile)

## License
[Free Mobile for Yii](https://github.com/cedx/yii2-free-mobile) is distributed under the Apache License, version 2.0.
