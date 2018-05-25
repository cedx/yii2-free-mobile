# Usage

## SMS notifications
**Free Mobile for Yii** provides the `yii\freemobile\Client` class, which allow to send SMS messages to your mobile phone by using the `sendMessage()` method:

```php
<?php
use yii\freemobile\{Client, ClientException};

try {
  $config = [
    'username' => 'your account identifier', // e.g. "12345678"
    'password' => 'your API key' // e.g. "a9BkVohJun4MA"
  ];

  $client = new Client($config);
  $client->sendMessage('Hello World!');
  echo 'The message was sent successfully';
}

catch (\Throwable $e) {
  echo 'An error occurred: ', $e->getMessage(), PHP_EOL;
  if ($e instanceof ClientException) echo 'From: ', $e->getUri(), PHP_EOL;
}
```

The `Client->sendMessage()` method throws a [`yii\base\InvalidArgumentException`](https://www.yiiframework.com/doc/api/2.0/yii-base-invalidargumentexception)
if the specified message is empty. It throws a `yii\freemobile\ClientException` if any error occurred while sending the message.

!!! warning
    The text of the messages will be automatically truncated to **160** characters:  
    you can't send multipart messages using this library.

## Client events
The `yii\freemobile\Client` class triggers some [events](https://www.yiiframework.com/doc/guide/2.0/en/concept-events) during its life cycle:

- `Client::EVENT_REQUEST` : emitted every time a request is made to the remote service.
- `Client::EVENT_RESPONSE` : emitted every time a response is received from the remote service.

You can subscribe to them using the `on()` method:

```php
<?php
use yii\freemobile\{Client};
use yii\httpclient\{RequestEvent};

$client->on(Client::EVENT_REQUEST, function(RequestEvent $event) {
  echo 'Client request: ', $event->request->url;
});

$client->on(Client::EVENT_RESPONSE, function(RequestEvent $event) {
  echo 'Server response: ', $event->response->statusCode;
});
```

## Yii integration

### Application component
In your [application configuration](https://www.yiiframework.com/doc/guide/2.0/en/concept-configurations#application-configurations) file, you can register the `yii\freemobile\Client` class as an [application component](https://www.yiiframework.com/doc/guide/2.0/en/structure-application-components):

```php
<?php
use yii\freemobile\{Client};

return [
  'components' => [
    'freemobile' => [
      'class' => Client::class,
      'username' => 'your account identifier', // e.g. "12345678"
      'password' => 'your API key' // e.g. "a9BkVohJun4MA"
    ]
  ]
];
```

Once the `freemobile` component is initialized with your credentials, you can use its `sendMessage()` method, available through the [application instance](https://www.yiiframework.com/doc/guide/2.0/en/structure-applications):

```php
<?php
$client = \Yii::$app->freemobile;
$client->sendMessage('Hello World!');
```

### Logging
In your [application configuration](https://www.yiiframework.com/doc/guide/2.0/en/concept-configurations#application-configurations) file, you can register the `yii\freemobile\LogTarget` class as a [log target](https://www.yiiframework.com/doc/guide/2.0/en/runtime-logging#log-targets):

```php
<?php
use yii\freemobile\{LogTarget};

return [
  'bootstrap' => ['log'],
  'components' => [
    'log' => [
      'targets' => [
        [
          'class' => LogTarget::class,
          'client' => 'freemobile',
          'levels' => ['error']
        ]
      ]
    ]
  ]
];
```

The optional `LogTarget->client` property accepts a `yii\freemobile\Client` instance or the application component ID of a Free Mobile client. It defaults to the `"freemobile"` string.

!!! tip
    As text of the log messages is truncated to **160** characters,
    you should not change the default value of the `LogTarget->exportInterval`
    and `LogTarget->logVars` properties.

## Unit tests
If you want to run the library tests, you must set two environment variables:

```shell
export FREEMOBILE_USERNAME="your account identifier"
export FREEMOBILE_PASSWORD="your API key"
```

Then, you can run the `test` script from the command prompt:

```shell
composer test
```
