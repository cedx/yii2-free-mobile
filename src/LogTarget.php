<?php declare(strict_types=1);
namespace yii\freemobile;

use yii\di\{Instance};
use yii\helpers\{VarDumper};
use yii\log\{Target};

/**
 * Sends the log messages by SMS to a Free Mobile account.
 */
class LogTarget extends Target {

  /**
   * @var array|string|Client The Free Mobile client or the application component ID of the Free Mobile client.
   */
  public $client = 'freemobile';

  /**
   * Creates a new log target.
   * @param array $config Name-value pairs that will be used to initialize the object properties.
   */
  function __construct(array $config = []) {
    $this->exportInterval = 1;
    $this->logVars = [];
    parent::__construct($config);
  }

  /**
   * Exports log messages to a specific destination.
   */
  function export(): void {
    /** @var Client $client */
    $client = $this->client;
    $client->sendMessage(implode("\n", array_map([$this, 'formatMessage'], $this->messages)));
  }

  /**
   * Formats a log message for display as a string.
   * @param array $message The log message to be formatted.
   * @return string The formatted message.
   */
  function formatMessage($message): string {
    [$text, $level, $category, $timestamp] = $message;
    return sprintf('[%s] %s', $category, is_string($text) ? $text : VarDumper::export($text));
  }

  /**
   * Initializes the object.
   */
  function init(): void {
    parent::init();
    if (!$this->client instanceof Client) {
      /** @var Client $client */
      $client = Instance::ensure($this->client, Client::class);
      $this->client = $client;
    }
  }
}
