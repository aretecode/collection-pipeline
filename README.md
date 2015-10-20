# Arete\CollectionPipeline
[![Build Status](https://secure.travis-ci.org/aretecode/collection-pipeline.svg)](https://travis-ci.org/aretecode/collection-pipeline)
[![HHVM Status](http://hhvm.h4cc.de/badge/arete/collection-pipeline.svg)](http://hhvm.h4cc.de/package/arete/collection-pipeline)
[![Author](http://img.shields.io/badge/author-@aretecode-blue.svg)](https://twitter.com/aretecode)
[![Latest Unstable Version](https://poser.pugx.org/arete/collection-pipeline/v/unstable)](https://packagist.org/packages/arete/collection-pipeline)
[![License](https://poser.pugx.org/arete/collection-pipeline/license)](http://packagist.org/packages/arete/collection-pipeline)
[![Codacy Badge](https://api.codacy.com/project/badge/88c8b9f55cf94e2ab16765a7c95be7aa)](https://www.codacy.com/app/aretecode/collection-pipeline)

Filter a collection of objects without making a bunch of loops & ifs.

After reading [Martin Fowler on the Collection Pipeline](http://martinfowler.com/articles/collection-pipeline/) I wanted to use something similar in PHP, thus, this was born. [League\Pipeline](https://github.com/thephpleague/pipeline) was used as was [Illuminate\Support\Collection](http://laravel.com/api/master/Illuminate/Support/Collection.html) (all functions from this Collection are available in the chain.)


# Example

## This is our example group we will use
```php
use Arete\CollectionPipeline\CollectionPipeline as CP;

class MockEntity {
    public $id;
    public $name;
    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }
    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
}

// ids are just random for testing
$array = array(
    new MockEntity(null, "eric"), #0
    new MockEntity(10, "tim"),    #1
    new MockEntity(111, "beau"),  #2
    new MockEntity(11, "ross"),   #3
    new MockEntity(12, "sarah"),  #4
    new MockEntity(13, "taylor"), #5
    new MockEntity(-42, "lea"),   #6
    new MockEntity("eh", "phil"), #7
    new MockEntity(6, "larry"),   #8
    new MockEntity(10, "frank"),  #9
    new MockEntity(["eh"], "joe"),#10

    new MockEntity(99, "kayla"),  #12
    new MockEntity(0, "martin"),  #11
    new MockEntity(1, "brad"),    #13
    new MockEntity(2, "luke"),    #14
    new MockEntity(3, "paul"),    #15
    new MockEntity(4, "ash"),     #16
    new MockEntity(5, "davey"),   #17
    new MockEntity(18,"anthony"), #18
    new MockEntity(19,"tim"),     #19
);
```


## String functions
```php
$result = CP::from($array)->wheres('getId', 'is_string')->all();

# gives: [7 => $array[7]]
```

## `!` String functions
```php
$result = CP::from($array)->wheres('getId', '!is_string')->all();

# gives: everything in $array except #7
```

## Each
Use `::wheresEach` to compare the whole value without using any accessors.

### instanceof
```php
$result = CP::from($array)->wheres('instanceof', MockEntity::CLASS)->all();

# gives: everything in $array
```

### `!` instanceof
```php
$result = CP::from($array)->wheres('!instanceof', MockEntity::CLASS)->all();

# gives: empty array, they all are instances of MockEntity
```

## [comparison operators](http://php.net/manual/en/language.operators.comparison.php)
[comparison operator tests](https://github.com/aretecode/collection-pipeline/blob/master/tests/MathComparisonTest.php)

```php
$result = CP::from($array)->wheres('getId', '>', 110)->all();

# gives: [9 => $array[9]]
```

## chaining
```php
$result = CP::from($array)->wheres('getId', '!is_string')->wheres('getId', '>', 10)->wheres('getName', '===', 'tim')->all();

# gives: [19 => $array[19]]
```


## argument order:
The accessor return value (X) as the first argument, and the value you are using in the comparison (Y).

```php
// one does contain joe, but none contain derek
$stringItWouldBeIn = 'joe,derek';
$x = 'getName';
$y = $stringItWouldBeIn;

// containsSubString is from arete\support
$result = CP::from($array)->wheresYX($x, 'containsSubString', $y)->all();

# gives: [10 => $array[10]]
```




## Laravel Illuminate:
Since it extends [Illuminate\Support\Collection](http://laravel.com/api/master/Illuminate/Support/Collection.html), you can use their functions, such as:

```php
$result = CP::from($array)->wheres('id', 'is_string', null, 'property')->keys();

# gives: [7]
```


## Types:
* [methods](https://github.com/aretecode/collection-pipeline/blob/master/tests/MethodTest.php)
* [properties](https://github.com/aretecode/collection-pipeline/blob/master/tests/PropertyTest.php)
* [callables (using closure)](https://github.com/aretecode/collection-pipeline/blob/master/tests/CallableTest.php)
* [key | index](https://github.com/aretecode/collection-pipeline/blob/master/tests/ArrayTest.php)
By default it will first check if it's a `method`|`property`|`callable`|`index`.
If you want to only check for that particular type, in this case, `method`:

```php
// will only check for the method `getId`
$result = CP::from($array)->wheres('getId', '>', 110, 'method')->all();

# gives: [9 => $array[9]]
```

#### Reverse order
```php
// compares 110 < $payload->getId()
$result = CP::from($array)->wheres('getId', '<', 110, 'method', 'yx')->all();

# gives: [9 => $array[9]]
```

## [callables](https://github.com/aretecode/collection-pipeline/blob/master/tests/CallableTest.php)
```php
$stringItWouldBeIn = 'joe,jonathon';
$result = CP::from($array)->wheresYX('getName', 'containsSubString', $stringItWouldBeIn, 'callable')->all();
$result = CP::from($array)->wheres('getId', function($value) {
    if ($value == 'tim')
        return true
    return false;
})->all();

# gives: [10 => $array[10]]
```

## Value:
Value is an optional parameter, so if you want to check say, a `property` only, but have no value to compare it to:
```php
// will only check for the property `id`,
// it could be ['property', 'method'] if you wanted to use a method if the property was not there
// or it could be ['property', 'method', 'callable'] (which is default)
$result = CP::from($array)->wheres('id', 'is_string', null, 'property')->all();

# gives: [9 => $array[9]]
```


## Specification
[arete/specification](https://github.com/aretecode/specification)

```php

use Arete\Specification\Specification;
use Arete\Specification\SpecificationTrait;

class NameEquals implements Specification {
    use ParameterizedSpecification;
    use SpecificationTrait;

    public function isSatisfiedBy($entity) {
        if ($entity->getName() == $this->value)
            return true;
        return false;
    }
}

$result = CP::from($array)->satisfying(new NameEquals('tim'));

# gives: [10 => $array[10]]
```



## Installation
It can be installed from [Packagist](https://packagist.org/arete/collection-pipeline) using [Composer](https://getcomposer.org/).

In your project root just run:


`$ composer require arete/collection-pipeline`


Make sure that you’ve set up your project to [autoload Composer-installed packages](https://getcomposer.org/doc/00-intro.md#autoloading).


## Running tests
Run via the command line by going to `arete/collection-pipeline` directory and running `phpunit`

# @TODO:
* [ ] add ability to get an array with objects method values. Meaning, if I want to just get $objects->getName(); as an array of $objectNames and also maybe set what the key is?
* [x] option to pass in an array with the '!' if you want it to be not?
* [x] move ExpressionBuilder to Constructor()
* [ ] optimize the filters so they can be combined and done in one loop when requested as array / all()?
* [ ] pass in multiple string functions & comparison operators, such as `'is_string | is_int & >'` be able to do `('methodName', 'strlen >', 5)` (could use some Symfony\ExpressionLanguage optionally if alias are required) when this is done, it will really use the pipeline how it ought to
* [ ] similar to the last todo, but with chaining method calls
* [ ] move examples out of readme (except for 1), and into [examples/]
* [x] add in spaceship comparison operator depending on version (thanks @seldaek)
* [ ] `ands` using last method?
* [x] refactor `ExendedPipeline` so it is less of a God object.
* [ ] array key in `Specification`
* [x] array key for matching along with the method, property, and callable
* [x] abstract argsOrderYX & XY
* [x] remove null check from `::wheresComparison`
* [x] add ability to reverse arguments in expressions
* [ ] add casting of accessor