# BCP 47

**PHP helpers to validate and normalize [IETF BCP 47 language tag](https://en.wikipedia.org/wiki/IETF_language_tag).**

---

[![Static Analysis](https://github.com/Toobo/bcp47/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/Toobo/bcp47/actions/workflows/static-analysis.yml)
[![Unit Tests](https://github.com/Toobo/bcp47/actions/workflows/unit-tests.yml/badge.svg)](https://github.com/Toobo/bcp47/actions/workflows/unit-tests.yml)
[![Code Coverage](https://codecov.io/gh/Toobo/bcp47/graph/badge.svg?token=cBMyRfVpyK)](https://codecov.io/gh/Toobo/bcp47)
![PHP Version](https://img.shields.io/badge/PHP_8.3%2B-blue?style=flat&logo=php&logoColor=white&labelColor=black)
[![Mutation Tests](https://img.shields.io/badge/Mutation_Tests-Min_Covered_MSI_95%25-blue?style=flat&logo=php&logoColor=white&labelColor=black)](https://github.com/Toobo/bcp47/actions/workflows/mutation-tests.yml)

---


## Utility functions

```php
$isValid = Toobo\Bcp47::isValidTag('i-klingon');  // true
$isValid = Toobo\Bcp47::isValidTag('xy');         // false

$filtered = Toobo\Bcp47::filterTag('EN-us');      // "en-US"
$filtered = Toobo\Bcp47::filterTag('fr-latn-fx'); // "fr-FR"

$isRTL = Toobo\Bcp47::isRtl('he');                // true
$isRTL = Toobo\Bcp47::isRtl('en-us');             // false

var_export(Toobo\Bcp47::splitTag('En-ca-Newfound'));
array (
  'language' => 'en',
  'extLang' => '',
  'script' => '',
  'region' => 'CA',
  'variant' => 'newfound',
  'extension' => '',
  'privateUse' => '',
)
```



## The `Bcp47Tag` class

The `Toobo\Bcp47Tag` class offers an API similar to the utility functions, but it ensures it
encapsulates a valid tag, because it throws when instantiated with an invalid tag.

The class is `Stringable` and `JsonSerializable`, and it also implements the `Bcp47Code` interface
defined by the [**`wikimedia/bcp-47-code`**](https://packagist.org/packages/wikimedia/bcp-47-code)
package.

```php
$tag = Toobo\Bcp47Tag::new('En-ca-Newfound');

assert($tag->isSameCodeAs(Toobo\Bcp47Tag::new('en-CA-newfound')) === true);

assert($tag->language() === 'en');
assert($tag->extLang() === null);
assert($tag->script() === null);
assert($tag->region() === 'CA');
assert($tag->variant() === 'newfound');
assert($tag->extension() === null);
assert($tag->privateUse() === null);
assert($tag->isRtl() === false);

assert((string) $tag === 'en-CA-newfound');
assert($tag->toBcp47Code() === 'en-CA-newfound');
assert(json_encode($tag) === '{"language":"en","region":"CA","variant":"newfound"}');
```



## Validation

The `Bcp47Tag` class, as well as the `Bcp47::isValidTag()`, `Bcp47::filterTag()`, and 
`Bcp47::splitTag()` functions, all do validation.

The class throw on instantiation for invalid tags, while the functions returns, respectively,
`false`, `null`, and an array with all empty items.

The validation is not just about the _format_ but also the actual values. For example, `xy-IT`
looks like a valid tag, but the language "xy" does not exist, so the the tag is not valid.

The validation apply to all subtags (but "extension" and "privateUse"), and also _across_ subtags.
For example, the tag `ca-valencia` is valid (_Valencia variant of the Catalan language_),
but `en-valencia` is not, despite the language "en" and the variant "valencia" being valid per-se,
because there is no "valencia" variant for the English language.

Validation is done by comparing the values with the up-to-date list of all the registered BCP 47 
subtags, which includes over 8000 languages, and several hundreds of scripts, regions, and variants.



## Normalization

The `Bcp47Tag` class, as well as both `Bcp47::filterTag()` and `Bcp47::splitTag()` functions
"normalize" the given tag.

Normalization includes:
- Replace deprecated values with the new accepted value, when available. For example, the region
  code for the "Democratic Republic of the Congo" (formerly "Zaire") "ZR" is replaced with "CD".
- Case normalization (all lowercase, but uppercase region and title-case script.
- Replacement of numeric region codes with 2-chars alpha code, when available.
- Replacement of 3-chars language code (ISO 639-3) with 2-chars code (ISO 636-1), when available.



## FAQ

- Why is `Bc47` an **enum**?

This package's utility functions are stateless and pure PHP _functions_.

However, plain PHP functions can't be autoloaded. By using a case-less PHP enum, we get autoloading,
but unlike when using a class, we prevent anyone to extend or instantiate the class without 
intervening on the runtime code.

A case-less PHP enum is a _de facto_ autoload-enabling namespace for functions.



## Installation

Best served via Composer, the package name is `toobo/bcp47`.

```bash
composer require "toobo/bcp47:^1"
```



## Requirements

BCP 47 requires PHP 8.3+, and requires via Composer:

- "wikimedia/bcp-47-code" (GPL v2)

When installed for development, it also requires via Composer:

- "phpunit/phpunit" (BSD-3-Clause)
- "inpsyde/php-coding-standards" (MIT)
- "vimeo/psalm" (MIT)



## Security Issues

If you have identified a security issue, please email giuseppe.mazzapica [at] gmail.com and do not file an issue as they are public.



## License

Copyright (c), Giuseppe Mazzapica, and contributors.

This software is released under the ["MIT"](LICENSE) license.
