<?php
declare(strict_types=1);
namespace yii\freemobile;

use League\Uri\{Http as Uri};
use yii\base\{Component, InvalidArgumentException, InvalidConfigException};
use yii\httpclient\{Client as HttpClient, CurlTransport};
use yii\web\{HttpException};

/**
 * Sends messages by SMS to a Free Mobile account.
 * @property Uri $endPoint The URL of the API end point.
 */
class Client extends Component {

  /**
   * @var string An event that is triggered when a request is made to the remote service.
   */
  const EVENT_REQUEST = 'request';

  /**
   * @var string An event that is triggered when a response is received from the remote service.
   */
  const EVENT_RESPONSE = 'response';

  /**
   * @var string The identification key associated to the account.
   */
  public $password = '';

  /**
   * @var string The user name associated to the account.
   */
  public $username = '';

  /**
   * @var Uri|null The URL of the API end point.
   */
  private $endPoint;

  /**
   * @var HttpClient The underlying HTTP client.
   */
  private $httpClient;

  /**
   * Creates a new client.
   * @param array $config Name-value pairs that will be used to initialize the object properties.
   */
  function __construct(array $config = []) {
    $this->httpClient = new HttpClient(['transport' => CurlTransport::class]);

    $this->httpClient->on(HttpClient::EVENT_BEFORE_SEND, function($event) {
      $this->trigger(static::EVENT_REQUEST, $event);
    });

    $this->httpClient->on(HttpClient::EVENT_AFTER_SEND, function($event) {
      $this->trigger(static::EVENT_RESPONSE, $event);
    });

    parent::__construct($config);
  }

  /**
   * Gets the URL of the API end point.
   * @return Uri|null The URL of the API end point.
   */
  function getEndPoint(): ?Uri {
    return $this->endPoint;
  }

  /**
   * Initializes the object.
   * @throws InvalidConfigException The account credentials are invalid.
   */
  function init(): void {
    parent::init();
    if (!mb_strlen($this->username) || !mb_strlen($this->password)) throw new InvalidConfigException('The account credentials are invalid');
    if (!$this->getEndPoint()) $this->setEndPoint('https://smsapi.free-mobile.fr');
  }
  /**
   * Sends a SMS message to the underlying account.
   * @param string $text The text of the message to send.
   * @throws InvalidArgumentException The specified message is empty.
   * @throws ClientException An error occurred while sending the message.
   */
  function sendMessage(string $text): void {
    $message = trim($text);
    if (!mb_strlen($message)) throw new InvalidArgumentException('The specified message is empty');

    /** @var Uri $endPoint */
    $endPoint = $this->getEndPoint();
    $uri = $endPoint->withPath('/sendmsg')->withQuery(http_build_query([
      'msg' => mb_substr($message, 0, 160),
      'pass' => $this->password,
      'user' => $this->username
    ]));

    try {
      $response = $this->httpClient->get((string) $uri)->send();
      if (!$response->isOk) throw new HttpException((int) $response->statusCode, $response->content);
    }

    catch (\Throwable $e) {
      throw new ClientException($e->getMessage(), $uri, $e);
    }
  }

  /**
   * Sets the URL of the API end point.
   * @param Uri|string $value The new URL of the API end point.
   * @return $this This instance.
   */
  function setEndPoint($value): self {
    $this->endPoint = is_string($value) ? Uri::createFromString($value) : $value;
    return $this;
  }
}
