<?php

namespace Gmstudio\Validator\Tests;

class IsString extends Test {

  protected $error_message = "Object is not a string";

  public function test(array $data): bool {
    foreach($data as $string) {
      if(!is_string($string)) return false;
    }
    return true;
  }

}