<?php
declare(strict_types=1);
namespace yii\freemobile;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};
use yii\log\{Logger};

/**
 * Tests the features of the `yii\freemobile\LogTarget` class.
 */
class LogTargetTest extends TestCase {

  /**
   * Performs a common set of tasks just before the first test of the class is run.
   */
  public static function setUpBeforeClass() {
    \Yii::$app->set('freemobile', [
      'class' => Client::class,
      'username' => 'anonymous',
      'password' => 'secret'
    ]);
  }

  /**
   * @test LogTarget::formatMessage
   */
  public function testFormatMessage() {
    it('should return a formatted message including the log level and category', function() {
      $message = ['Hello World!', Logger::LEVEL_ERROR, 'tests', time()];
      expect((new LogTarget)->formatMessage($message))->to->equal('[error@tests] Hello World!');
    });
  }

  /**
   * @test LogTarget::setClient
   */
  public function testSetClient() {
    it('should handle the application component', function() {
      \Yii::$app->set('freemobileTest', [
        'class' => Client::class,
        'username' => 'anonymous',
        'password' => 'secret'
      ]);

      expect((new LogTarget(['client' => 'freemobileTest']))->client)->to->be->identicalTo(\Yii::$app->get('freemobileTest'));
    });
  }
}
