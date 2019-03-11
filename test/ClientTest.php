<?php
declare(strict_types=1);
namespace yii\freemobile;

use League\Uri\{Http as Uri};
use PHPUnit\Framework\{TestCase};
use yii\base\{InvalidArgumentException, InvalidConfigException};

/**
 * Tests the features of the `yii\freemobile\Client` class.
 */
class ClientTest extends TestCase {

  /**
   * Tests the `Client::init()` method.
   */
  function testInit(): void {
    // It should throw an exception if the username or password is empty.
    $this->expectException(InvalidConfigException::class);
    new Client;
  }

  /**
   * Tests the `Client::sendMessage()` method.
   * @test
   */
  function testSendMessage(): void {
    // It should not send invalid messages with valid credentials.
    try {
      (new Client(['username' => 'anonymous', 'password' => 'secret']))->sendMessage('');
      $this->fail('An empty message with credentials should not be sent');
    }

    catch (\Throwable $e) {
      assertThat($e, isInstanceOf(InvalidArgumentException::class));
    }

    // It should throw a `ClientException` if a network error occurred.
    try {
      (new Client(['username' => 'anonymous', 'password' => 'secret', 'endPoint' => 'http://localhost']))->sendMessage('Hello World!');
      $this->fail('A message with an invalid endpoint should not be sent');
    }

    catch (\Throwable $e) {
      assertThat($e, isInstanceOf(ClientException::class));
    }

    // It should send valid messages with valid credentials.
    if (is_string($username = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
      try {
        (new Client(['username' => $username, 'password' => $password]))->sendMessage('Bonjour Cédric !');
        assertThat(true, isTrue());
      }

      catch (\Throwable $e) {
        assertThat($e, isInstanceOf(ClientException::class));
      }
    }
  }

  /**
   * Tests the `Client::setEndPoint()` method.
   * @test
   */
  function testSetEndPoint(): void {
    $client = new Client(['username' => 'anonymous', 'password' => 'secret']);

    // It should not be empty by default.
    assertThat($client->endPoint, isInstanceOf(Uri::class));
    assertThat((string) $client->endPoint, equalTo('https://smsapi.free-mobile.fr'));

    // It should be an instance of the `Uri` class.
    $client->setEndPoint('http://localhost');
    assertThat($client->endPoint, isInstanceOf(Uri::class));
    assertThat((string) $client->endPoint, equalTo('http://localhost'));
  }
}
