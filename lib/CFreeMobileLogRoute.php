<?php
/**
 * Implementation of the `CFreeMobileLogRoute` class.
 * @module CFreeMobileLogRoute
 */

/**
 * Sends the log messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 * @class CFreeMobileLogRoute
 * @extends system.logging.CLogRoute
 * @constructor
 */
class CFreeMobileLogRoute extends CLogRoute {

  /**
   * The URL of the API end point.
   * @property END_POINT_URL
   * @type string
   * @static
   * @final
   */
  const END_POINT_URL='https://smsapi.free-mobile.fr/sendmsg';

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
   * Processes log messages and sends them by SMS to a Free Mobile account.
   * @method processLogs
   * @param {array} $logs The list of messages.
   */
  protected function processLogs($logs) {
    $text=implode("\n", array_map(function($log) {
      return sprintf('[%s] [%s] %s', $log[1], $log[2], $log[0]);
    }, $logs));

    $fields=[
      'msg'=>mb_convert_encoding($text, 'ISO-8859-1', Yii::app()->charset),
      'pass'=>$this->password,
      'user'=>$this->userName
    ];

    $resource=null;
    $url=static::END_POINT_URL.'?'.http_build_query($fields, '', '&', PHP_QUERY_RFC3986);

    try {
      $resource=curl_init($url);
      if(!$resource) throw new CException('Resource not found.');

      if(!curl_setopt_array($resource, [
        CURLOPT_ENCODING=>'',
        CURLOPT_FAILONERROR=>true,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_TIMEOUT=>5000,
        CURLOPT_SSL_VERIFYPEER=>false
      ])) throw new CException(curl_error($resource));

      $response=curl_exec($resource);
      if($response===false) throw new CException(curl_error($resource));
      curl_close($resource);
    }

    catch(CException $e) {
      if($resource) curl_close($resource);
    }
  }
}
