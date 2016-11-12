<?php
/**
 * Implementation of the `yii\log\FreeMobileTarget` class.
 */
namespace yii\log;

use freemobile\{Client};
use yii\helpers\{VarDumper};

/**
 * Sends the log messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 */
class FreeMobileTarget extends Target {

  /**
   * @var int How many messages should be accumulated before they are exported.
   */
  public $exportInterval = 1;

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
  public $username = '';

  /**
   * Exports log messages to a specific destination.
   */
  public function export() {
    $encoding = mb_internal_encoding();
    mb_internal_encoding(\Yii::$app->charset);

    $restoreEncoding = function() use ($encoding) {
      mb_internal_encoding($encoding);
    };

    (new Client($this->username, $this->password))
      ->sendMessage(implode("\n", array_map([$this, 'formatMessage'], $this->messages)))
      ->subscribeCallback(null, $restoreEncoding, $restoreEncoding);
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
