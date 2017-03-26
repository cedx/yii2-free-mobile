<?php
namespace yii\freemobile;

use freemobile\{Client as FreeMobileClient};
use yii\base\{Component};
use yii\helpers\{Json};

/**
 * Sends messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 * @property string $endPoint The URL of the API end point.
 * @property string $password The identification key associated to the account.
 * @property string $username The user name associated to the account.
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

    $this->client->on('request', function($request) {
      $this->trigger(static::EVENT_REQUEST, new RequestEvent(['request' => $request]));
    });

    $this->client->on('response', function($response) {
      $this->trigger(static::EVENT_RESPONSE, new ResponseEvent(['response' => $response]));
    });
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  public function __toString(): string {
    $json = Json::encode($this);
    return static::class." $json";
  }

  /**
   * Gets the URL of the API end point.
   * @return string The URL of the API end point.
   */
  public function getEndPoint(): string {
    return $this->client->getEndPoint();
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
   */
  public function sendMessage(string $text) {
    $this->client->sendMessage($text);
  }

  /**
   * Sets the URL of the API end point.
   * @param string $value The new URL of the API end point.
   * @return Client This instance.
   */
  public function setEndPoint(string $value) {
    $this->client->setEndPoint($value);
    return $this;
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
