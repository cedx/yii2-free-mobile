<?php
/**
 * Implementation of the `yii\test\log\FreeMobileTargetTest` class.
 */
namespace yii\test\log;
use yii\log\{FreeMobileTarget, Logger};

/**
 * Tests the features of the `yii\log\FreeMobileTarget` class.
 */
class FreeMobileTargetTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests the `FreeMobileTarget::formatMessage()` method.
   */
  public function testFormatMessage() {
    $message = ['Hello World!', Logger::LEVEL_ERROR, 'tests', time()];
    $target = new FreeMobileTarget();
    $this->assertEquals('[error@tests] Hello World!', $target->formatMessage($message));
  }
}
