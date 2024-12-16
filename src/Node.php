<?php

namespace Gmstudio\Validator;

use Gmstudio\Validator\Tests\IsArray;
use Gmstudio\Validator\Tests\IsBool;
use Gmstudio\Validator\Tests\IsEmptyArray;
use Gmstudio\Validator\Tests\IsEmptyString;
use Gmstudio\Validator\Tests\IsFalse;
use Gmstudio\Validator\Tests\IsFloat;
use Gmstudio\Validator\Tests\IsInt;
use Gmstudio\Validator\Tests\IsNull;
use Gmstudio\Validator\Tests\IsNumeric;
use Gmstudio\Validator\Tests\IsStrictEqual;
use Gmstudio\Validator\Tests\IsString;
use Gmstudio\Validator\Tests\IsTrue;
use Gmstudio\Validator\Tests\Test;
use Gmstudio\Validator\Tests\IsEqual;
use Gmstudio\Validator\Tests\IsOneOf;
use Gmstudio\Validator\Tests\Has;

/**
 * Node is a object containing data to test on.
 */
class Node{

  private $active = true;
  private $data;
  private $data_type;
  private $errors = [];
  private $tests = [];

  private $pointers = [];

  protected $test_map = [
    'isArray' => IsArray::class,
    'isString' => IsString::class,
    'isNumeric' => IsNumeric::class,
    'isInt' => IsInt::class,
    'isFloat' => IsFloat::class,
    'isBool' => IsBool::class,
    'isEmptyArray' => IsEmptyArray::class,
    'isEmptyString' => IsEmptyString::class,
    'isEqual' => IsEqual::class,
    'isFalse' => IsFalse::class,
    'isTrue' => IsTrue::class,
    'isStrictEqual' => IsStrictEqual::class,
    'isNull' => IsNull::class,
    'isOneOf' => IsOneOf::class,
    'has' => Has::class,
  ];
  
  /**
   * Main node constructor
   *
   * @param  mixed $data data to test
   * @return void
   */
  public function __construct(mixed $data)
  {
    $this->data = $data;
  }
  
  /**
   * Returns node's data
   *
   * @return mixed data
   */
  private function getData(): mixed {
    return $this->data;
  }
  
  /**
   * Clears node's pointers.
   *
   * @return self
   */
  public function clearPointers() {
    $this->pointers = [];
    return $this;
  }
  
  /**
   * Adding new test to node
   *
   * @param  mixed $test
   * @return void
   */
  public function addTest(Test $test) {
    $this->tests[] = $test;
  }
  
  /**
   * Change node state to inactive. Some actions may have been limited to work
   * only on active node to avoid errors (e.g. array-type access to data that 
   * are not an array)
   *
   * @param  ValidationError $error Validation error that is set to be returned
   * @return void
   */
  public function setInactive(ValidationError $error = null) {
    $this->active = false;
    if($error != null) $this->errors[] = $error->text;
  }
  
  /**
   * Checks if node is an inactive state.
   *
   * @return bool True on inactive. False on active
   */
  public function isInactive() {
    if(!$this->active) return true;
    return false;
  }
  
  /**
   * Returns node's error list
   *
   * @return array errors
   */
  public function getErrors() {
    return $this->errors;
  }
  
  /**
   * Ensures node's data is type of array. If not node state is changed to 
   * inactive to avoid potential array-type access to data that is not array. 
   *
   * @return self
   */
  public function ensureArray() {
    $rule = new IsArray();
    if(!$rule->validate($this->getData())) {
      $this->setInactive($rule->getError());
    }
    return $this;
  }
  
  /**
   * Prepare a single test. Tests are chosen by $this->test_map array. Saves 
   * tests by callind $this->addTest().
   * 
   * If $test_name is not found sets note state as inactive (because of this 
   * test could not be resolved)
   *
   * @param  mixed $test_name Test name (e.g. 'isInt', 'isNotNull')
   * @param  mixed $params Test additional parameters
   * @return void
   */
  public function test(string $test_name, mixed ...$params) {
    if(!key_exists($test_name, $this->test_map)) 
      $this->setInactive(new ValidationError("Test {$test_name} does not exist."));
    if($this->isInactive())
      return;
    if($this->pointers === []) {
      $this->performTest($test_name, $this->getData(), $params);
    } else {
      foreach($this->pointers as $pointer) {
        $this->performTest($test_name, $pointer, $params);
      }
    }
  }
  
  /**
   * perform a test by test_name
   *
   * @param  mixed $test_name
   * @param  mixed $data
   * @param  mixed $params
   * @return void
   */
  private function performTest($test_name, $data, ?array $params) {
    $test_class = $this->test_map[$test_name];
        $test = new $test_class();
        $test->setData($data, ...$params);
        $this->addTest($test);
  }
  
  /**
   * Validates all tests that were recently added to an node. 
   * 
   * If any test is not passed node state is changed to inactive and errors are 
   * added. Returns a new ValidationResult object with result based on node 
   * state and potential errors list.
   *
   * @return ValidationResult
   */
  public function validate(): ValidationResult {

    foreach($this->tests as $test) {
      if(!$test->validate()) {
        $this->setInactive($test->getError());
      }
    }

    return new ValidationResult(!$this->isInactive(), $this->getErrors());
  }
  
  /**
   * Select all data
   *
   * @return self
   */
  public function all() {
    $this->clearPointers();

    if($this->isInactive()) return $this;

    $this->pointers[] = $this->getData();

    return $this;
  }

  /**
   * Adds new array key to a node's pointers (tested datapoints) only if node 
   * state is not alreaty inactive. Clears pointers on every use.
   *
   * @param  string|array $key single key of array of keys
   * @return self
   */
  public function key(string|array $key) {
    $this->clearPointers();
    if($this->isInactive()) return $this;

    $data = $this->getData();

    if(is_array($key)) {
      # for each key make a new data endpoint and add to pointers;
      foreach($key as $single_key) {
        if(dot($data)->has($single_key))
          $this->pointers[] = 
            str_contains($single_key, '*') 
            ? dot($data)->get($single_key) 
            : [dot($data)->get($single_key)];
        else
          $this->setInactive(
            new ValidationError("Key `{$single_key}` does not exist"));
      }
    } else {
      if(dot($data)->has($key)) {
        $value = str_contains($key, '*') 
        ? dot($data)->get($key) 
        : [dot($data)->get($key)];
        $this->pointers = $value;

      } else { $this->setInactive(
          new ValidationError("Key `{$key}` does not exist"));
      }
    }
    return $this;
  }

}