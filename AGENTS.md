# Contributte Forms

Instructions for AI coding agents working in this repository.

## Overview

`contributte/forms` adds controls, renderers, captchas and form factories to Nette Forms. It ships date and time
inputs, a submit-timing guard, Bootstrap 3, 4 and 5 renderers, two captchas (Wordcha questions and Seznam images)
and DI extensions that register form factories. It is a library of independent parts, not a form builder or a
theme.

- **PHP**: 8.2 to 8.5 (`>=8.2` in `composer.json`)
- **Package**: `contributte/forms`, namespace `Contributte\Forms\`
- **Extensions**: `DI\ApplicationFormFactoryExtension`, `DI\StandaloneFormFactoryExtension`,
  `Captcha\Wordcha\DI\WordchaExtension`, `Captcha\Seznam\DI\SeznamCaptchaExtension`
- **Integrates**: `nette/forms` 3.2; `nette/di` and `nette/application` 3.2 are optional (only in `require-dev`)

## Documentation

- `.docs/README.md` is the user documentation and the page on contributte.org. Update it in the same pull request
  when a control, option or extension changes.
- `DESIGN.md` describes the markup the renderers and controls produce, the CSS classes and `data-*` attributes.
  Read it before changing `src/Rendering/`, `src/Controls/` or the captcha containers.
- Organization rules for code, tests and tooling are in
  [contributte/contributte specs](https://github.com/contributte/contributte/tree/master/specs).

## Commands

```bash
# Install dependencies
make install

# Run all checks (PHPStan level 9 + code style), does not run tests
make qa

# Fix code style
make csf

# Run all tests, or one file
make tests
vendor/bin/tester -s -p php --colors 1 -C tests/Cases/Rendering/Bootstrap4VerticalRenderer.phpt

# Generate code coverage (coverage.html)
make coverage
```

CI runs the tests on PHP 8.2 to 8.5 and once on PHP 8.2 with `--prefer-lowest`.

## Conventions

- Tests are Nette Tester `.phpt` files in `tests/Cases`, grouped like `src/`. Older files use the global `test()`
  helper from `tests/bootstrap.php`, newer ones `Toolkit::test()` from `contributte/tester`.
- Renderer tests compare the whole output with `Assert::matchFile()` against `tests/Cases/Rendering/expected/*.html`.
  A markup change means updating the matching expected file in the same commit.
- Captcha tests mock `Factory`, `Generator` and `Validator` with Mockery, so they never call the network.

## Traps

- **Renderers change the form they render.** `render()` adds classes such as `form-control` and `is-invalid` to the
  control prototypes, and every renderer except the three inline ones sets `novalidate` on the form. The classes
  stay on the controls after rendering.
- **Horizontal renderers resolve `%colsLabel%` and `%colsControl%` late.** `AbstractBootstrapHorizontalRenderer`
  replaces them in `getValue()`, so wrapper strings must keep the placeholders; `setColumns()` changes them.
- **Bootstrap 5 renderers have no tests.** Only Bootstrap 3 and 4 have `expected/*.html` files. Bootstrap 5 also puts
  `form-control` on selects instead of `form-select`; see `DESIGN.md`.
- **The Seznam captcha calls the network in its constructor.** `SeznamProvider` runs `captcha.create` on
  `captcha.seznam.cz:443` on every `addSeznamCaptcha()` call, also on POST, and `verify()` makes a second call. Keep that out of unit tests.
- **The Wordcha hash key changes on every container build.** Without `WordchaExtension(true)` the extension bakes a
  random key into `WordchaUniqueFactory` at compile time, so forms rendered before a rebuild fail `verify()` after
  it. Answers are compared in lower case.
- **`wordcha.questions` is declared as `Expect::listOf('string')`,** but `QuestionDataSource` reads
  question => answer pairs. The keyed map shown in `.docs/README.md` fails nette/schema list validation, and no test
  covers the `questions` datasource.
- **`addWordcha()` and `addSeznamCaptcha()` are global extension methods.** The captcha extensions register them in
  the container `initialize()` when `auto` is `true`; captcha tests call `FormBinder::bind()` themselves.
- **`DateInput::register()` and its siblings lose to nette/forms 3.2.** `Container` there has its own `addDate()`,
  `addTime()` and `addDateTime()`, and a real method wins over an extension method, so those calls create the native
  Nette control. Date tests create the inputs with `new`; the `register()` examples in `.docs/README.md` are affected.
- **`ApplicationFormFactoryExtension` fails the container build without `nette/application`.** It throws
  `LogicalException` on purpose; use `StandaloneFormFactoryExtension` in plain Nette Forms apps.
- Usage, configuration and examples for users live in `.docs/README.md`, not here.
