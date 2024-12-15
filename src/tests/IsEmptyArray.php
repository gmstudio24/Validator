<?php

namespace Gmstudio\Validator\Tests;


class IsEmptyArray extends Test {

  protected $error_message = "Value is not an empty array";

  public function test(array $data): bool {
    foreach($data as $single) {
      if($single !== []) return false;
    }
    return true;
  }

}