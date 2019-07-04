<?php declare(strict_types=1);
namespace yii\freemobile;

use GuzzleHttp\Psr7\{Uri};
use PHPUnit\Framework\{TestCase};
use yii\base\{InvalidArgumentException, InvalidConfigException};

/** Tests the features of the `yii\freemobile\Client` class. */
class ClientTest extends TestCase {

  /** @test Client->init() */
  function testInit(): void {
    // It should throw an exception if the username or password is empty.
    $this->expectException(InvalidConfigException::class);
    new Client;
  }

  /** @test Client->sendMessage() */
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
    try {
      $config = ['username' => getenv('FREEMOBILE_USERNAME'), 'password' => getenv('FREEMOBILE_PASSWORD')];
      (new Client($config))->sendMessage('Bonjour Cédric, à partir du Yii Framework !');
      assertThat(true, isTrue());
    }

    catch (\Throwable $e) {
      $this->fail($e->getMessage());
    }
  }
}
