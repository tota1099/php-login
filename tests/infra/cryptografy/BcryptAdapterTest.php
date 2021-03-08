<?php

namespace tests\infra\cryptografy;

use App\data\interfaces\Encrypter;
use App\infra\cryptografy\BcryptAdapter;
use PHPUnit\Framework\TestCase;

final class BcryptTest extends TestCase
{
  use \phpmock\phpunit\PHPMock;

  public Encrypter $sut;

  public function setUp() : void {
    $this->sut = new BcryptAdapter();
  }

  public function testShouldCallPasswordHashWithCorrectValues() {
    $mock = $this->getFunctionMock('App\infra\cryptografy', "password_hash");
    $mock->expects($this->once())->with("foo", PASSWORD_BCRYPT)->willReturn("bar");

    $this->assertSame('bar', $this->sut->encrypt('foo'));
  }

  public function testShouldThrowsIfPasswordHashThrows() {
    $mock = $this->getFunctionMock('App\infra\cryptografy', "password_hash");

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('any error');
    
    $mock->expects($this->once())->with("foo", PASSWORD_BCRYPT)->willThrowException(new \Exception('any error'));

    $this->assertSame('bar', $this->sut->encrypt('foo'));
  }
}