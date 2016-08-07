<?php
/**
 * Implementation of the `yii\test\log\FreeMobileTargetTest` class.
 */
namespace yii\tests\log;

use yii\log\{FreeMobileTarget, Logger};
use yii\web\HttpException;

/**
 * Tests the features of the `yii\log\FreeMobileTarget` class.
 */
class FreeMobileTargetTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var FreeMobileTarget The data context of the tests.
   */
  private $model;

  /**
   * Tests the `export` method.
   */
  public function testExport() {
    $this->expectException(HttpException::class);
    $this->model->endPoint = 'https://sms.belin.io/sendmsg';
    $this->model->export(true);
  }

  /**
   * Tests the `formatMessage` method.
   */
  public function testFormatMessage() {
    $message = ['Hello World!', Logger::LEVEL_ERROR, 'tests', time()];
    $this->assertEquals('[error@tests] Hello World!', $this->model->formatMessage($message));
  }

  /**
   * Performs a common set of tasks just before each test method is called.
   */
  protected function setUp() {
    $this->model = new FreeMobileTarget();
    $this->model->userName = getenv('FREEMOBILE_USERNAME');
    $this->model->password = getenv('FREEMOBILE_PASSWORD');
  }
}
