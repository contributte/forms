<?php declare(strict_types = 1);

use Contributte\Forms\Controls\DateTime\TimeInput;
use Nette\Forms\Form;
use Nette\Forms\Rule;
use Nette\Utils\DateTime as NetteDateTime;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

date_default_timezone_set('Europe/Prague');

// native - default

test(function (): void {
	$control = new TimeInput();
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValueAs(DateTime::class));
});

// native - setDefaultValue

test(function (): void {
	$control = new TimeInput();
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new TimeInput();
	$control->setDefaultValue(new DateTime('2022-01-05 12:30:45'));
	Assert::equal('12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new TimeInput();
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45', new DateTimeZone('America/New_York')));
	Assert::equal('12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new TimeInput();
	$control->setDefaultValue(120); // timestamp
	Assert::equal('01:02', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 01:02:00'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 01:02:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new TimeInput();
	$control->setDefaultValue('12:30');
	Assert::equal('12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new TimeInput();
	$control->setDefaultValue('2022-01-05 12:30:45');
	Assert::equal('12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new TimeInput();
	$control->setDefaultValue('2022-01-05T12:30');
	Assert::equal('12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new TimeInput();
	$control->setDefaultValue('invalid');
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValueAs(DateTime::class));
});

// custom - default

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	Assert::equal(null, $control->getRawValue());
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(null, $control->getValueAs(DateTime::class));
});

// custom - setDefaultValue

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('12-30-45', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:45'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 12:30:45'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	$control->setDefaultValue(new DateTime('2022-01-05 12:30:45'));
	Assert::equal('12-30-45', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:45'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 12:30:45'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	$control->setDefaultValue(120); // timestamp
	Assert::equal('01-02-00', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 01:02:00'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 01:02:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	$control->setDefaultValue('12-30-45');
	Assert::equal('12-30-45', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:45'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 12:30:45'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	$control->setDefaultValue('2022-01-05 12:30:45');
	Assert::equal('12-30-45', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:45'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 12:30:45'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	$control->setDefaultValue('2022-01-05T12:30');
	Assert::equal('12-30-00', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	$control->setDefaultValue('invalid');
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValueAs(DateTime::class));
});

// native - addRule

test(function (): void {
	$control = new TimeInput();
	$control->addRule(Form::MIN, 'Min %d', new DateTimeImmutable('2022-01-05 12:45:00'));
	Assert::equal(new DateTimeImmutable('1970-01-01 12:45:00'), $control->getMin());
	Assert::equal(null, $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('12:45', $rule->arg);
	}
});

test(function (): void {
	$control = new TimeInput();
	$control->addRule(Form::MAX, 'Max %d', new DateTimeImmutable('2022-01-05 12:15:00'));
	Assert::equal(null, $control->getMin());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:15:00'), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('12:15', $rule->arg);
	}
});

test(function (): void {
	$control = new TimeInput();
	$control->addRule(Form::RANGE, 'Range %d - %d', [
		new DateTimeImmutable('2022-01-05 12:15:00'),
		new DateTimeImmutable('2022-01-05 12:45:00'),
	]);
	Assert::equal(new DateTimeImmutable('1970-01-01 12:15:00'), $control->getMin());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:45:00'), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal(['12:15', '12:45'], $rule->arg);
	}
});

// custom - addRule

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	$control->addRule(Form::MIN, 'Min %d', new DateTimeImmutable('2022-01-05 12:45:00'));
	Assert::equal(new DateTimeImmutable('1970-01-01 12:45:00'), $control->getMin());
	Assert::equal(null, $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('12-45-00', $rule->arg);
	}
});

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	$control->addRule(Form::MAX, 'Max %d', new DateTimeImmutable('2022-01-05 12:15:00'));
	Assert::equal(null, $control->getMin());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:15:00'), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('12-15-00', $rule->arg);
	}
});

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	$control->addRule(Form::RANGE, 'Range %d - %d', [
		new DateTimeImmutable('2022-01-05 12:15:00'),
		new DateTimeImmutable('2022-01-05 12:45:00'),
	]);
	Assert::equal(new DateTimeImmutable('1970-01-01 12:15:00'), $control->getMin());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:45:00'), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal(['12-15-00', '12-45-00'], $rule->arg);
	}
});

// value type

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	$control->setValueType(DateTime::class);
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('12-30-45', $control->getRawValue());
	Assert::equal(new DateTime('1970-01-01 12:30:45'), $control->getValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:45'), $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(new DateTime('1970-01-01 12:30:45'), $control->getValueAs(DateTime::class));
	Assert::equal('1970-01-01T12:30:45+01:00', $control->getValueAs(fn (DateTimeImmutable $value) => $value->format(DateTimeInterface::ATOM)));
});

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	$control->setValueType(NetteDateTime::class);
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('12-30-45', $control->getRawValue());
	Assert::equal(new NetteDateTime('1970-01-01 12:30:45'), $control->getValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:45'), $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(new DateTime('1970-01-01 12:30:45'), $control->getValueAs(DateTime::class));
	Assert::equal('1970-01-01T12:30:45+01:00', $control->getValueAs(fn (DateTimeImmutable $value) => $value->format(DateTimeInterface::ATOM)));
});

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	$control->setValueType(fn (DateTimeImmutable $value) => NetteDateTime::from($value));
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('12-30-45', $control->getRawValue());
	Assert::equal(new NetteDateTime('1970-01-01 12:30:45'), $control->getValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:45'), $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(new DateTime('1970-01-01 12:30:45'), $control->getValueAs(DateTime::class));
	Assert::equal('1970-01-01T12:30:45+01:00', $control->getValueAs(fn (DateTimeImmutable $value) => $value->format(DateTimeInterface::ATOM)));
});
