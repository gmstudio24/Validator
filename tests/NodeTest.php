<?php declare(strict_types=1);

use Gmstudio\Validator\Node;
use Gmstudio\Validator\ValidationResult;
use Gmstudio\Validator\Validator;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertTrue;

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

  public function testNodeBasicTests() {
    error_reporting(E_ALL);

    $types = [
      'string' => 'test',
      'int' => 1,
      'bool' => true,
      'true' => true,
      'false' => false,
      'null' => null,
      'array' => [1],
    ];
    $custom = [
      'emptyString' => '',
      'emptyArray' => [],
      'numeric' => '123',
    ];

    $tests = $types + $custom;

    foreach($tests as $key => $type) {
      $node = (new Validator($type))->getNode();
      $key = ucfirst($key);
      $node->test("is{$key}");
      $v = $node->validate();
      $this->assertTrue($v->passed(), $v->errorsToString());
    }
  }
}