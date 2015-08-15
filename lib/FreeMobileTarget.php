<?php
/**
 * @file
 * Implementation of the `yii\log\FreeMobileTarget` class.
 */
namespace yii\log;

// Dependencies.
use yii\helpers\VarDumper;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Sends the log messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 */
class FreeMobileTarget extends Target {

  /**
   * @var string $endPoint
   * The URL of the API end point.
   */
  public $endPoint='https://smsapi.free-mobile.fr/sendmsg';

  /**
   * @var array $logVars
   * The list of the PHP predefined variables that should be logged in a message.
   */
  public $logVars=[];

  /**
   * @var string $password
   * The identification key associated to the account.
   */
  public $password='';

  /**
   * @var string $userName
   * The user name associated to the account.
   */
  public $userName='';

  /**
   * Exports log messages to a specific destination.
   */
  public function export() {
    $resource=null;
    $text=implode("\n", array_map([ $this, 'formatMessage' ], $this->messages));

    $fields=[
      'msg'=>mb_convert_encoding(mb_substr($text, 0, 160), 'ISO-8859-1', \Yii::$app->charset),
      'pass'=>$this->password,
      'user'=>$this->userName
    ];

    try {
      $resource=curl_init($this->endPoint.'?'.http_build_query($fields, '', '&', PHP_QUERY_RFC3986));
      if(!$resource) throw new NotFoundHttpException('Resource not found.');

      if(!curl_setopt_array($resource, [
        CURLOPT_ENCODING=>'',
        CURLOPT_FAILONERROR=>true,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_TIMEOUT=>5000,
        CURLOPT_SSL_VERIFYPEER=>false
      ])) throw new ServerErrorHttpException(curl_error($resource));

      $response=curl_exec($resource);
      if($response===false) throw new ServerErrorHttpException(curl_error($resource));
      curl_close($resource);
    }

    catch(HttpException $e) {}
    finally { if($resource) curl_close($resource); }
  }

  /**
   * Formats a log message for display as a string.
   * @param array $message The log message to be formatted.
   * @return string The formatted message.
   */
  public function formatMessage($message) {
    list($text, $level, $category)=$message;
    return strtr('[{level}@{category}] {text}', [
      '{category}'=>$category,
      '{level}'=>Logger::getLevelName($level),
      '{text}'=>is_string($text) ? $text : VarDumper::export($text)
    ]);
  }
}
