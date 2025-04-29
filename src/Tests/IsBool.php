<?php

namespace Gmstudio\Validator\Tests;


class IsBool extends Test {

  protected $error_message = "Object is not a boolean";

  public function test(array $data): bool {
    foreach($data as $single) {
      if(!is_bool($single)) return false;
    }
    return true;
  }

}