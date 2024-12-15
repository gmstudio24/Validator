<?php

namespace Gmstudio\Validator\Tests;


class IsInt extends Test {

  protected $error_message = "Object is not an int";

  public function test(array $data): bool {
    foreach($data as $single) {
      print($single);
      if(!is_int($single)) return false;
    }
    return true;
  }

}