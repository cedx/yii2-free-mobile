<?php
/**
 * Implementation of the `yii\log\FreeMobileTarget` class.
 * @module FreeMobileTarget
 */
namespace yii\log;

// Module dependencies.
use yii\web\HttpException;

/**
 * Sends the log messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 * @class yii.log.FreeMobileTarget
 * @extends yii.log.Target
 * @constructor
 */
class FreeMobileTarget extends Target {

  /**
   * The URL of the API end point.
   * @property endPoint
   * @type string
   * @default "https://smsapi.free-mobile.fr/sendmsg"
   */
  public $endPoint='https://smsapi.free-mobile.fr/sendmsg';

  /**
   * The identification key associated to the account.
   * @property password
   * @type string
   */
  public $password='';

  /**
   * The user name associated to the account.
   * @property userName
   * @type string
   */
  public $userName='';

  /**
   * Exports log messages to a specific destination.
   * @method export
   */
  public function export() {
    $text=implode("\n", array_map([ $this, 'formatMessage' ], $this->messages));

    $fields=[
      'msg'=>mb_convert_encoding($text, 'ISO-8859-1', \Yii::$app->charset),
      'pass'=>$this->password,
      'user'=>$this->userName
    ];

    $resource=null;
    $url=$this->endPoint.'?'.http_build_query($fields, '', '&', PHP_QUERY_RFC3986);

    try {
      $resource=curl_init($url);
      if(!$resource) throw new HttpException('Resource not found.');

      if(!curl_setopt_array($resource, [
        CURLOPT_ENCODING=>'',
        CURLOPT_FAILONERROR=>true,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_TIMEOUT=>5000,
        CURLOPT_SSL_VERIFYPEER=>false
      ])) throw new HttpException(curl_error($resource));

      $response=curl_exec($resource);
      if($response===false) throw new HttpException(curl_error($resource));
      curl_close($resource);
    }

    catch(HttpException $e) {
      if($resource) curl_close($resource);
      \Yii::error($e->getMessage(), 'yii\\i18n');
    }
  }
}
