<?php

namespace Gmstudio\Validator\Tests;

class IsNumeric extends Test {

  protected $error_message = "Object is not a string";

  public function test(array $data): bool {
    foreach($data as $num) {
      if(!is_numeric($num)) return false;
    }
    return true;
  }

}