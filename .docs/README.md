# Contributte Forms

## Content

- [Setup](#setup)
- Form factory DI extensions
  - [ApplicationFormFactoryExtension](#application-form-factory) (Nette\Application\UI\Form)
  - [StandaloneFormFactoryExtension](#standalone-form-factory) (Nette\Forms\Form)
- Controls
  - [Date/time inputs](#date-time-inputs) (DateTimeInput, DateInput, TimeInput)

## Setup

```bash
composer require contributte/forms
```

## DI extensions

### Application Form Factory

ApplicationFormFactory returns instance of `Nette\Application\UI\Form`. It should be used in place of StandaloneFormFactory if [nette/application](https://github.com/nette/application) is installed.

```neon
extensions:
	forms.application: Contributte\Forms\DI\ApplicationFormFactoryExtension
```

You can override it by your implementation.

```neon
services:
	forms.application.factory: My\FormFactory
```

Straightforward way is to **inject** the factory in a presenter.

```php
namespace App\Presenters;

use Contributte\Forms\IApplicationFormFactory;
use Nette\Application\UI\Form;

final class UserPresenter extends BasePresenter
{

	/** @var IApplicationFormFactory @inject */
	public $factory;

	protected function createComponentUserForm(): Form
	{
		$form = $this->factory->create();

		// Add inputs here!

		return $form;
	}

}
```

Even better is to use the factory in your custom form factory.

```php
namespace App\Forms;

use Contributte\Forms\IApplicationFormFactory;
use Nette\Application\UI\Form;

final class UserFormFactory
{

	/** @var IApplicationFormFactory */
	private $factory;

	public function __construct(IApplicationFormFactory $factory)
	{
		$this->factory = $factory;
	}

	public function create(): Form
	{
		$form = $this->factory->create();

		// Add inputs here!

		return $form;
	}

}
```

### Standalone Form Factory

StandaloneFormFactory returns instance of `Nette\Forms\Form`. It should be used only if [nette/application](https://github.com/nette/application) is not installed.

```neon
extensions:
	forms.standalone: Contributte\Forms\DI\StandaloneFormFactoryExtension
```

You can override it by your implementation.

```neon
services:
	forms.standalone.factory: My\FormFactory
```

Straightforward way is to **inject** factory in a presenter.

```php
namespace App\Presenters;

use Contributte\Forms\IStandaloneFormFactory;
use Nette\Forms\Form;

final class UserPresenter extends BasePresenter
{

	/** @var IStandaloneFormFactory @inject */
	public $factory;

	protected function createComponentUserForm(): Form
	{
		$form = $this->factory->create();

		// Add inputs here!

		return $form;
	}

}
```

Even better is to use the factory in your custom form factory.

```php
namespace App\Forms;

use Contributte\Forms\IStandaloneFormFactory;
use Nette\Forms\Form;

final class UserFormFactory
{

	/** @var IStandaloneFormFactory */
	private $factory;

	public function __construct(IStandaloneFormFactory $factory)
	{
		$this->factory = $factory;
	}

	public function create(): Form
	{
		$form = $this->factory->create();

		// Add inputs here!

		return $form;
	}

}
```
## Controls

### Date/time inputs

Features:
* support both native date/time/datetime-local html inputs and text input with custom format
* in custom format text input variant accept multiple formats
* can be configured to return value as `\DateTime`, `\DateTimeImmutable` object or any offspring (like `\Nette\DateTime` or `\Carbon\DateTime`)
* can be configured to return value as any object by setting callback that would transform DateTimeImmutable to required type (for example `\Brick\DateTime\LocalDate`)
* set (default) value by `\DateTimeInterface` object, string (in configured or any of standard formats) or int timestamp
* pass parameters (like formats, min/max, custom settings) to data attributes of control (so it can be used to configure JS date picker)
* support MIN, MAX and RANGE rules
* can handle different input/server timezones

#### Usage

You can either register extension methods by calling

```php
\Contributte\Forms\Controls\DateTime\DateTimeInput::register();
\Contributte\Forms\Controls\DateTime\DateInput::register();
\Contributte\Forms\Controls\DateTime\TimeInput::register();
```

then you can use it simply as

```php
$form = new \Nette\Forms\Form();
$form->addDateTime('datetime', 'Enter date and time');
$form->addDate('date', 'Enter date');
$form->addTime('time', 'Enter time');
```

Without additional parameter it will create native HTML5 inputs. If you want to create custom format text input add parameter with desired format.

```php
// to register extension method to add custom format text inputs by default
\Contributte\Forms\Controls\DateTime\DateTimeInput::register('d.m.Y H:i');

// or for single input
$form->addDateTime('datetime', 'Enter date and time', 'd.m.Y H:i');
```

Alternatively you can manually create control instance and add it to form

```php
$form = new \Nette\Forms\Form();
$control = new DateTimeInput($label);
$form->addComponent($control, $name);
```

or you can add this as method to your base form

```php

class MyForm extends \Nette\Forms\Form
{

	public function addDateTime(string $name, ?string $label = null)
	{
		$form = new \Nette\Forms\Form();
		$control = new DateTimeInput($label);
		$form->addComponent($control, $name);
		return $control;
	}

}
```

#### Native input types (datetime-local, date, time)

Default mode for all date/time inputs is to render native HTML5 inputs.
* DateTimeInput `<input type="datetime-local" />`
* DateInput `<input type="date" />`
* TimeInput `<input type="time" />`

#### Text input type - formats

If you set optional parameter `$format` then text input is generated and input is parsed against given format.

```php
$control = new DateTimeInput($label, "d.m.Y H:i");
```

* DateTimeInput `<input type="text" data-format="Y-m-d H:i" />`
* DateInput `<input type="text" data-format="Y-m-d"/>`
* TimeInput `<input type="text" data-format="H:i"/>`

You can set multiple accepted formats. First of then is considered as desired format and will be passed to rendered HTML.

```php
$control = new DateTimeInput($label, ["d.m.Y H:i", "Y-m-d H:i"]);
```

#### Returned value type

All inputs return value as \DateTimeImmutable by default, but you can change value type.

You can set vale type to any class implementing \DateTimeInterface.

```php
$control->setValueType(\DateTime::class);
```
```php
$control->setValueType(\Nette\Utils\DateTime::class);
```

Or set any factory callback that creates required value type from DateTimeInterface
```php
$control->setValueType([\Brick\DateTime\LocalDate::class, 'fromNativeDateTime'])
```
```php
$control->setValueType(function (\DateTimeImmutable $value) {
  return new MyValueType($value);
});
```

You can also manually get value as different type using method `getValueAs` that accepts same argument as `setValueType`:
```php
$control->setValueAs(\DateTime::class);
```

#### Default value

Default value can se set as:

* \DateTimeInterface object
* string with any custom format
* string with of standard date/time formats (for example "Y-m-d H:i:s")
* int representing timestamp.

#### Data attributes - for JavaScript picker

If you use custom format text input then all relevant settings are aautomatically passed to rendered HTML in form of data attributes. So oyu can use then to initialize your favourite JavaScript datetime picker.

* `data-format="..."` - desired value format
* `data-value="..."` - value in standard format
* `data-min="..."` - minimal value in standard format
* `data-max="..."` - maximal value in standard format
* `data-settings="..."` - additional settings in JSON (see bellow)

Note: Standardized formats are same as for related HTML5 native inputs - "Y-m-d H:i", "Y-m-d" or "H:i".

To set additonal settings use:
```php
$control->setOption('settings', ['option1' => 'val1', 'option2' => 2]);
```

#### Validation - min, max, range, invalidFormat

Validation of minimum, maximum and range is supported. Validation arguments accepts same values as setValue.

```php
$control->addRule(Form::RANGE, 'Range %d - %d', [
  new DateTimeImmutable('2022-01-05 12:15:00'),
  new DateTimeImmutable('2022-01-05 12:45:00')]
);
```

#### DateTimeInput - input timezone

DateTime input does not do any timezone conversions by default.

Values are transformed to/from input format as they are and timezone information is ignored or expected to be same as server timezone. This is all right as long as you server time zone, client timezone and all values timezone matches.

You can enable timezone conversions for given input by setting optional parameter `$inputTimezone`.

```php
$control = new DateTimeInput($label, "d.m.Y H:i", new DateTimeZone("America/New_York"));
```

Then all values (`setValue`, `setDefaultValue`, `addRule`) are converted to selected timezone before transforming it to given format. And returned value from control have given input timezone set.

You can also manually get value as in different Time zone using `getValueInTz` or `getValueInTzAs`. If timezone argument is null then default server timezone is used.
```php
$control->setValue(); // value in input timezone
$control->setValueInTz(); // value in server default timezone
$control->setValueInTz(new DateTimeZone('Americe/New_York')); // value in given timezone
```
```php
$control->setValueAs(DateTime::class); // value in input timezone as \DateTime
$control->setValueInTzAs(DateTime::class); // value in server default timezone as \DateTime
$control->setValueInTzAs(DateTime::class, new DateTimeZone('Americe/New_York')); // value in given timezone as \DateTime
```
