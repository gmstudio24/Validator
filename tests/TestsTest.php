<?php declare(strict_types=1);

use Gmstudio\Validator\Tests\Has;
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
use Gmstudio\Validator\Tests\IsEqual;
use Gmstudio\Validator\Tests\IsOneOf;
use Gmstudio\Validator\Tests\IsString;
use Gmstudio\Validator\Tests\IsTrue;
use PHPUnit\Framework\TestCase;

final class TestsTest extends TestCase {

  public static $types = [
    'string' => 'foo',
    'int' => 1,
    'float' => 1.2,
    'null' => null,
    'bool' => true,
    'array' => []
  ];

  public static $test_map = [
    'isArray' => IsArray::class, //done
    'isString' => IsString::class, //done
    'isNumeric' => IsNumeric::class, //done
    'isInt' => IsInt::class, //done
    'isFloat' => IsFloat::class, //done
    'isBool' => IsBool::class, //done
    'isEmptyArray' => IsEmptyArray::class,  //done
    'isEmptyString' => IsEmptyString::class, //done
    'isEqual' => IsEqual::class, //done
    'isFalse' => IsFalse::class, //done
    'isTrue' => IsTrue::class, //done
    'isStrictEqual' => IsStrictEqual::class, //done
    'isNull' => IsNull::class, //done
  ];

  public static function makeTest($test_name, $data, ...$params) {
    $test = new self::$test_map[$test_name]();

    return $test->setData($data, ...$params)->validate();
  }

  public static function makeDoubleTest($test_name, $true_values, $false_values) {
    foreach($true_values as $val) {
      if(self::makeTest($test_name, $val) == false) return false;
    }

    foreach($false_values as $val) {
      if(self::makeTest($test_name, $val) == true) return false;
    }

    return true;
  }


  public function testTypeTests() {
    foreach(self::$types as $type => $data) {
      $type_u = ucfirst($type);
      $this->assertTrue(self::makeTest("is{$type_u}", $data));
      
      foreach(self::$types as $subtype => $subdata) {
        if($subtype !== $type) {
          $subtype_u = ucfirst($subtype);
          $this->assertFalse(self::makeTest("is{$subtype_u}", $data));
        }
      }
    }
  }

  public function testIsNumeric() {
    $this->assertTrue(
      self::makeDoubleTest(
        "isNumeric", 
        ['2', 67, 2.3, 0, '0', '0.6'],
        ['2a', '000000i0', false, true, []]
      )
    );
  }
  
  public function testIsEmptyArray() {
    $this->assertTrue(
      self::makeDoubleTest(
        "isEmptyArray", 
        [[]],
        [[1], [null], [0], [true], [false], '', null]
      )
    );
  }
  
  public function testIsEmptyString() {
    $this->assertTrue(
      self::makeDoubleTest(
        "isEmptyString", 
        ['', ""],
        [[], " ", ' ', false, true, null]
      )
    );
  }

  public function testIsEqual() {
    $this->assertTrue(
      self::makeTest(
        "isEqual",
        1, 
        1,
        '1',
        true
    ));
      $this->assertFalse(
        self::makeTest(
          "isEqual",
          1,
          2,
          3
        )
      );
  }

  public function testIsStrictEqual() {
    $this->assertTrue(
      self::makeTest(
        "isStrictEqual",
        'asdfg1',
        'asdfg1'
    ));
      $this->assertFalse(
        self::makeTest(
          "isStrictEqual",
         1,
         1.0
        )
      );
  }

  public function testIsTrue() {
    $this->assertTrue(
      self::makeDoubleTest(
        "isTrue", 
        [true],
        [false, 0, 1, null]
      )
    );
  }

  public function testIsFalse() {
    $this->assertTrue(
      self::makeDoubleTest(
        "isFalse", 
        [false],
        [0, true, 1, null]
      )
    );
  }

  public function testIsOneOf() {
    $test = new IsOneOf();

    $test->setData('bar', 'bar', 'foo', 2, null);
    $this->assertTrue($test->validate());

    $test = new IsOneOf();
    $test->setData(true, 1, 'bar', 'foo', 3.3, false);
    $this->assertFalse($test->validate());

  }

  public function testHas() {

    $data = [
      'foo' => [
        'bar' => [
          'baz' => '2',
          'bay' => 3,
        ],
        'fee' => [
          'baz' => false,
        ]
      ]
    ];

    $test  = new Has();
    $test2 = new Has();
    $test3 = new Has();
    $test4 = new Has();

    $test->setData($data, 'foo.*.baz', 'foo');
    $test2->setData($data, 'foo.*.bay', 'foo');
    $test3->setData($data, 'foo.bar');
    $test4->setData($data, 'foo.ee');

    $this->assertTrue($test->validate());
    $this->assertFalse($test2->validate());
    $this->assertTrue($test3->validate());
    $this->assertFalse($test4->validate());

  }
}