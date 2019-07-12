<?php declare(strict_types=1);
namespace yii\freemobile;

use function PHPUnit\Expect\{expect, it};
use GuzzleHttp\Psr7\{Uri};
use PHPUnit\Framework\{TestCase};
use yii\base\{InvalidArgumentException, InvalidConfigException};

/** Tests the features of the `yii\freemobile\Client` class. */
class ClientTest extends TestCase {

  /** @test Client->init() */
  function testInit(): void {
    it('should throw an exception if the username or password is empty', function() {
      expect(function() { new Client; })->to->throw(InvalidConfigException::class);
    });
  }

  /** @test Client->sendMessage() */
  function testSendMessage(): void {
    it('should not send invalid messages with valid credentials', function() {
      expect(function() {
        (new Client(['username' => 'anonymous', 'password' => 'secret']))->sendMessage('');
      })->to->throw(InvalidArgumentException::class);
    });

    it('should throw a `ClientException` if a network error occurred', function() {
      expect(function() {
        $config = ['username' => 'anonymous', 'password' => 'secret', 'endPoint' => new Uri('http://localhost/')];
        (new Client($config))->sendMessage('Hello World!');
      })->to->throw();
    });

    it('should send valid messages with valid credentials', function() {
      expect(function() {
        $config = ['username' => getenv('FREEMOBILE_USERNAME'), 'password' => getenv('FREEMOBILE_PASSWORD')];
        (new Client($config))->sendMessage('Bonjour Cédric, à partir du Yii Framework !');
      })->to->not->throw;
    });
  }
}
