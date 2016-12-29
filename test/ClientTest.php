<?php
/**
 * Implementation of the `yii\freemobile\test\ClientTest` class.
 */
namespace yii\freemobile\test;
use yii\freemobile\{Client, RequestEvent, ResponseEvent};

/**
 * Tests the features of the `yii\freemobile\Client` class.
 */
class ClientTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests the `Client` constructor.
   */
  public function testConstructor() {
    $client = new Client(['username' => 'anonymous', 'password' => 'secret']);
    $this->assertEquals('secret', $client->getPassword());
    $this->assertEquals('anonymous', $client->getUsername());

    $this->assertSame($client, $client->setPassword(''));
    $this->assertEmpty($client->getPassword());
  }

  /**
   * Tests the `Client::jsonSerialize()` method.
   */
  public function testJsonSerializeoJSON() {
    $data = (new Client(['username' => 'anonymous', 'password' => 'secret']))->jsonSerialize();

    $this->assertObjectHasAttribute('password', $data);
    $this->assertEquals('secret', $data->password);

    $this->assertObjectHasAttribute('username', $data);
    $this->assertEquals('anonymous', $data->username);
  }

  /**
   * Tests the `Client` "request" event.
   */
  public function testOnRequest() {
    $client = new Client(['username' => 'anonymous', 'password' => 'secret']);
    $client->on(Client::EVENT_REQUEST, function($request) { $this->assertInstanceOf(RequestEvent::class, $request); });
    $client->sendMessage('FooBar');
  }

  /**
   * Tests the `Client` "response" event.
   */
  public function testOnResponse() {
    $client = new Client(['username' => 'anonymous', 'password' => 'secret']);
    $client->on(Client::EVENT_RESPONSE, function($response) { $this->assertInstanceOf(ResponseEvent::class, $response); });
    $client->sendMessage('FooBar');
  }

  /**
   * Tests the `Client::sendMessage()` method.
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
}
