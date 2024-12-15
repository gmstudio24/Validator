<?php

namespace Gmstudio\Validator;

/**
 * Simple validation result object.
 */
class ValidationResult {
  
  private $passed;

  private $errors = [];
  
  /**
   * Returns a ValidationResult object with pass state defined in $passed and 
   * errors defined in $errors
   *
   * @param  bool  $passed Validation state
   * @param  array $errors Test's errors
   * @return void
   */
  public function __construct(bool $passed, array $errors)
  {
    $this->passed = $passed;
    $this->errors = $errors;
  }
  
  /**
   * Returns true if validation passed.
   *
   * @return bool passed
   */
  public function passed() {
    return $this->passed;
  }
  
  /**
   * Returns an errors list
   *
   * @return array errors
   */
  public function getErrors() {
    return $this->errors;
  }

  public function errorsToString()
  {
    $str = '';
    foreach($this->errors as $error) {
      $error_str = strval($error);
      $str.= "{$error_str}; ";
    }

    return $str;
  }

}