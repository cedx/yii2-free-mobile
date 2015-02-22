<?php
/**
 * Implementation of the `belin\log\FreeMobileLogRoute` class.
 * @module log.FreeMobileLogRoute
 */
namespace belin\log;

/**
 * Sends the log messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 * @class belin.log.FreeMobileLogRoute
 * @extends system.logging.CLogRoute
 * @constructor
 */
class FreeMobileLogRoute extends \CLogRoute {

  /**
   * The URL of the API end point.
   * @property END_POINT_URL
   * @type string
   * @static
   * @final
   */
  const END_POINT_URL='https://smsapi.free-mobile.fr/sendmsg';

  /**
   * The format string to be used to format a log message.
   * The following placeholders can be specified:
   * - `{message}`: replaced by the message content.
   * - `{level}`: replaced by the message level.
   * - `{category}`: replaced by the message category.
   * - `{time}`: replaced by the message timestamp (using the `"Y/m/d H:i:s"` date format).
   * @property logFormat
   * @type string
   * @default "[{level}@{category}] {message}"
   */
  public $logFormat='[{level}@{category}] {message}';

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
   * Formats a log message given different fields.
   * @method formatLogMessage
   * @param {string} $message The message content.
   * @param {integer} $level The message level.
   * @param {string} $category The message category.
   * @param {integer} $time The message timestamp.
   * @return {string} The formatted message.
   * @protected
   */
  protected function formatLogMessage($message, $level, $category, $time) {
    $values=[
      '{category}'=>$category,
      '{level}'=>$level,
      '{message}'=>$message,
      '{time}'=>@date('Y/m/d H:i:s', $time)
    ];

    return strtr($this->logFormat, $values);
  }

  /**
   * Processes log messages and sends them by SMS to a Free Mobile account.
   * @method processLogs
   * @param {array} $logs The list of messages.
   * @protected
   */
  protected function processLogs($logs) {
    $text=implode("\n", array_map(function($log) {
      return $this->formatLogMessage($log[0], $log[1], $log[2], $log[3]);
    }, $logs));

    $fields=[
      'msg'=>mb_convert_encoding($text, 'ISO-8859-1', \Yii::app()->charset),
      'pass'=>$this->password,
      'user'=>$this->userName
    ];

    $resource=null;
    $url=static::END_POINT_URL.'?'.http_build_query($fields, '', '&', PHP_QUERY_RFC3986);

    try {
      $resource=curl_init($url);
      if(!$resource) throw new \CException('Resource not found.');

      if(!curl_setopt_array($resource, [
        CURLOPT_ENCODING=>'',
        CURLOPT_FAILONERROR=>true,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_TIMEOUT=>5000,
        CURLOPT_SSL_VERIFYPEER=>false
      ])) throw new \CException(curl_error($resource));

      $response=curl_exec($resource);
      if($response===false) throw new \CException(curl_error($resource));
      curl_close($resource);
    }

    catch(\CException $e) {
      if($resource) curl_close($resource);
    }
  }
}
