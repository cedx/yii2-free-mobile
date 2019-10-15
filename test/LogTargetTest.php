<?php declare(strict_types=1);
namespace yii\freemobile;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};
use yii\base\{InvalidConfigException};
use yii\log\{Logger};

/** @testdox yii\freemobile\LogTarget */
class LogTargetTest extends TestCase {

  /** @testdox ->formatMessage() */
  function testFormatMessage(): void {
    it('should return a formatted message including the log level and category', function() {
      $message = ['Hello World!', Logger::LEVEL_ERROR, 'tests', time()];
      expect((new LogTarget)->formatMessage($message))->to->equal('[tests] Hello World!');
    });
  }

  /** @testdox ->init() */
  function testInit(): void {
    it('should throw an exception if the client is empty', function() {
      expect(function() {
        \Yii::$app->set('freemobile', null);
        new LogTarget;
      })->to->throw(InvalidConfigException::class);
    });

    it('should not throw an exception if the client is not empty', function() {
      expect(function() {
        \Yii::$app->set('freemobile', new Client(['username' => 'anonymous', 'password' => 'secret']));
        new LogTarget;
      })->to->not->throw;
    });

    it('should allow a customized client component ID', function() {
      \Yii::$app->set('freemobileTest', [
        'class' => Client::class,
        'username' => 'anonymous',
        'password' => 'secret'
      ]);

      expect((new LogTarget(['client' => 'freemobileTest']))->client)
        ->to->be->instanceOf(Client::class)
        ->and->be->identicalTo(\Yii::$app->get('freemobileTest'));
    });
  }

  /** @before This method is called before each test. */
  protected function setUp(): void {
    \Yii::$app->set('freemobile', new Client(['username' => 'anonymous', 'password' => 'secret']));
  }
}
