<?php
declare(strict_types=1);
namespace yii\freemobile;

use GuzzleHttp\Psr7\{Uri};
use Psr\Http\Message\{UriInterface};
use yii\base\{Component, InvalidConfigException, InvalidParamException, InvalidValueException};
use yii\helpers\{Json};
use yii\httpclient\{Client as HttpClient, CurlTransport};
use yii\web\{ServerErrorHttpException};

/**
 * Sends messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 * @property UriInterface $endPoint The URL of the API end point.
 */
class Client extends Component implements \JsonSerializable {

  /**
   * @var string The URL of the default API end point.
   */
  const DEFAULT_ENDPOINT = 'https://smsapi.free-mobile.fr';

  /**
   * @var string An event that is triggered when a response is received from the remote service.
   */
  const EVENT_AFTER_SEND = HttpClient::EVENT_AFTER_SEND;

  /**
   * @var string An event that is triggered when a request is made to the remote service.
   */
  const EVENT_BEFORE_SEND = HttpClient::EVENT_BEFORE_SEND;

  /**
   * @var string The identification key associated to the account.
   */
  public $password = '';

  /**
   * @var string The user name associated to the account.
   */
  public $username = '';

  /**
   * @var Uri The URL of the API end point.
   */
  private $endPoint;

  /**
   * @var HttpClient The underlying HTTP client.
   */
  private $httpClient;

  /**
   * Initializes a new instance of the class.
   * @param array $config Name-value pairs that will be used to initialize the object properties.
   */
  public function __construct(array $config = []) {
    $this->httpClient = \Yii::createObject([
      'class' => HttpClient::class,
      'transport' => CurlTransport::class
    ]);

    $this->httpClient->on(HttpClient::EVENT_BEFORE_SEND, function($event) {
      $this->trigger(static::EVENT_BEFORE_SEND, $event);
    });

    $this->httpClient->on(HttpClient::EVENT_AFTER_SEND, function($event) {
      $this->trigger(static::EVENT_AFTER_SEND, $event);
    });

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
   * Gets the URL of the API end point.
   * @return UriInterface The URL of the API end point.
   */
  public function getEndPoint() {
    return $this->endPoint;
  }

  /**
   * Initializes the object.
   * @throws InvalidConfigException The account credentials are invalid.
   */
  public function init() {
    parent::init();
    if (!mb_strlen($this->username) || !mb_strlen($this->password)) throw new InvalidConfigException('The account credentials are invalid.');
    if (!$this->getEndPoint()) $this->setEndPoint(static::DEFAULT_ENDPOINT);
  }

  /**
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  public function jsonSerialize(): \stdClass {
    return (object) [
      'endPoint' => ($endPoint = $this->getEndPoint()) ? (string) $endPoint : null,
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
   * @throws ServerErrorHttpException An error occurred while sending the message.
   */
  public function sendMessage(string $text) {
    $message = trim($text);
    if (!mb_strlen($message)) throw new InvalidParamException('The specified message is empty.');

    try {
      $queryParams = [
        'msg' => mb_substr($message, 0, 160),
        'pass' => $this->password,
        'user' => $this->username
      ];

      $uri = $this->getEndPoint()->withPath('/sendmsg');
      $response = $this->httpClient->get((string) $uri, $queryParams)->send();
      if (!$response->isOk) throw new InvalidValueException($response->statusCode);
    }

    catch (\Throwable $e) {
      throw new ServerErrorHttpException($e->getMessage());
    }
  }

  /**
   * Sets the URL of the API end point.
   * @param string|UriInterface $value The new URL of the API end point.
   * @return Client This instance.
   */
  public function setEndPoint($value): self {
    if ($value instanceof UriInterface) $this->endPoint = $value;
    else if (is_string($value)) $this->endPoint = new Uri($value);
    else $this->endPoint = null;

    return $this;
  }
}
