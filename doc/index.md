# Free Mobile <small>for Yii</small>
![PHP](https://img.shields.io/packagist/php-v/cedx/yii2-free-mobile.svg) ![Yii Framework](https://img.shields.io/badge/yii-%3E%3D2.0-brightgreen.svg) ![Release](https://img.shields.io/packagist/v/cedx/yii2-free-mobile.svg) ![License](https://img.shields.io/packagist/l/cedx/yii2-free-mobile.svg) ![Downloads](https://img.shields.io/packagist/dt/cedx/yii2-free-mobile.svg) ![Coverage](https://coveralls.io/repos/github/cedx/yii2-free-mobile/badge.svg) ![Build](https://travis-ci.com/cedx/yii2-free-mobile.svg)

![Free Mobile](img/free_mobile.png)

## Send SMS messages to your Free Mobile account
Send notifications to your own mobile device via any internet-connected device.

For example, you can configure a control panel or a network-attached storage to your home so that they send an SMS to your [Free Mobile](http://mobile.free.fr) phone when an event occurs.

## Quick start

!!! warning
    SMS notifications require an API key. If you are not already registered,
    [sign up for a Free Mobile account](https://mobile.free.fr/subscribe).

### Get an API key
You first need to enable the **SMS notifications** in [your subscriber account](https://mobile.free.fr/moncompte).
This will give you an identification key allowing access to the [Free Mobile](http://mobile.free.fr) API.

![SMS notifications](img/sms_notifications.jpg)  

### Get the library
Install the latest version of **Free Mobile for Yii** with [Composer](https://getcomposer.org):

```shell
composer require cedx/yii2-free-mobile
```

For detailed instructions, see the [installation guide](installation.md).
