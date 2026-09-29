![](https://heatbadger.now.sh/github/readme/contributte/forms/)

<p align=center>
  <a href="https://github.com/contributte/forms/actions"><img src="https://badgen.net/github/checks/contributte/forms/master?cache=300"></a>
  <a href="https://codecov.io/gh/contributte/forms"><img src="https://badgen.net/codecov/c/github/contributte/forms"></a>
  <a href="https://packagist.org/packages/contributte/forms"><img src="https://badgen.net/packagist/dm/contributte/forms"></a>
  <a href="https://packagist.org/packages/contributte/forms"><img src="https://badgen.net/packagist/v/contributte/forms"></a>
</p>
<p align=center>
  <a href="https://packagist.org/packages/contributte/forms"><img src="https://badgen.net/packagist/php/contributte/forms"></a>
  <a href="https://github.com/contributte/forms"><img src="https://badgen.net/github/license/contributte/forms"></a>
  <a href="https://bit.ly/ctteg"><img src="https://badgen.net/badge/support/gitter/cyan"></a>
  <a href="https://bit.ly/cttfo"><img src="https://badgen.net/badge/support/forum/yellow"></a>
  <a href="https://contributte.org/partners.html"><img src="https://badgen.net/badge/sponsor/donations/F96854"></a>
</p>

<p align=center>
Website 🚀 <a href="https://contributte.org">contributte.org</a> | Contact 👨🏻‍💻 <a href="https://f3l1x.io">f3l1x.io</a> | Twitter 🐦 <a href="https://twitter.com/contributte">@contributte</a>
</p>

Contributte Forms adds date and time inputs, Bootstrap 3, 4 and 5 renderers, two captchas and form factory DI
extensions to [Nette Forms](https://doc.nette.org/en/forms). Each part works on its own, so you can use a renderer
without the captchas or the date inputs without a DI container.

## Usage

To install the latest version of `contributte/forms`, use [Composer](https://getcomposer.org):

```bash
composer require contributte/forms
```

Requires PHP 8.2 or later and Nette Forms 3.2.

Add a date input and render the form with Bootstrap 5 classes:

```php
use Contributte\Forms\Controls\DateTime\DateInput;
use Contributte\Forms\Rendering\Bootstrap5VerticalRenderer;
use Nette\Forms\Form;

$form = new Form();
$form['birthday'] = new DateInput('Birthday'); // <input type="date">, value is DateTimeImmutable
$form->addSubmit('send', 'Send');
$form->setRenderer(new Bootstrap5VerticalRenderer());
```

The [documentation](.docs) covers the form factories, the date and time inputs and the captchas.

## Versions

| State       | Version | Branch   | Nette  | PHP     |
|-------------|---------|----------|--------|---------|
| dev         | `^0.7`  | `master` | `3.2+` | `>=8.2` |
| stable      | `^0.5`  | `master` | `3.0+` | `>=7.2` |
| stable      | `^0.3`  | `master` | `2.4`  | `>=7.1` |

## Development

Install the dependencies and run the checks:

```bash
make install   # install dependencies
make qa        # check code style and run static analysis
make tests     # run tests
```

Run `make` to list every target.

See [how to contribute](https://contributte.org/contributing.html) to this package.

This package is maintained by these authors.

<a href="https://github.com/f3l1x">
  <img width="80" height="80" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=80">
</a>

-----

Consider [supporting](https://contributte.org/partners.html) the **contributte** development team.
Thank you for using this package.
