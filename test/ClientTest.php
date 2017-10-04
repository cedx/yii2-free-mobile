<?php
declare(strict_types=1);
namespace yii\freemobile;

use function PHPUnit\Expect\{expect, fail, it};
use PHPUnit\Framework\{TestCase};
use Psr\Http\Message\{UriInterface};
use yii\base\{InvalidConfigException};

/**
 * Tests the features of the `yii\freemobile\Client` class.
 */
class ClientTest extends TestCase {

  /**
   * @test Client::init
   */
  public function testInit() {
    it('should throw an exception if the username or password is empty', function() {
      expect(function() { new Client; })->to->throw(InvalidConfigException::class);
    });

    it('should not throw an exception if the username and password are not empty', function() {
      expect(function() { new Client(['username' => 'anonymous', 'password' => 'secret']); })->to->not->throw;
    });
  }

  /**
   * @test Client::sendMessage
   */
  public function testSendMessage() {
    it('should not send valid messages with invalid credentials', function() {
      try {
        (new Client(['username' => '', 'password' => '']))->sendMessage('Hello World!');
        fail('A message with empty credentials should not be sent.');
      }

      catch (\Throwable $e) {
        expect(true)->to->be->true;
      }
    });

    it('should not send invalid messages with valid credentials', function() {
      try {
        (new Client(['username' => 'anonymous', 'password' => 'secret']))->sendMessage('');
        fail('An empty message with credentials should not be sent.');
      }

      catch (\Throwable $e) {
        expect(true)->to->be->true;
      }
    });

    if (is_string($username = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
      it('should send valid messages with valid credentials', function() use ($password, $username) {
        (new Client(['username' => $username, 'password' => $password]))->sendMessage('Bonjour Cédric !');
        expect(true)->to->be->true;
      });
    }
  }

  /**
   * @test Client::setEndPoint
   */
  public function testSetEndPoint() {
    it('should return an instance of `UriInterface` for strings', function() {
      $client = new Client(['username' => 'anonymous', 'password' => 'secret']);
      $client->endPoint = 'https://github.com/cedx/yii2-free-mobile';
      expect($client->endPoint)->to->be->instanceOf(UriInterface::class);
      expect((string) $client->endPoint)->to->equal('https://github.com/cedx/yii2-free-mobile');
    });

    it('should return a `null` reference for unsupported values', function() {
      $client = new Client(['username' => 'anonymous', 'password' => 'secret']);
      $client->endPoint = 123;
      expect($client->endPoint)->to->be->null;
    });
  }
}
