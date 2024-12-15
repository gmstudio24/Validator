<?php

namespace Gmstudio\Validator\Tests;

use Gmstudio\Validator\TestException;

class IsFalse extends Test {

  protected $error_message = "Value is not equal";

  public function test(array $data): bool {
    if($data === []) {
      throw new TestException("Data cannot be an empty array");
    }
    $v = $data[0];

    foreach($data as $val) {
      if($val != $v) return false;
    }

    return true;
  }

}