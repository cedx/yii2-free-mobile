<?php
declare(strict_types=1);
namespace yii\freemobile;

use function PHPUnit\Expect\{expect, fail, it};
use PHPUnit\Framework\{TestCase};
use Psr\Http\Message\{UriInterface};
use yii\base\{InvalidArgumentException, InvalidConfigException};

/**
 * Tests the features of the `yii\freemobile\Client` class.
 */
class ClientTest extends TestCase {

  /**
   * @test Client::init
   */
  public function testInit(): void {
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
  public function testSendMessage(): void {
    it('should not send invalid messages with valid credentials', function() {
      try {
        (new Client(['username' => 'anonymous', 'password' => 'secret']))->sendMessage('');
        fail('An empty message with credentials should not be sent');
      }

      catch (\Throwable $e) {
        expect($e)->to->be->an->instanceOf(InvalidArgumentException::class);
      }
    });

    it('should throw a `ClientException` if a network error occurred', function() {
      try {
        (new Client(['username' => 'anonymous', 'password' => 'secret', 'endPoint' => 'http://localhost']))->sendMessage('Hello World!');
        fail('A message with an invalid endpoint should not be sent');
      }

      catch (\Throwable $e) {
        expect($e)->to->be->an->instanceOf(ClientException::class);
      }
    });

    if (is_string($username = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
      it('should send valid messages with valid credentials', function() use ($password, $username) {
        try {
          (new Client(['username' => $username, 'password' => $password]))->sendMessage('Bonjour Cédric !');
          expect(true)->to->be->true;
        }

        catch (\Throwable $e) {
          expect($e)->to->be->an->instanceOf(ClientException::class);
        }
      });
    }
  }

  /**
   * @test Client::setEndPoint
   */
  public function testSetEndPoint(): void {
    $client = new Client(['username' => 'anonymous', 'password' => 'secret']);

    it('should not be empty by default', function() use ($client) {
      expect($client->endPoint)->to->be->an->instanceOf(UriInterface::class);
      expect((string) $client->endPoint)->to->equal('https://smsapi.free-mobile.fr');
    });

    it('should be an instance of the `Uri` class', function() use ($client) {
      $client->endPoint = 'http://localhost';
      expect($client->endPoint)->to->be->an->instanceOf(UriInterface::class);
      expect((string) $client->endPoint)->to->equal('http://localhost');
    });
  }
}
