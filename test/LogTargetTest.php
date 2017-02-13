<?php
/**
 * Implementation of the `yii\freemobile\test\LogTargetTest` class.
 */
namespace yii\freemobile\test;

use PHPUnit\Framework\{TestCase};
use yii\freemobile\{Client, LogTarget};
use yii\log\{Logger};

/**
 * @coversDefaultClass \yii\freemobile\LogTarget` class.
 */
class LogTargetTest extends TestCase {

  /**
   * @test ::formatMessage
   */
  public function testFormatMessage() {
    $message = ['Hello World!', Logger::LEVEL_ERROR, 'tests', time()];
    $this->assertEquals('[error@tests] Hello World!', (new LogTarget())->formatMessage($message));
  }

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    $data = (new LogTarget())->jsonSerialize();
    $this->assertEquals(count(get_object_vars($data)), 8);
    $this->assertEquals(Client::class, $data->client);
    $this->assertTrue($data->enabled);
  }

  /**
   * @test ::setClient
   */
  public function testSetClient() {
    \Yii::$app->set('freemobileTest', \Yii::createObject(Client::class));

    $logTarget = new LogTarget(['client' => 'freemobileTest']);
    $this->assertSame(\Yii::$app->get('freemobileTest'), $logTarget->getClient());
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    $target = (string) new LogTarget(['client' => \Yii::createObject(Client::class)]);
    $this->assertStringStartsWith('yii\freemobile\LogTarget {', $target);
    $this->assertContains('"client":"yii\\freemobile\\Client"', $target);
    $this->assertContains('"enabled":true', $target);
  }
}
