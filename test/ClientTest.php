<?php
declare(strict_types=1);
namespace yii\freemobile;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};
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
   * @test Client::jsonSerialize
   */
  public function testJsonSerialize() {
    it('should return a map with the same public values', function() {
      $data = (new Client(['username' => 'anonymous', 'password' => 'secret']))->jsonSerialize();
      expect(get_object_vars($data))->to->have->lengthOf(3);
      expect($data)->to->have->property('password')->that->equal('secret');
      expect($data)->to->have->property('username')->that->equal('anonymous');
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
   * @test Client::__toString
   */
  public function testToString() {
    $client = (string) new Client(['username' => 'anonymous', 'password' => 'secret']);

    it('should start with the class name', function() use ($client) {
      expect($client)->to->startWith('yii\freemobile\Client {');
    });

    it('should contain the instance properties', function() use ($client) {
      expect($client)->to->contain('"username":"anonymous"')
        ->and->contain('"password":"secret"');
    });
  }
}
