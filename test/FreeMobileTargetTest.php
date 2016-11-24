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
   * Tests the `FreeMobileTarget::formatMessage` method.
   */
  public function testFormatMessage() {
    $message = ['Hello World!', Logger::LEVEL_ERROR, 'tests', time()];
    $this->assertEquals('[error@tests] Hello World!', (new FreeMobileTarget())->formatMessage($message));
  }

  /**
   * Tests the `FreeMobileTarget::jsonSerialize` method.
   */
  public function testJsonSerialize() {
    $data = (new FreeMobileTarget([
      'password' => 'secret',
      'username' => 'anonymous'
    ]))->jsonSerialize();

    $this->assertObjectHasAttribute('enabled', $data);
    $this->assertTrue($data->enabled);

    $this->assertObjectHasAttribute('password', $data);
    $this->assertEquals('secret', $data->password);

    $this->assertObjectHasAttribute('username', $data);
    $this->assertEquals('anonymous', $data->username);
  }
}
