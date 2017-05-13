# EnvDiff

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

EnvDiff is tool to compare environment keys to find the difference between .env files and actualize them.

# Installation

```
composer install tekill/env-diff
```

## Manual running
### Actualize variables
Compare `.env` with `.env.dist` and add missing variables to `.env` file
```
php ./vendor/bin/env-diff actualize
```

Compare `.env` with `.env.example` and add missing variables to `.env` file
```
php ./vendor/bin/env-diff actualize .env.example
```

Compare `.env-target` with `.env.example` and add missing variables to `.env-target` file
```
php ./vendor/bin/env-diff actualize .env.example .env-target
```

If you want to delete outdated values just run command with `-k=false` option

```
php ./vendor/bin/env-diff actualize -k=false
```

### Show differences
Command has same interface, arguments and options

Compare `.env` with `.env.dist` and show differences between them
```
php ./vendor/bin/env-diff diff
```

## Composer script

Add code block in `composer.json`:
```$json
"scripts": {
    "post-update-cmd": "Lf\\EnvDiff\\Composer\\ScriptHandler::actualizeEnv"
}
```

The `.env` will then be created or updated by the composer script, to match the structure of the dist 
file `.env.dist` by asking you the missing variables.

By default, the dist file is assumed to be in the same place than the target `.env`
file, suffixed by `.dist`. This can be changed in the configuration:

```json
{
    "extra": {
        "lf-env-diff": [
            {
                "dist": "path/to/env.dist",
                "target": "path/to/.env"
            }
        ]
    }
}
```

The script handler will ask you interactively for variables which are missing
in the target env file, using the value of the dist file as default value.
If composer is run in a non-interactive mode `--no-interaction`, the values of the dist file
will be used for missing variables.

**Warning:** This handler will overwrite any comments or spaces into your target `.env` file so handle with care.

### Managing multiple ignored files

The handler can manage multiple ignored files. To use this feature, the `lf-env-diff` extra should contain a 
JSON array with multiple configurations inside it instead of a configuration object:

```json
{
    "extra": {
        "lf-env-diff": [
            {
                 "dist": "path/to/.env.dist",
                 "target": "path/to/.env"
            },
            {
                 "dist": "path/to/.env.dist",
                 "target": "path/to/.env-test",
                 "keep-outdated": false
            }
        ]
    }
}
```

### Show difference

Add code block in `composer.json`:
```$json
"scripts": {
    "post-update-cmd": "Lf\\EnvDiff\\Composer\\ScriptHandler::showDifference"
}
```

This handler has same behavior as described before.

## Git hooks

You can use Git hook that gets triggered after any 'git pull' whenever one of the files specified has changed. 
Useful to update any web application dependency or sync configuration.

Create `post-merge` hook in `.git/hooks` directory of your project:
```
#/usr/bin/env bash

changed_files="$(git diff-tree -r --name-only --no-commit-id ORIG_HEAD HEAD)"

check_run() {
  echo "$changed_files" | grep -E --quiet "$1" && eval "$2"
}

# Aclualize env files if the `env.dist` file gets changed
check_run env.dist "php ./vendor/bin/env-diff aclualize"
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
