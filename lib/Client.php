<?php
namespace yii\freemobile;

use yii\base\{Component, InvalidConfigException, InvalidParamException, InvalidValueException};
use yii\helpers\{Json};
use yii\httpclient\{Client as HTTPClient, CurlTransport};
use yii\web\{ServerErrorHttpException};

/**
 * Sends messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 */
class Client extends Component implements \JsonSerializable {

  /**
   * @var string The URL of the default API end point.
   */
  const DEFAULT_ENDPOINT = 'https://smsapi.free-mobile.fr';

  /**
   * @var string An event that is triggered when a response is received from the remote service.
   */
  const EVENT_AFTER_SEND = HTTPClient::EVENT_AFTER_SEND;

  /**
   * @var string An event that is triggered when a request is made to the remote service.
   */
  const EVENT_BEFORE_SEND = HTTPClient::EVENT_BEFORE_SEND;

  /**
   * @var string The URL of the API end point.
   */
  public $endPoint = self::DEFAULT_ENDPOINT;

  /**
   * @var string The identification key associated to the account.
   */
  public $password = '';

  /**
   * @var string The user name associated to the account.
   */
  public $username = '';

  /**
   * @var HTTPClient The underlying HTTP client.
   */
  private $httpClient;

  /**
   * Initializes a new instance of the class.
   * @param array $config Name-value pairs that will be used to initialize the object properties.
   */
  public function __construct(array $config = []) {
    $this->httpClient = new HTTPClient(['transport' => CurlTransport::class]);
    parent::__construct($config);
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
   * Initializes the object.
   * @throws InvalidConfigException The account credentials are invalid.
   */
  public function init() {
    parent::init();
    if (!mb_strlen($this->username) || !mb_strlen($this->password)) throw new InvalidConfigException('The account credentials are invalid.');

    $this->httpClient->on(HTTPClient::EVENT_BEFORE_SEND, function($event) {
      $this->trigger(static::EVENT_BEFORE_SEND, $event);
    });

    $this->httpClient->on(HTTPClient::EVENT_AFTER_SEND, function($event) {
      $this->trigger(static::EVENT_AFTER_SEND, $event);
    });
  }

  /**
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  public function jsonSerialize(): \stdClass {
    return (object) [
      'endPoint' => $this->endPoint,
      'password' => $this->password,
      'username' => $this->username
    ];
  }

  /**
   * Sends a SMS message to the underlying account.
   * @param string $text The text of the message to send.
   * @emits \yii\httpclient\RequestEvent The "beforeSend" event.
   * @emits \yii\httpclient\RequestEvent The "afterSend" event.
   * @throws InvalidParamException The specified message is empty.
   * @throws ServerErrorHttpException An error occurred while querying the end point.
   */
  public function sendMessage(string $text) {
  }
}
