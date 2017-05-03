# EnvDiff

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

EnvDiff is tool to compare environment keys to find the difference between .env files and actualize them

# Usage

composer Scripts composer event
```$json
"scripts": {
    "post-update-cmd": "Lf\\EnvDiff\\Composer\\ScriptHandler::actualizeEnv"
}
```
[ico-version]: https://img.shields.io/packagist/v/Tekill/env-diff.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Tekill/env-diff/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/Tekill/env-diff.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Tekill/env-diff.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/Tekill/env-diff
[link-travis]: https://travis-ci.org/Tekill/env-diff
[link-scrutinizer]: https://scrutinizer-ci.com/g/Tekill/env-diff/code-structure/
[link-code-quality]: https://scrutinizer-ci.com/g/Tekill/env-diff
[link-author]: https://github.com/Tekill
