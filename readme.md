## adhocore/underscore

PHP underscore inspired &amp;/or cloned from awesome `_.js`. A set of utilities and data manipulation helpers providing convenience functionalites to deal with array, list, hash, functions and so on in a neat elegant and OOP way. Guaranteed to save you tons of boiler plate codes when churning complex data collection.

[![Latest Version](https://img.shields.io/github/release/adhocore/php-underscore.svg?style=flat-square)](https://github.com/adhocore/php-underscore/releases)
[![Travis Build](https://img.shields.io/travis/adhocore/php-underscore/master.svg?style=flat-square)](https://travis-ci.org/adhocore/php-underscore?branch=master)
[![Scrutinizer CI](https://img.shields.io/scrutinizer/g/adhocore/php-underscore.svg?style=flat-square)](https://scrutinizer-ci.com/g/adhocore/php-underscore/?branch=master)
[![Codecov branch](https://img.shields.io/codecov/c/github/adhocore/php-underscore/master.svg?style=flat-square)](https://codecov.io/gh/adhocore/php-underscore)
[![StyleCI](https://styleci.io/repos/108437038/shield)](https://styleci.io/repos/108437038)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

## Installation

Requires PHP5.6 or later.

```sh
composer require adhocore/underscore
```

## Usage and API

Although all of them are available with helper function `underscore($data)` or `new Ahc\Underscore($data)`,
the methods are grouped and organized in different heriarchy and classes according as their scope.
This keeps it maintainable and saves from having a God class.

#### Contents

- [Underscore](#underscore)
- [UnderscoreFunction](#underscorefunction)
- [UnderscoreArray](#underscorearray)
- [UnderscoreCollection](#underscorecollection)
- [UnderscoreBase](#underscorebase)
- [HigherOrderMessage](#higherordermessage)
- [ArrayAccess](#arrayaccess)
- [Arrayizes](#arrayizes)


---
### Underscore

<h4><a name="constant"></a>constant(mixed $value): callable</h4>

Generates a function that always returns a constant value.

```php
$fn = underscore()->constant([1, 2]);

$fn(); // [1, 2]
```

<h4><a name="noop"></a>noop(): void</h4>

No operation!
```php
underscore()->noop(); // void/null
```

<h4><a name="random"></a>random(int $min, int $max): int</h4>

Return a random integer between min and max (inclusive).

```php
$rand = underscore()->random(1, 10);
```

<h4><a name="times"></a>times(int $n, callable $fn): self</h4>

Run callable n times and create new collection.

```php
$fn = function ($i) { return $i * 2; };

underscore()->times(5, $fn)->get();
// [0, 2, 4, 6, 8]
```

<h4><a name="uniqueId"></a>uniqueId(string $prefix): string</h4>

Generate unique ID (unique for current go/session).

```php
$u  = underscore()->uniqueId();      // '1'
$u1 = underscore()->uniqueId();      // '2'
$u3 = underscore()->uniqueId('id:'); // 'id:3'
```


---
### UnderscoreFunction

<h4><a name="compose"></a>compose(callable $fn1, callable $fn2, ...callable|null $fn3): mixed</h4>

Returns a function that is the composition of a list of functions,
each consuming the return value of the function that follows.

```php
$c = underscore()->compose('strlen', 'strtolower', 'strtoupper');

$c('aBc.xYz'); // ABC.XYZ => abc.xyz => 7
```

<h4><a name="delay"></a>delay(callable $fn, int $wait): mixed</h4>

Cache the result of callback for given arguments and reuse that in subsequent call.

```php
$cb = underscore()->delay(function () { echo 'OK'; }, 100);

// waits 100ms
$cb(); // 'OK'
```

<h4><a name="memoize"></a>memoize(callable $fn): mixed</h4>

Returns a callable which when invoked caches the result for given arguments
and reuses that result in subsequent calls.

```php
$sum = underscore()->memoize(function ($a, $b) { return $a + $b; });

$sum(4, 5); // 9

// Uses memo:
$sum(4, 5); // 9
```

<h4><a name="throttle"></a>throttle(callable $fn, int $wait): mixed</h4>

Returns a callable that wraps given callable which can be only invoked
at most once per given $wait threshold.

```php
$fn = underscore()->throttle($callback, 100);

while (...) {
    $fn(); // it will be constantly called but not executed more than one in 100ms

    if (...) break;
}
```


---
### UnderscoreArray

<h4><a name="compact"></a>compact(): self</h4>

Get only the truthy items.

```php
underscore($array)->compact()->get();
// [1 => 'a', 4 => 2, 5 => [1]
```

<h4><a name="difference"></a>difference(array|mixed $data): self</h4>

Get the items whose value is not in given data.

```php
underscore([1, 2, 1, 'a' => 3, 'b' => [4]])->difference([1, [4]])->get();
// [1 => 2, 'a' => 3]
```

<h4><a name="findIndex"></a>findIndex(callable $fn): mixed|null</h4>

Find the first index that passes given truth test.

```php
$u = underscore([[1, 2], 'a' => 3, 'x' => 4, 'y' => 2, 'b' => 'B']);

$isEven = function ($i) { return is_numeric($i) && $i % 2 === 0; };

$u->findIndex();        // 0
$u->findIndex($isEven); // 'x'
```

<h4><a name="findLastIndex"></a>findLastIndex(callable $fn): mixed|null</h4>

Find the last index that passes given truth test.

```php
$u = underscore([[1, 2], 'a' => 3, 'x' => 4, 'y' => 2, 'b' => 'B']);

$isEven = function ($i) { return is_numeric($i) && $i % 2 === 0; };

$u->findLastIndex();        // 'b'
$u->findLastIndex($isEven); // 'y'
```

<h4><a name="first"></a>first(int $n): array|mixed</h4>

Get the first n items.

```php
underscore([1, 2, 3])->first(); // 1
underscore([1, 2, 3])->first(2); // [1, 2]
```

<h4><a name="flatten"></a>flatten(): self</h4>

Gets the flattened version of multidimensional items.

```php
$u = underscore([0, 'a', '', [[1, [2]]], 'b', [[[3]], 4, 'c', underscore([5, 'd'])]]);

$u->flatten()->get(); // [0, 'a', '', 1, 2, 'b', 3, 4, 'c', 5, 'd']
```

<h4><a name="indexOf"></a>indexOf(mixed $value): string|int|null</h4>

Find the first index of given value if available null otherwise.

```php
$u = underscore([[1, 2], 'a' => 2, 'x' => 4]);

$array->indexOf(2); // 'a'
```

<h4><a name="intersection"></a>intersection(array|mixed $data): self</h4>

Gets the items whose value is common with given data.

```php
$u = underscore([1, 2, 'a' => 3]);

$u->intersection([2, 'a' => 3, 3])->get(); // [1 => 2, 'a' => 3]
```

<h4><a name="last"></a>last(int $n): array|mixed</h4>

Get the last n items.

```php
underscore([1, 2, 3])->last();   // 3
underscore([1, 2, 3])->last(2);  // [2, 3]
```

<h4><a name="lastIndexOf"></a>lastIndexOf(mixed $value): string|int|null</h4>

Find the last index of given value if available null otherwise.

```php
$u = underscore([[1, 2], 'a' => 2, 'x' => 4, 'y' => 2]);

$array->lastIndexOf(2); // 'y'
```

<h4><a name="object"></a>object(string|null $className): self</h4>

Hydrate the items into given class or stdClass.

```php
underscore(['a', 'b' => 2])->object(); // stdClass(0: 'a', 'b': 2)
```

<h4><a name="range"></a>range(int $start, int $stop, int $step): self</h4>

Creates a new range from start to stop with given step.

```php
underscore()->range(4, 9)->get(); // [4, 5, 6, 7, 8, 9]
```

<h4><a name="sortedIndex"></a>sortedIndex(mixed $object, callable|string $fn): string|int|null</h4>

Gets the smallest index at which an object should be inserted so as to maintain order.

```php
underscore([1, 3, 5, 8, 11])->sortedIndex(9, null); // 4
```

<h4><a name="union"></a>union(array|mixed $data): self</h4>

Get the union/merger of items with given data.

```php
$u = underscore([1, 2, 'a' => 3]);

$u->union([3, 'a' => 4, 'b' => [5]])->get(); // [1, 2, 'a' => 4, 3, 'b' => [5]]
```

<h4><a name="unique"></a>unique(callable|string $fn): self</h4>

Gets the unique items using the id resulted from callback.

```php
$u = underscore([1, 2, 'a' => 3]);

$u->union([3, 'a' => 4, 'b' => [5]])->get();
// [1, 2, 'a' => 4, 3, 'b' => [5]]
```

<h4><a name="zip"></a>zip(array|mixed $data): self</h4>

Group the values from data and items having same indexes together.

```php
$u = underscore([1, 2, 'a' => 3, 'b' => 'B']);

$u->zip([2, 4, 'a' => 5])->get();
// [[1, 2], [2, 4], 'a' => [3, 5], 'b' => ['B', null]]
```


---
### UnderscoreCollection

<h4><a name="contains"></a>contains(mixed $item): bool</h4>

Check if the collection contains given item.

```php
$u = underscore(['a' => 1, 'b' => 2, 'c' => 3, 5]);

$u->contains(1);   // true
$u->contains('x'); // false
```

<h4><a name="countBy"></a>countBy(callable|string $fn): self</h4>

Count items in each group indexed by the result of callback.

```php
$u = underscore([
    ['a' => 0, 'b' => 1, 'c' => 1],
    ['a' => true, 'b' => false, 'c' => 'c'],
    ['a' => 2, 'b' => 1, 'c' => 2],
    ['a' => 1, 'b' => null, 'c' => 0],
]);

// by key 'a'
$u->countBy('a')->get();
// [0 => 1, 1 => 2, 2 => 1]
```

<h4><a name="each"></a>each(callable $fn): self</h4>

Apply given callback to each of the items in collection.

```php
$answers = [];
underscore([1, 2, 3])->each(function ($num) use (&$answers) {
    $answers[] = $num * 5;
});

$answers; // [5, 10, 15]
```

<h4><a name="every"></a>every(callable $fn): bool</h4>

Tests if all the items pass given truth test.

```php
$gt0 = underscore([1, 2, 3, 4])->every(function ($num) { return $num > 0; });

$gt0; // true
```

<h4><a name="filter"></a>filter(callable|string|null $fn): self</h4>

Find and return all the items that passes given truth test.

```php
$gt2 = underscore([1, 2, 4, 0, 3])->filter(function ($num) { return $num > 2; });

$gt2->values(); // [4, 3]
```

<h4><a name="find"></a>find(callable $fn, bool $useValue): mixed|null</h4>

Find the first item (or index) that passes given truth test.

```php
$num = underscore([1, 2, 4, 3])->find(function ($num) { return $num > 2; });

$num; // 4

$idx = underscore([1, 2, 4, 3])->find(function ($num) { return $num > 2; }, false);

$idx; // 2
```

<h4><a name="findWhere"></a>findWhere(array $props): mixed</h4>

Get the first item that contains all the given props (matching both index and value).

```php
$u = underscore([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 2], ['a' => 1, 'b' => 3]]);

$u->findWhere(['b' => 3]); // ['a' => 1, 'b' => 3]
```

<h4><a name="groupBy"></a>groupBy(callable|string $fn): self</h4>

Group items by using the result of callback as index. The items in group will have original index intact.

```php
$u = underscore([
    ['a' => 0, 'b' => 1, 'c' => 1],
    ['a' => true, 'b' => false, 'c' => 'c'],
    ['a' => 2, 'b' => 1, 'c' => 2],
    ['a' => 1, 'b' => null, 'c' => 0],
]);

// by key 'a'
$u->groupBy('a')->get();
// [
//  0 => [0 => ['a' => 0, 'b' => 1, 'c' => 1]],
//  1 => [1 => ['a' => true, 'b' => false, 'c' => 'c'], 3 => ['a' => 1, 'b' => null, 'c' => 0]],
//  2 => [2 => ['a' => 2, 'b' => 1, 'c' => 2]],
// ]
```

<h4><a name="indexBy"></a>indexBy(callable|string $fn): self</h4>

Reindex items by using the result of callback as new index.

```php
$u = underscore([
    ['a' => 0, 'b' => 1, 'c' => 1],
    ['a' => true, 'b' => false, 'c' => 'c'],
    ['a' => 2, 'b' => 1, 'c' => 2],
    ['a' => 1, 'b' => null, 'c' => 0],
]);

// by key 'a'
$u->indexBy('a')->get();
// [
//   0 => ['a' => 0, 'b' => 1, 'c' => 1],
//   1 => ['a' => 1, 'b' => null, 'c' => 0],
//   2 => ['a' => 2, 'b' => 1, 'c' => 2],
// ]
```

<h4><a name="invoke"></a>invoke(callable $fn): mixed</h4>

Invoke a callback using all of the items as arguments.

```php
$sum = underscore([1, 2, 4])->invoke(function () { return array_sum(func_get_args()); });

$sum; // 7
```

<h4><a name="map"></a>map(callable $fn): self</h4>

Update the value of each items with the result of given callback.

```php
$map = underscore([1, 2, 3])->map(function ($num) { return $num * 2; });

$map->get(); // [2, 4, 6]
```

<h4><a name="max"></a>max(callable|string|null $fn): mixed</h4>

Find the maximum value using given callback or just items.

```php
underscore([1, 5, 4])->max(); // 5
$u = underscore([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3], ['a' => 0, 'b' => 1]]);

$u->max(function ($i) { return $i['a'] + $i['b']; }); // 5
```

<h4><a name="min"></a>min(callable|string|null $fn): mixed</h4>

Find the minimum value using given callback or just items.

```php
underscore([1, 5, 4])->min(); // 1
$u = underscore([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3], ['a' => 0, 'b' => 1]]);

$u->min(function ($i) { return $i['a'] + $i['b']; }); // 1
```

<h4><a name="partition"></a>partition(callable|string $fn): self</h4>

Separate the items into two groups: one passing given truth test and other failing.

```php
$u = underscore(range(1, 10));

$oddEvn = $u->partition(function ($i) { return $i % 2; });

$oddEvn->get(0); // [1, 3, 5, 7, 9]
$oddEvn->get(1); // [2, 4, 6, 8, 10]
```

<h4><a name="pluck"></a>pluck(string|int $columnKey, string|int $indexKey): self</h4>

Pluck given property from each of the items.

```php
$u = underscore([['name' => 'moe', 'age' => 30], ['name' => 'curly']]);

$u->pluck('name')->get(); // ['moe', 'curly']
```

<h4><a name="reduce"></a>reduce(callable $fn, mixed $memo): mixed</h4>

Iteratively reduce the array to a single value using a callback function.

```php
$sum = underscore([1, 2, 3])->reduce(function ($sum, $num) {
    return $num + $sum;
}, 0);

$sum; // 6
```

<h4><a name="reduceRight"></a>reduceRight(callable $fn, mixed $memo): mixed</h4>

Same as reduce but applies the callback from right most item first.

```php
$concat = underscore([1, 2, 3, 4])->reduceRight(function ($concat, $num) {
    return $concat . $num;
}, '');

echo $concat; // '4321'
```

<h4><a name="reject"></a>reject(callable $fn): self</h4>

Find and return all the items that fails given truth test.

```php
$evens = underscore([1, 2, 3, 4, 5, 7, 6])->reject(function ($num) {
    return $num % 2 !== 0;
});

$evens->values(); // [2, 4, 6]
```

<h4><a name="sample"></a>sample(int $n): self</h4>

Get upto n items in random order.

```php
$u = underscore([1, 2, 3, 4]);

$u->sample(1)->count(); // 1
$u->sample(2)->count(); // 2
```

<h4><a name="shuffle"></a>shuffle(): self</h4>

Randomize the items keeping the indexes intact.

```php
underscore([1, 2, 3, 4])->shuffle()->get();
```

<h4><a name="some"></a>some(callable $fn): bool</h4>

Tests if some (at least one) of the items pass given truth test.

```php
$some = underscore([1, 2, 0, 4, -1])->some(function ($num) {
    return $num > 0;
});

$some; // true
```

<h4><a name="sortBy"></a>sortBy(callable $fn): self</h4>

Sort items by given callback and maintain indexes.

```php
$u = underscore(range(1, 15))->shuffle(); // randomize
$u->sortBy(null)->get(); // [1, 2, ... 15]

$u = underscore([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3], ['a' => 0, 'b' => 1]]);
$u->sortBy('a')->get();
// [2 => ['a' => 0, 'b' => 1], 0 => ['a' => 1, 'b' => 2], 1 => ['a' => 2, 'b' => 3]]

$u->sortBy(function ($i) { return $i['a'] + $i['b']; })->get();
// [2 => ['a' => 0, 'b' => 1], 0 => ['a' => 1, 'b' => 2], 1 => ['a' => 2, 'b' => 3]],
```

<h4><a name="where"></a>where(array $props): self</h4>

Filter only the items that contain all the given props (matching both index and value).

```php
$u = underscore([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 2], ['a' => 1, 'b' => 3, 'c']]);

$u->where(['a' => 1, 'b' => 2])->get(); // [['a' => 1, 'b' => 2, 'c']]
```


---
### UnderscoreBase

#### `_`(array|mixed $data): self

A static shortcut to constructor.

```php
$u = Ahc\Underscore\Underscore::_([1, 3, 7]);
```

#### `__`toString(): string

Stringify the underscore instance as json string.

```php
echo (string) underscore([1, 2, 3]); // [1, 2, 3]
echo (string) underscore(['a', 2, 'c' => 3]); // {0: "a", 1: 2, "c":3}
```

<h4><a name="asArray"></a>asArray(mixed $data, bool $cast): array</h4>

Get data as array.

```php
underscore()->asArray('one');                        // ['one']
underscore()->asArray([1, 2]);                       // [1, 2]
underscore()->asArray(underscore(['a', 1, 'c', 3])); // ['a', 1, 'c', 3]

underscore()->asArray(new class {
    public function toArray()
    {
        return ['a', 'b', 'c'];
    }
}); // ['a', 'b', 'c']

underscore()->asArray(new class implements \JsonSerializable {
    public function jsonSerialize()
    {
        return ['a' => 1, 'b' => 2, 'c' => 3];
    }
}); // ['a' => 1, 'b' => 2, 'c' => 3]
```

<h4><a name="clon"></a>clon(): self</h4>

Creates a shallow copy of itself.

```php
$u = underscore(['will', 'be', 'cloned']);

$u->clon() ==  $u; // true
$u->clon() === $u; // false
```

<h4><a name="count"></a>count(): int</h4>

Gets the count of items.

```php
underscore([1, 2, [3, 4]])->count(); // 3
underscore()->count();               // 0
```

<h4><a name="flat"></a>flat(array $array): array</h4>

Flatten a multi dimension array to 1 dimension.

```php
underscore()->flat([1, 2, [3, 4, [5, 6]]]); // [1, 2, 3, 4, 5, 6]
```

<h4><a name="get"></a>get(string|int|null $index): mixed</h4>

Get the underlying array data by index.

```php
$u = underscore([1, 2, 3]);

$u->get();  // [1, 2, 3]
$u->get(1); // 2
$u->get(3); // 3

```

<h4><a name="getData"></a>getData(): array</h4>

Get data.

```php
// same as `get()` without args:
underscore([1, 2, 3])->getData(); // [1, 2, 3]
```

<h4><a name="getIterator"></a>getIterator(): \ArrayIterator</h4>

Gets the iterator for looping.

```php
$it = underscore([1, 2, 3])->getIterator();

while ($it->valid()) {
    echo $it->current();
}
```

<h4><a name="invert"></a>invert(): self</h4>

Swap index and value of all the items. The values should be stringifiable.

```php
$u = underscore(['a' => 1, 'b' => 2, 'c' => 3]);

$u->invert()->get(); // [1 => 'a', 2 => 'b', 3 => 'c']
```

<h4><a name="jsonSerialize"></a>jsonSerialize(): array</h4>

Gets the data for json serialization.

```php
$u = underscore(['a' => 1, 'b' => 2, 'c' => 3]);

json_encode($u); // {"a":1,"b":2,"c":3}
```

<h4><a name="keys"></a>keys(): self</h4>

Get all the keys.

```php
$u = underscore(['a' => 1, 'b' => 2, 'c' => 3, 5]);

$u->keys()->get(); // ['a', 'b', 'c', 0]
```

<h4><a name="mixin"></a>mixin(string $name, \Closure $fn): self</h4>

Adds a custom handler/method to instance. The handler is bound to this instance.

```php
Ahc\Underscore\Underscore::mixin('square', function () {
    return $this->map(function ($v) { return $v * $v; });
});

underscore([1, 2, 3])->square()->get(); // [1, 4, 9]
```

<h4><a name="now"></a>now(): float</h4>

The current time in millisec.

```php
underscore()->now(); // 1529996371081
```

<h4><a name="omit"></a>omit(array|...string|...int $index): self</h4>

Omit the items having one of the blacklisted indexes.

```php
$u = underscore(['a' => 3, 7, 'b' => 'B', 1 => ['c', 5]]);

$u->omit('a', 0)->get(); // ['b' => 'B', 1 => ['c', 5]]
```

<h4><a name="pairs"></a>pairs(): self</h4>

Pair all items to use an array of index and value.

```php
$u = ['a' => 3, 7, 'b' => 'B'];

$u->pair(); // ['a' => ['a', 3], 0 => [0, 7], 'b' => ['b', 'B']
```

<h4><a name="pick"></a>pick(array|...string|...int $index): self</h4>

Pick only the items having one of the whitelisted indexes.

```php
$u = underscore(['a' => 3, 7, 'b' => 'B', 1 => ['c', 5]]);

$u->pick(0, 1)->get(); // [7, 1 => ['c', 5]]
```

<h4><a name="tap"></a>tap(callable $fn): self</h4>

Invokes callback fn with clone and returns original self.

```php
$u = underscore([1, 2]);

$tap = $u->tap(function ($_) { return $_->values(); });

$tap === $u; // true
```

<h4><a name="toArray"></a>toArray(): array</h4>

Convert the data items to array.

```php
$u = underscore([1, 3, 5, 7]);

$u->toArray(); // [1, 3, 5, 7]
```

<h4><a name="valueOf"></a>valueOf(): string</h4>

Get string value (JSON representation) of this instance.

```php
underscore(['a', 2, 'c' => 3])->valueOf(); // {0: "a", 1: 2, "c":3}
```

<h4><a name="values"></a>values(): self</h4>

Get all the values.

```php
$u = underscore(['a' => 1, 'b' => 2, 'c' => 3, 5]);

$u->values()->get(); // [1, 2, 3, 5]
```


---
### UnderscoreAliases

<h4><a name="collect"></a>collect(callable $fn): self</h4>

Alias of <a href="#map">map()</a>.

<h4><a name="detect"></a>detect(callable $fn, bool $useValue): mixed|null</h4>

Alias of <a href="#find">find()</a>.

<h4><a name="drop"></a>drop(int $n): array|mixed</h4>

Alias of <a href="#last">last()</a>.

<h4><a name="foldl"></a>foldl(callable $fn, mixed $memo): mixed</h4>

Alias of <a href="#reduce">reduce()</a>.

<h4><a name="foldr"></a>foldr(callable $fn, mixed $memo): mixed</h4>

Alias of <a href="#reduceRight">reduceRight()</a>.

<h4><a name="head"></a>head(int $n): array|mixed</h4>

Alias of <a href="#first">first()</a>.

<h4><a name="includes"></a>includes(): void</h4>

Alias of <a href="#contains">contains()</a>.

<h4><a name="inject"></a>inject(callable $fn, mixed $memo): mixed</h4>

Alias of <a href="#reduce">reduce()</a>.

<h4><a name="select"></a>select(callable|string|null $fn): self</h4>

Alias of <a href="#filter">filter()</a>.

<h4><a name="size"></a>size(): int</h4>

Alias of <a href="#count">count()</a>.

<h4><a name="tail"></a>tail(int $n): array|mixed</h4>

Alias of <a href="#last">last()</a>.

<h4><a name="take"></a>take(int $n): array|mixed</h4>

Alias of <a href="#first">first()</a>.

<h4><a name="uniq"></a>uniq(callable|string $fn): self</h4>

Alias of <a href="#unique">unique()</a>.

<h4><a name="without"></a>without(array|mixed $data): self</h4>

Alias of <a href="#difference">difference()</a>.

---
### HigherOrderMessage

A syntatic sugar to use elegant shorthand oneliner for complex logic often wrapped in closures.
See example below:

```php
// Higher Order Messaging
class HOM
{
    protected $n;
    public $square;

    public function __construct($n)
    {
        $this->n      = $n;
        $this->square = $n * $n;
    }

    public function even()
    {
        return $this->n % 2 === 0;
    }
}

$u = [new HOM(1), new HOM(2), new HOM(3), new HOM(4)];

// Filter `even()` items
$evens = $u->filter->even(); // 'even()' method of each items!

// Map each evens to their squares
$squares = $evens->map->square; // 'square' prop of each items!
// Gives an Underscore instance

// Get the data
$squares->get();
// [1 => 4, 3 => 16]
```

Without higher order messaging that would look like:

```php
$evens = $u->filter(function ($it) {
    return $it->even();
});

$squares = $evens->map(function ($it) {
    return $it->square;
});
```

---
### \ArrayAccess

Underscore instances can be treated as array:

```php
$u = underscore([1, 2, 'a' => 3]);

isset($u['a']); // true
isset($u['b']); // false

echo $u[1];     // 2

$u['b'] = 'B';
isset($u['b']); // true

unset($u[1]);
```

---
### Arrayizes

You can use this trait to arrayize all complex data.

```php
use Ahc\Underscore\Arrayizes;

class Any
{
    use Arrayizes;

    public function name()
    {
        $this->asArray($data);
    }
}
```

---
#### License

> [MIT](./LICENSE) | &copy; 2017-2018 | Jitendra Adhikari
