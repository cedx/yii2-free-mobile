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
   * @test LogTarget::jsonSerialize
   */
  public function testJsonSerialize() {
    it('should return a map with the same public values', function() {
      $data = (new LogTarget)->jsonSerialize();
      expect(get_object_vars($data))->to->have->lengthOf(8);
      expect($data->client)->to->equal(Client::class);
      expect($data->enabled)->to->be->true;
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

  /**
   * @test LogTarget::__toString
   */
  public function testToString() {
    $target = (string) new LogTarget;

    it('should start with the class name', function() use ($target) {
      expect($target)->to->startWith('yii\freemobile\LogTarget {');
    });

    it('should contain the instance properties', function() use ($target) {
      expect($target)->to->contain(sprintf('"client":"%s"', str_replace('\\', '\\\\', Client::class)))
        ->and->contain('"enabled":true');
    });
  }
}
