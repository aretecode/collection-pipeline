# Arete\CollectionPipeline
[![Build Status](https://secure.travis-ci.org/aretecode/collection-pipeline.svg)](https://travis-ci.org/aretecode/collection-pipeline)
[![Author](http://img.shields.io/badge/author-@aretecode-blue.svg)](https://twitter.com/aretecode)
[![Latest Unstable Version](https://poser.pugx.org/arete/collection-pipeline/v/unstable)](https://poser.pugx.org/arete/collection-pipeline/v/unstable)
[![License](https://poser.pugx.org/arete/collection-pipeline/license)](http://packagist.org/packages/arete/collection-pipeline)

Filter a collection of objects without making a bunch of loops & ifs.

After reading [Martin Fowler on the Collection Pipeline](http://martinfowler.com/articles/collection-pipeline/) I wanted to use something similar in PHP, thus, this was born. [League\Pipeline](https://github.com/thephpleague/pipeline) was used as was [Illuminate\Support\Collection](http://laravel.com/api/master/Illuminate/Support/Collection.html) (all functions from this Collection are available in the chain.) 


# Example

## This is our example group we will use
```php
use Arete\CollectionPipeline\CollectionPipeline as CP;

class MockValueObject {
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
    new MockValueObject(null, "eric"), #0
    new MockValueObject(10, "tim"),    #1
    new MockValueObject(111, "beau"),  #2
    new MockValueObject(11, "ross"),   #3
    new MockValueObject(12, "sarah"),  #4
    new MockValueObject(13, "taylor"), #5
    new MockValueObject(-42, "lea"),   #6
    new MockValueObject("eh", "phil"), #7
    new MockValueObject(6, "larry"),   #8
    new MockValueObject(10, "frank"),  #9
    new MockValueObject(["eh"], "joe"),#10

    new MockValueObject(99, "kayla"),  #12
    new MockValueObject(0, "martin"),  #11
    new MockValueObject(1, "brad"),    #13
    new MockValueObject(2, "luke"),    #14
    new MockValueObject(3, "paul"),    #15
    new MockValueObject(4, "ash"),     #16
    new MockValueObject(5, "davey"),   #17    
    new MockValueObject(18,"anthony"), #18
    new MockValueObject(19,"tim"),     #19
);    
```


## string functions
```php
$result = CP::from($array)->wheres('getId', 'is_string')->all();

# gives: [7 => $array[7]]
```

## `!` string functions
```php
$result = CP::from($array)->wheres('getId', '!is_string')->all();

# gives: everything in $array except #7
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
The property|method (X) as the first argument, and the value you are using in the comparison (Y).

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


## types: 
[methods](https://github.com/aretecode/collection-pipeline/blob/master/tests/MethodTest.php)
[properties](https://github.com/aretecode/collection-pipeline/blob/master/tests/PropertyTest.php)
By default it will first check if it's a `method`|`property`|`callable`.
If you want to only check for that method:

```php
// will only check for the method `getId`
$result = CP::from($array)->wheres('getId', '>', 110, 'method')->all();

# gives: [9 => $array[9]]
```

## [callables](https://github.com/aretecode/collection-pipeline/blob/master/tests/CallableTest.php)
```php
$stringItWouldBeIn = 'joe,jonathon';
$result = CP::from($array)->wheresYX('getName', 'containsSubString', $stringItWouldBeIn, 'callable')->all();
$result = CP::from($array)->wheres('getId', function($value) {
    if ($value == 'tim') {
        return true;
    }
    return false;
})->all();

# gives: [10 => $array[10]]
```

## value: 
Value is an optional parameter, so if you want to check say, a `property` only, but have no value to compare it to:
```php
// will only check for the property `id`,
// it could be ['property', 'method'] if you wanted to use a method if the property was not there
// or it could be ['property', 'method', 'callable'] (which is default)
$result = CP::from($array)->wheres('id', 'is_string', null, 'property')->all();

# gives: [9 => $array[9]]
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
* [ ] move examples out of readme (except for 1), and into [tests/]
* [x] add in spaceship comparison operator depending on version (thanks @seldaek)
