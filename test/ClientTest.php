<?php declare(strict_types=1);
namespace yii\freemobile;

use GuzzleHttp\Psr7\{Uri};
use PHPUnit\Framework\{TestCase};
use yii\base\{InvalidArgumentException, InvalidConfigException};

/** Tests the features of the `yii\freemobile\Client` class. */
class ClientTest extends TestCase {

  /** @test Tests the `Client::init()` method. */
  function testInit(): void {
    // It should throw an exception if the username or password is empty.
    $this->expectException(InvalidConfigException::class);
    new Client;
  }

  /** @test Tests the `Client::sendMessage()` method. */
  function testSendMessage(): void {
    // It should not send invalid messages with valid credentials.
    try {
      (new Client(['username' => 'anonymous', 'password' => 'secret']))->sendMessage('');
      $this->fail('Exception not thrown.');
    }

    catch (\Throwable $e) {
      assertThat($e, isInstanceOf(InvalidArgumentException::class));
    }

    // It should throw a `ClientException` if a network error occurred.
    try {
      $config = ['username' => 'anonymous', 'password' => 'secret', 'endPoint' => new Uri('http://localhost/')];
      (new Client($config))->sendMessage('Hello World!');
      $this->fail('Exception not thrown.');
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
        $this->fail($e->getMessage());
      }
    }
  }
}
