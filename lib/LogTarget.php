<?php
declare(strict_types=1);
namespace yii\freemobile;

use yii\helpers\{VarDumper};
use yii\log\{Logger, Target};

/**
 * Sends the log messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 * @property Client $client The component used to send messages.
 */
class LogTarget extends Target {

  /**
   * @var Client The underlying client used to send the messages.
   */
  private $client;

  /**
   * Initializes a new instance of the class.
   * @param array $config Name-value pairs that will be used to initialize the object properties.
   */
  public function __construct(array $config = []) {
    $this->exportInterval = 1;
    $this->logVars = [];
    parent::__construct($config);
  }

  /**
   * Exports log messages to a specific destination.
   */
  public function export(): void {
    $previousEncoding = mb_internal_encoding();
    mb_internal_encoding(\Yii::$app->charset);
    $this->getClient()->sendMessage(implode("\n", array_map([$this, 'formatMessage'], $this->messages)));
    mb_internal_encoding($previousEncoding);
  }

  /**
   * Formats a log message for display as a string.
   * @param array $message The log message to be formatted.
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
   * Gets the client used to send messages.
   * @return Client The component used to send messages.
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * Initializes the object.
   */
  public function init(): void {
    parent::init();
    if (!$this->getClient()) $this->setClient('freemobile');
  }

  /**
   * Sets the client used to send messages.
   * @param Client|string $value The component to use for sending messages.
   * @return LogTarget This instance.
   */
  public function setClient($value): self {
    $this->client = is_string($value) ? \Yii::$app->get($value) : $value;
    return $this;
  }
}
