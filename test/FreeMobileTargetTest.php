<?php
/**
 * @file
 * Implementation of the `yii\test\log\FreeMobileTargetTest` class.
 */
namespace yii\tests\log;

// Dependencies.
use yii\log\FreeMobileTarget;
use yii\log\Logger;

/**
 * Tests the features of the `yii\log\FreeMobileTarget` class.
 */
class FreeMobileTargetTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var yii::log::FreeMobileTarget $model
   * The data context of the tests.
   */
  private $model;

  /**
   * Tests the `formatMessage` method.
   */
  public function testFormatMessage() {
    $message=[ 'Hello World!', Logger::LEVEL_ERROR, 'tests', time() ];
    $this->assertEquals('[error@tests] Hello World!', $this->model->formatMessage($message));
  }

  /**
   * Performs a common set of tasks just before each test method is called.
   */
  protected function setUp() {
    $this->model=new FreeMobileTarget();
  }
}
