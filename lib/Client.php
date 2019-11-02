<?php declare(strict_types=1);
namespace yii\freemobile;

use function GuzzleHttp\Psr7\{build_query};
use GuzzleHttp\Psr7\{Uri, UriResolver};
use Psr\Http\Message\{UriInterface};
use yii\base\{Component, InvalidArgumentException, InvalidConfigException};
use yii\httpclient\{Client as HttpClient, CurlTransport};
use yii\web\{HttpException};

/**
 * Sends messages by SMS to a Free Mobile account.
 * @property UriInterface $endPoint The URL of the API end point.
 */
class Client extends Component {

  /** @var string An event that is triggered when a request is made to the remote service. */
  const eventRequest = 'request';

  /** @var string An event that is triggered when a response is received from the remote service. */
  const eventResponse = 'response';

  /** @var string The identification key associated to the account. */
  public string $password = '';

  /** @var string The user name associated to the account. */
  public string $username = '';

  /** @var UriInterface The URL of the API end point. */
  private UriInterface $endPoint;

  /** @var HttpClient The underlying HTTP client. */
  private HttpClient $httpClient;

  /**
   * Creates a new client.
   * @param array $config Name-value pairs that will be used to initialize the object properties.
   */
  function __construct(array $config = []) {
    $this->endPoint = new Uri('https://smsapi.free-mobile.fr/');
    $this->httpClient = new HttpClient(['transport' => CurlTransport::class]);
    $this->httpClient->on(HttpClient::EVENT_BEFORE_SEND, fn($event) => $this->trigger(static::eventRequest, $event));
    $this->httpClient->on(HttpClient::EVENT_AFTER_SEND, fn($event) => $this->trigger(static::eventResponse, $event));
    parent::__construct($config);
  }

  /**
   * Gets the URL of the API end point.
   * @return UriInterface The URL of the API end point.
   */
  function getEndPoint(): UriInterface {
    return $this->endPoint;
  }

  /**
   * Initializes this object.
   * @throws InvalidConfigException The account credentials are invalid.
   */
  function init(): void {
    parent::init();
    if (!mb_strlen($this->username) || !mb_strlen($this->password)) throw new InvalidConfigException('The account credentials are invalid.');
  }

  /**
   * Sends a SMS message to the underlying account.
   * @param string $text The text of the message to send.
   * @throws InvalidArgumentException The specified message is empty.
   * @throws ClientException An error occurred while sending the message.
   */
  function sendMessage(string $text): void {
    $message = trim($text);
    if (!mb_strlen($message)) throw new InvalidArgumentException('The specified message is empty.');

    $uri = UriResolver::resolve($this->endPoint, new Uri('sendmsg'))->withQuery(build_query([
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
   * @param string|UriInterface $value The new URL.
   * @return $this This instance.
   */
  function setEndPoint($value): self {
    $this->endPoint = new Uri((string) $value);
    return $this;
  }
}
