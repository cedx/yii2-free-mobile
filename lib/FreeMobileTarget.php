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
class FreeMobileTarget extends Target implements \JsonSerializable {

  /**
   * @var int How many messages should be accumulated before they are exported.
   */
  public $exportInterval = 1;

  /**
   * @var array The list of the PHP predefined variables that should be logged in a message.
   */
  public $logVars = [];

  /**
   * @var Client The underlying client used to send the messages.
   */
  private $client;

  /**
   * Initializes a new instance of the class.
   * @param array $config Name-value pairs that will be used to initialize the object properties.
   */
  public function __construct($config = []) {
    $this->client = new Client();
    parent::__construct($config);
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  public function __toString(): string {
    $json = json_encode($this, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    return static::class." {$json}";
  }

  /**
   * Exports log messages to a specific destination.
   */
  public function export() {
    $encoding = mb_internal_encoding();
    mb_internal_encoding(\Yii::$app->charset);

    $restoreEncoding = function() use ($encoding) {
      mb_internal_encoding($encoding);
    };

    $this->client
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

  /**
   * Gets the identification key associated to the account.
   * @return string The identification key associated to the account.
   */
  public function getPassword(): string {
    return $this->client->getPassword();
  }

  /**
   * Gets the user name associated to the account.
   * @return string The user name associated to the account.
   */
  public function getUsername(): string {
    return $this->client->getUsername();
  }

  /**
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  public function jsonSerialize(): \stdClass {
    return (object) [
      'categories' => $this->categories,
      'enabled' => $this->enabled,
      'except' => $this->except,
      'exportInterval' => $this->exportInterval,
      'levels' => $this->getLevels(),
      'logVars' => $this->logVars,
      'messages' => $this->messages,
      'password' => $this->client->getPassword(),
      'username' => $this->client->getUsername()
    ];
  }

  /**
   * Sets the identification key associated to the account.
   * @param string $value The new identification key.
   * @return FreeMobileTarget This instance.
   */
  public function setPassword(string $value): self {
    $this->client->setPassword($value);
    return $this;
  }

  /**
   * Sets the user name associated to the account.
   * @param string $value The new username.
   * @return FreeMobileTarget This instance.
   */
  public function setUsername(string $value): self {
    $this->client->setUsername($value);
    return $this;
  }
}
