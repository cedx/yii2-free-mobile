<?php
declare(strict_types=1);
namespace yii\freemobile;

use GuzzleHttp\Psr7\{Uri};
use Psr\Http\Message\{UriInterface};
use yii\base\{Component, InvalidArgumentException, InvalidConfigException, InvalidValueException};
use yii\httpclient\{Client as HttpClient, CurlTransport};
use yii\web\{ServerErrorHttpException};

/**
 * Sends messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 * @property UriInterface $endPoint The URL of the API end point.
 */
class Client extends Component {

  /**
   * @var string An event that is triggered when a request is made to the remote service.
   */
  public const EVENT_REQUEST = 'request';

  /**
   * @var string An event that is triggered when a response is received from the remote service.
   */
  public const EVENT_RESPONSE = 'response';

  /**
   * @var string The URL of the default API end point.
   */
  private const DEFAULT_ENDPOINT = 'https://smsapi.free-mobile.fr';

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
   * @return UriInterface The URL of the API end point.
   */
  public function getEndPoint(): ?UriInterface {
    return $this->endPoint;
  }

  /**
   * Initializes the object.
   * @throws InvalidConfigException The account credentials are invalid.
   */
  public function init(): void {
    parent::init();
    if (!mb_strlen($this->username) || !mb_strlen($this->password)) throw new InvalidConfigException('The account credentials are invalid');
    if (!$this->getEndPoint()) $this->setEndPoint(static::DEFAULT_ENDPOINT);
  }
  /**
   * Sends a SMS message to the underlying account.
   * @param string $text The text of the message to send.
   * @throws InvalidArgumentException The specified message is empty.
   * @throws ClientException An error occurred while sending the message.
   */
  public function sendMessage(string $text): void {
    $message = trim($text);
    if (!mb_strlen($message)) throw new InvalidArgumentException('The specified message is empty');

    $uri = $this->getEndPoint()->withPath('/sendmsg')->withQuery(http_build_query([
      'msg' => mb_substr($message, 0, 160),
      'pass' => $this->password,
      'user' => $this->username
    ]));

    try {
      $response = $this->httpClient->get((string) $uri)->send();
      if (!$response->isOk) throw new InvalidValueException($response->content, (int) $response->statusCode);
    }

    catch (\Throwable $e) {
      throw new ClientException($e->getMessage(), $uri, $e);
    }
  }

  /**
   * Sets the URL of the API end point.
   * @param string|UriInterface $value The new URL of the API end point.
   * @return Client This instance.
   */
  public function setEndPoint($value): self {
    $this->endPoint = is_string($value) ? new Uri($value) : $value;
    return $this;
  }
}
