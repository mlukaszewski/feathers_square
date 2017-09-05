Unstable, please do not use for a moment.
-----------------------------------------
This is a fork of [blerou](https://github.com/blerou/turbulence.php) work.

## Done
* changed PDepend source - PEAR provides only version 1.1.14, now version 2.5.0 will be used (with PHP7 support).
* added -ignore options (for PDepend)

## TODO
* executing PDepend as part of PHP stack, not an external process.
* fix Unit Tests, write new one (goal: coverage of all APIs)


## Installation (with composer)

```
composer create-project mlukaszewski/feathers_square
```

Hopefully-meaningful Metrics
----------------------------

This work based on [Turbulence](https://github.com/chad/turbulence)

[A quick hack based on Michael Feathers' recent work](http://www.stickyminds.com/sitewide.asp?Function=edetail&ObjectType=COL&ObjectId=16679&tth=DYN&tt=siteemail&iDyn=2) in project churn and complexity

Usage
-----

		bin/turbulence_php -repo=/path/to/git/project -out=/tmp/output

It takes `/path/to/git/project` repository, calculates class/file changes and some kind of complexities, then create an out.json file under `/tmp/output` (it contains the raw data in JSON format).

		bin/turbulence_php -repo=/path/to/git/project -out=/tmp/output -path=src

When `-path` parameter presents only files (classes) under `src/` will be processed.

If everything went well a `viewer.html` will be generated under output (`/tmp/output`). It has no external dependency, so just launch it with your favorite browser.

		google-chrome /tmp/output/viewer.html

Example
-------

Let's create the metrics of Twig template engine:

		git clone git://github.com/blerou/turbulence.php.git
		git clone git://github.com/fabpot/Twig.git
		turbulence.php/bin/turbulence_php -repo=Twig -out=/tmp/Twig -path=lib
		google-chrome /tmp/Twig/viewer.html


Dependencies
------------

It uses [PDepend](http://pdepend.org/) to calculate complexity.

use composer to install this dependency:

```
composer install
```