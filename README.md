# EnvDiff

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Downloads][ico-downloads]][link-downloads]

EnvDiff is tool to compare environment keys to find the difference between .env files and actualize them

# Usage

composer Scripts composer event
```$json
"scripts": {
    "post-update-cmd": "Lf\\EnvDiff\\Composer\\ScriptHandler::actualizeEnv"
}
```
[ico-version]: https://img.shields.io/packagist/v/tekill/env-diff.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/tekill/env-diff/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/tekill/env-diff.svg?style=flat-square
[ico-hhvm]: https://img.shields.io/hhvm/tekill/env-diff.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/tekill/env-diff.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/tekill/env-diff.svg?style=flat-square
[ico-sensio]: https://insight.sensiolabs.com/projects/1fb8cbab-f611-45b5-8a45-0113e433eab7/big.png

[link-packagist]: https://packagist.org/packages/tekill/env-diff
[link-travis]: https://travis-ci.org/tekill/env-diff
[link-scrutinizer]: https://scrutinizer-ci.com/g/tekill/env-diff/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/tekill/env-diff
[link-downloads]: https://packagist.org/packages/tekill/env-diff
[link-author]: https://github.com/tekill
[link-contributors]: ../../contributors
[link-sensio]: https://insight.sensiolabs.com/projects/1fb8cbab-f611-45b5-8a45-0113e433eab7