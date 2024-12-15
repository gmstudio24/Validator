<?php

namespace Gmstudio\Validator;

/**
 * Validator class
 */
class Validator {

  #data
  private $data;
    
  /**
   * Constructor of new Validator object with given $data to validate
   *
   * @param  mixed $data
   * @return void
   */
  public function __construct($data)
  {
    $this->data = $data;
  }
  
  /**
   * Returns an object data.
   *
   * @return mixed data
   */
  public function getData(): mixed {
    return $this->data;
  }
  
  /**
   * Returns a new Node object with data from Validator object.
   *
   * @return Node new node
   */
  public function getNode() {
    return new Node($this->getData());
  }

}

?>


