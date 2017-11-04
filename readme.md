## adhocore/underscore

[![Latest Version](https://img.shields.io/github/release/adhocore/underscore.svg?style=flat-square)](https://github.com/adhocore/underscore/releases)
[![Travis Build](https://img.shields.io/travis/adhocore/underscore/master.svg?style=flat-square)](https://travis-ci.org/adhocore/underscore?branch=master)
[![Scrutinizer CI](https://img.shields.io/scrutinizer/g/adhocore/underscore.svg?style=flat-square)](https://scrutinizer-ci.com/g/adhocore/underscore/?branch=master)
[![Codecov branch](https://img.shields.io/codecov/c/github/adhocore/underscore/master.svg?style=flat-square)](https://codecov.io/gh/adhocore/underscore)
[![StyleCI](https://styleci.io/repos/108437038/shield)](https://styleci.io/repos/108437038)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Heavily work in progress and not yet ready!

## Installation

Requires PHP5.5 or later.

```bash
composer require adhocore/underscore

```

## Usage
```php
use Ahc\Underscore\Underscore;

$u = new Underscore([1, 2, 3]);

$u->contains(1); // true
$u->contains(4); // false
```
