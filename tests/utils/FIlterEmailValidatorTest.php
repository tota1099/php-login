<?php

namespace tests\utils;

use App\presentation\interfaces\EmailValidator;
use App\utils\FilterEmailValidator;
use PHPUnit\Framework\TestCase;

final class FilterEmailValidatorTest extends TestCase
{
  private EmailValidator $sut;

  public function setUp(): void {
    $this->sut = new FilterEmailValidator();
  }

  public function testShouldReturnTrueWithValidEmails() {
    $this->assertTrue($this->sut->isValid('email@email.com'));
    $this->assertTrue($this->sut->isValid('email@email.com'));
    $this->assertTrue($this->sut->isValid('email-email@email.com'));
  }

  public function testShouldReturnFalseWithInvalidEmails() {
    $this->assertFalse($this->sut->isValid('email-email.com'));
    $this->assertFalse($this->sut->isValid('email@email'));
    $this->assertFalse($this->sut->isValid('email@emailcom'));
    $this->assertFalse($this->sut->isValid('email'));
  }
}