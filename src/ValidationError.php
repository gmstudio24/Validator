<?php

namespace Gmstudio\Validator;

/**
 * ValidationError class for managing a validation errors.
 */
class ValidationError {
  
  public $text;
  
  /**
   * Constructs a new ValidationError object with message passed in $message 
   * parameter.
   *
   * @param  string $message
   * @return void
   */
  public function __construct($message = "Validation error")
  {
    $this->text = $message;
  }
  
  /**
   * Casting object to string. Returns $this->text.
   *
   * @return string error message
   */
  public function __toString()
  {
    return $this->text;
  }

}