<?php

namespace Gmstudio\Validator\Tests;

use Gmstudio\Validator\TestException;

class Has extends Test {

  protected $error_message = "Object is not a boolean";

  public function test(array $data): bool {
    if(count($data) < 2) {
      throw new TestException("To less data to test");
    }

    $pattern = $data[0];
    unset($data[0]);

    foreach($data as $key) {
      if(dot($pattern)->has($key) == false) return false;
    }

    return true;
  }
}