<?php

namespace Gmstudio\Validator\Tests;


class IsEmptyString extends Test {

  protected $error_message = "Value is not an empty string";

  public function test(array $data): bool {
    foreach($data as $single) {
      if($single !== '') return false;
    }
    return true;
  }

}