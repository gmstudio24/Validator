<?php

namespace Gmstudio\Validator\Tests;


class IsArray extends Test {

  protected $error_message = "Object is not an array";

  public function test(array $data): bool {
    foreach($data as $string) {
      if(!is_array($string)) return false;
    }
    return true;
  }

}