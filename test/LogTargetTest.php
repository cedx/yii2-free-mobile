<?php
declare(strict_types=1);
namespace yii\freemobile;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};
use yii\base\{InvalidConfigException};
use yii\log\{Logger};

/**
 * Tests the features of the `yii\freemobile\LogTarget` class.
 */
class LogTargetTest extends TestCase {

  /**
   * @test LogTarget::formatMessage
   */
  public function testFormatMessage(): void {
    it('should return a formatted message including the log level and category', function() {
      $message = ['Hello World!', Logger::LEVEL_ERROR, 'tests', time()];
      expect((new LogTarget)->formatMessage($message))->to->equal('[tests] Hello World!');
    });
  }

  /**
   * @test LogTarget::init
   */
  public function testInit(): void {
    it('should throw an exception if the client is empty', function() {
      \Yii::$app->set('freemobile', null);
      expect(function() { new LogTarget; })->to->throw(InvalidConfigException::class);
    });

    it('should not throw an exception if the client is not empty', function() {
      \Yii::$app->set('freemobile', new Client(['username' => 'anonymous', 'password' => 'secret']));
      expect(function() { new LogTarget; })->to->not->throw;
    });

    it('should allow a customized client component ID', function() {
      \Yii::$app->set('freemobileTest', [
        'class' => Client::class,
        'username' => 'anonymous',
        'password' => 'secret'
      ]);

      expect((new LogTarget(['client' => 'freemobileTest']))->client)
        ->to->be->an->instanceOf(Client::class)
        ->and->be->identicalTo(\Yii::$app->get('freemobileTest'));
    });
  }

  /**
   * Performs a common set of tasks just before each test method is called.
   */
  protected function setUp(): void {
    \Yii::$app->set('freemobile', new Client(['username' => 'anonymous', 'password' => 'secret']));
  }
}
