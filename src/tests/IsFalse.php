<?php

namespace Gmstudio\Validator\Tests;


class IsFalse extends Test {

  protected $error_message = "Value is not false";

  public function test(array $data): bool {
    foreach($data as $single) {
      if($single !== false) return false;
    }
    return true;
  }

}