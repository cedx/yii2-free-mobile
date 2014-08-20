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
   * The maximum length of sent messages.
   * Defaults to `0`, meaning no length limit.
   * @property lengthLimit
   * @type int
   * @default 0
   */
  public $lengthLimit=0;

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
    $resource=null;

    $text=implode("\n", array_map(function($log) {
      return $this->formatLogMessage($log[0], $log[1], $log[2], $log[3]);
    }, $logs));

    if($this->lengthLimit>0 && mb_strlen($text)>$this->lengthLimit)
      $text=mb_strimwidth($text, 0, $this->lengthLimit, '...');

    try {
      $resource=curl_init(static::END_POINT_URL);
      if(!$resource) throw new CException('Resource not found.');

      $fields=[
        'msg'=>mb_convert_encoding($text, 'ISO-8859-1', Yii::app()->charset),
        'pass'=>$this->password,
        'user'=>$this->userName
      ];

      if(!curl_setopt_array($resource, [
        CURLOPT_ENCODING=>'',
        CURLOPT_FAILONERROR=>true,
        CURLOPT_POST=>true,
        CURLOPT_POSTFIELDS=>$fields,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_TIMEOUT=>5000,
        CURLOPT_SSL_VERIFYPEER=>false
      ])) throw new CException(curl_error($resource));

      $response=curl_exec($resource);
      if($response===false) throw new CException(curl_error($resource));

      $data=@simplexml_load_string($response);
      if(!$data) throw new CException('Unable to parse the XML response.');

      curl_close($resource);
      return $data;
    }

    catch(CException $e) {
      Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'system.logging.CFreeMobileLogRoute');
    }

    finally {
      if($resource) curl_close($resource);
    }
  }
}
