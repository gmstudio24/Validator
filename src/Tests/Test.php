<?php

namespace Gmstudio\Validator\Tests;

use Gmstudio\Validator\ValidationError;

/**
 * Test extendable class
 */
abstract class Test {

  #data
  private $data = [];

  #validation error message
  protected $error_message = "Validation error.";
  
  /**
   * Sets test data
   *
   * @param  mixed $data
   * @return self
   */
  public function setData(mixed ...$data) {
    $this->data = $data;
    return $this;
  }
  
  /**
   * Call's test function with data array.
   *
   * @param  mixed $_data
   * @return bool
   */
  public function validate(mixed ...$_data): bool {
    $data = $_data != [] ? $_data : $this->data;
    if($data == []) return false;
    return $this->test($data);
  }
  
  /**
   * Abstract function for making a tests in children classes.
   *
   * @param  array $data array of data parameters
   * @return bool passed
   */
  abstract function test(array $data);
  
  /**
   * Returns a new ValidationError object with message
   *
   * @return ValidationError
   */
  public function getError(): ValidationError {
    return new ValidationError($this->error_message);
  }

}