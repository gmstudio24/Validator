<?php

namespace Gmstudio\Validator\Tests;


class IsFloat extends Test {

  protected $error_message = "Object is not a float";

  public function test(array $data): bool {
    foreach($data as $single) {
      if(!is_float($single)) return false;
    }
    return true;
  }

}