<?php
/**
 * Implementation of the `yii\log\FreeMobileTarget` class.
 */
namespace yii\log;

use GuzzleHttp\{Client};
use yii\helpers\{VarDumper};

/**
 * Sends the log messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 */
class FreeMobileTarget extends Target {

  /**
   * @var string The URL of the API end point.
   */
  public $endPoint = 'https://smsapi.free-mobile.fr/sendmsg';

  /**
   * @var array The list of the PHP predefined variables that should be logged in a message.
   */
  public $logVars = [];

  /**
   * @var string The identification key associated to the account.
   */
  public $password = '';

  /**
   * @var string The user name associated to the account.
   */
  public $userName = '';

  /**
   * Exports log messages to a specific destination.
   */
  public function export() {
    $text = implode("\n", array_map([$this, 'formatMessage'], $this->messages));
    $encoded = mb_convert_encoding($text, 'ISO-8859-1', \Yii::$app->charset);

    $options = ['query' => [
      'msg' => substr($encoded, 0, 160),
      'pass' => $this->password,
      'user' => $this->userName
    ]];

    $promise = (new Client())->getAsync($this->endPoint, $options);
    $promise->then()->wait();
  }

  /**
   * Formats a log message for display as a string.
   * @param string $message The log message to be formatted.
   * @return string The formatted message.
   */
  public function formatMessage($message): string {
    list($text, $level, $category) = $message;
    return strtr('[{level}@{category}] {text}', [
      '{category}' => $category,
      '{level}' => Logger::getLevelName($level),
      '{text}' => is_string($text) ? $text : VarDumper::export($text)
    ]);
  }
}
