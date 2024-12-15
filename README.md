### Simple PHP validator library ###

Usage:

```php

  use Gmstudio\Validator\Validator;

  //dummy data
  $data = [
    'bar' => [
      'baz' => 'foo',
      'foo' => 33,
    ],
    'foobar' => 2
  ]

  //make a new validator instance
  $validator = Validator($data);

  //returns an array type node
  $node = $validator->getNode()->ensureArray();

  //add rules
  $node->key('bar.foo')->test('isInt');
  $node->key('bar.*')->test('isNotNull');
  $node->key('foobar')->test('isEqual', 3);

  //validate
  $result = $node->validate();

  //display errors
  if(!$result->passed()) {
    foreach($error as $result->getErrors()) {
      print($error->text)
    }
  }

```