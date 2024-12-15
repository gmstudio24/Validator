<?php

namespace Gmstudio\Validator\Tests;


class IsNull extends Test {

  protected $error_message = "Value is not false";

  public function test(array $data): bool {
    foreach($data as $single) {
      if($single !== null) return false;
    }
    return true;
  }

}