<?php
/**
 * Implementation of the `yii\freemobile\test\LogTargetTest` class.
 */
namespace yii\freemobile\test;

use yii\freemobile\{Client, LogTarget};
use yii\log\{Logger};

/**
 * Tests the features of the `yii\freemobile\LogTarget` class.
 */
class LogTargetTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests the `LogTarget::formatMessage` method.
   */
  public function testFormatMessage() {
    $message = ['Hello World!', Logger::LEVEL_ERROR, 'tests', time()];
    $this->assertEquals('[error@tests] Hello World!', (new LogTarget())->formatMessage($message));
  }

  /**
   * Tests the `LogTarget::jsonSerialize` method.
   */
  public function testJsonSerialize() {
    $client = \Yii::createObject([
      'class' => Client::class,
      'password' => 'secret',
      'username' => 'anonymous'
    ]);

    $data = (new LogTarget(['client' => $client]))->jsonSerialize();
    $this->assertObjectHasAttribute('enabled', $data);
    $this->assertTrue($data->enabled);
  }
}
