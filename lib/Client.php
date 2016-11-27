<?php
/**
 * Implementation of the `yii\freemobile\Client` class.
 */
namespace yii\freemobile;

use freemobile\{Client as FreeMobileClient};
use yii\base\{Component};

/**
 * Sends messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 */
class Client extends Component implements \JsonSerializable {

  /**
   * @var string An event that is triggered when a request is made to the remote service.
   */
  const EVENT_REQUEST = 'request';

  /**
   * @var string An event that is triggered when a response is received from the remote service.
   */
  const EVENT_RESPONSE = 'reponse';

  /**
   * @var string The underlying Free Mobile client.
   */
  private $client;

  /**
   * Initializes a new instance of the class.
   * @param array $config Name-value pairs that will be used to initialize the object properties.
   */
  public function __construct(array $config = []) {
    $this->client = new FreeMobileClient();
    parent::__construct($config);

    $this->client->onRequest()->subscribeCallback(function($request) {
      $this->trigger(static::EVENT_REQUEST, \Yii::createObject(['class' => RequestEvent::class, 'request' => $request]));
    });

    $this->client->onResponse()->subscribeCallback(function($response) {
      $this->trigger(static::EVENT_RESPONSE, \Yii::createObject(['class' => ResponseEvent::class, 'response' => $response]));
    });
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
    return $this->client->jsonSerialize();
  }

  /**
   * Sends a SMS message to the underlying account.
   * @param string $text The text of the message to send.
   * @return bool Whether the operation was successful.
   */
  public function sendMessage(string $text): bool {
    $this->client->sendMessage($text)->subscribeCallback(
      function() use (&$result) { $result = true; },
      function() use (&$result) { $result = false; }
    );

    return $result;
  }

  /**
   * Sets the identification key associated to the account.
   * @param string $value The new identification key.
   * @return Client This instance.
   */
  public function setPassword(string $value): self {
    $this->client->setPassword($value);
    return $this;
  }

  /**
   * Sets the user name associated to the account.
   * @param string $value The new username.
   * @return Client This instance.
   */
  public function setUsername(string $value): self {
    $this->client->setUsername($value);
    return $this;
  }
}
