<?php

namespace Gmstudio\Validator\Tests;

use Gmstudio\Validator\TestException;

class IsOneOf extends Test {

  protected $error_message = "Value is not present in given array";

  public function test(array $data): bool {
    if(count($data) < 2) {
      throw new TestException("To less data to test");
    }

    $pattern = $data[0];
    unset($data[0]);

    if(!in_array($pattern, $data, true)) return false;
    return true;
  }

}