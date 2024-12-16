<?php declare(strict_types=1);

use Gmstudio\Validator\Node;
use Gmstudio\Validator\ValidationResult;
use Gmstudio\Validator\Validator;
use PHPUnit\Framework\TestCase;

final class NodeTest extends TestCase {

  public function testNodeArrayAccess() {
    error_reporting(E_ALL);
    $data = [];

    $node = new Node($data);
    $node->ensureArray();

    $node2 = new Node('a');
    $node2->ensureArray();

    $this->assertFalse($node->isInactive());
    $this->assertTrue($node2->isInactive());

    # check for potential expections on later node access
    $node2->key('nodekey')->test('isArray');
    $result = $node2->validate();

    $this->assertInstanceOf(ValidationResult::class, $result);
    $this->assertFalse($result->passed());
    $this->assertSameSize([1], $result->getErrors(), $result->errorsToString());
  }

  public function testNodeArrayAsteriskAccess() {
    $data = [
      'key1' => [
        'sub1' => [
          'foo' => 'bar',
          'bar' => 'baz'
        ],
        'sub2' => [
          'foo' => 'bar',
          'bar' => 'bay'
        ]
      ],
      'key2' => [
        'sub1' => [
          'foo' => 'bay',
          'bar' => 'baz',
        ],
        'sub2' => [
          'foo' => 'bar',
          'bar' => 'baz'
        ]
      ],
      'key3' => [
        'sub1' => '1'
      ]
    ];

    $validator = new Validator($data);

    $node = $validator->getNode()->ensureArray();

    $node->key('key1.*.foo')->test('isEqual', 'bar');
    
    $this->assertTrue($node->validate()->passed());


    $node2 = $validator->getNode()->ensureArray();

    $node2->key('key2.*.foo')->test('isEqual', 'bar');

    $this->assertFalse($node2->validate()->passed());
  }

  public function testNodeBasicTests() {

    $data = [
      'equal1' => 1,
      'equal2' => '1',
      'equal3' => 2,
      'equal4' => '2',
    ];


    $validator = new Validator($data);

    $node = $validator->getNode()->ensureArray();
    $node2 = $validator->getNode()->ensureArray();

    $node->key('equal1')->test('isEqual', 1);
    $node->key('equal2')->test('isEqual', 1);
    $node2->key('equal3')->test('isStrictEqual', 2);
    $node2->key('equal4')->test('isStrictEqual', 2);

    $this->assertTrue($node->validate()->passed());
    $this->assertFalse($node2->validate()->passed());

    $node->key('equal5');

    $this->assertFalse($node->validate()->passed());
  }

  
}