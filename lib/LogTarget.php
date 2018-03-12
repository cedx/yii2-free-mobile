<?php
declare(strict_types=1);
namespace yii\freemobile;

use yii\base\{InvalidConfigException};
use yii\di\{Instance};
use yii\helpers\{VarDumper};
use yii\log\{Target};

/**
 * Sends the log messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 * @property Client $client The component used to send messages.
 */
class LogTarget extends Target {

  /**
   * @var array|string|Client The Free Mobile client or the application component ID of the Free Mobile client.
   */
  public $client = 'freemobile';

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
    $this->client->sendMessage(implode("\n", array_map([$this, 'formatMessage'], $this->messages)));
  }

  /**
   * Formats a log message for display as a string.
   * @param array $message The log message to be formatted.
   * @return string The formatted message.
   */
  public function formatMessage($message): string {
    list($text,, $category) = $message;
    return sprintf('[%s] %s', $category, is_string($text) ? $text : VarDumper::export($text));
  }

  /**
   * Initializes the object.
   * @throws InvalidConfigException The client component is not properly configured.
   */
  public function init(): void {
    parent::init();
    $this->client = Instance::ensure($this->client, Client::class);
  }
}
