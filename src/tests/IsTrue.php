<?php

namespace Gmstudio\Validator\Tests;


class IsTrue extends Test {

  protected $error_message = "Value is not true";

  public function test(array $data): bool {
    foreach($data as $single) {
      if($single !== true) return false;
    }
    return true;
  }

}