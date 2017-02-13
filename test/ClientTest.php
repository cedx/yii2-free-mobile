<?php
/**
 * Implementation of the `yii\freemobile\test\ClientTest` class.
 */
namespace yii\freemobile\test;

use PHPUnit\Framework\{TestCase};
use yii\freemobile\{Client, RequestEvent, ResponseEvent};

/**
 * @coversDefaultClass \yii\freemobile\Client` class.
 */
class ClientTest extends TestCase {

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    $data = (new Client(['username' => 'anonymous', 'password' => 'secret']))->jsonSerialize();

    $this->assertObjectHasAttribute('password', $data);
    $this->assertEquals('secret', $data->password);

    $this->assertObjectHasAttribute('username', $data);
    $this->assertEquals('anonymous', $data->username);
  }

  /**
   * @test ::onRequest
   */
  public function testOnRequest() {
    $client = new Client(['username' => 'anonymous', 'password' => 'secret']);
    $client->on(Client::EVENT_REQUEST, function($request) { $this->assertInstanceOf(RequestEvent::class, $request); });
    $client->sendMessage('FooBar');
  }

  /**
   * @test ::onResponse
   */
  public function testOnResponse() {
    $client = new Client(['username' => 'anonymous', 'password' => 'secret']);
    $client->on(Client::EVENT_RESPONSE, function($response) { $this->assertInstanceOf(ResponseEvent::class, $response); });
    $client->sendMessage('FooBar');
  }

  /**
   * @test ::sendMessage
   */
  public function testSendMessage() {
    $client = new Client(['username' => '', 'password' => '']);
    $this->assertFalse($client->sendMessage('Hello World!'));

    $client = new Client(['username' => 'anonymous', 'password' => 'secret']);
    $this->assertFalse($client->sendMessage(''));

    if (is_string($username = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
      $client = new Client(['username' => $username, 'password' => $password]);
      $this->assertTrue($client->sendMessage('Bonjour Cédric !'));
    }
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    $client = new Client(['username' => 'anonymous', 'password' => 'secret']);
    $this->assertStringStartsWith('yii\freemobile\Client {', $client);
    $this->assertContains('"username":"anonymous"', $client);
    $this->assertContains('"password":"secret"', $client);
  }
}
