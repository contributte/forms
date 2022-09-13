<?php declare(strict_types = 1);

use Contributte\Forms\Controls\DateTime\DateInput;
use Nette\Forms\Form;
use Nette\Forms\Rule;
use Nette\Utils\DateTime as NetteDateTime;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

date_default_timezone_set('Europe/Prague');

// native - default

test(function (): void {
	$control = new DateInput();
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValueAs(DateTime::class));
});

// native - setDefaultValue

test(function (): void {
	$control = new DateInput();
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('2022-01-05', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateInput();
	$control->setDefaultValue(new DateTime('2022-01-05 12:30:45'));
	Assert::equal('2022-01-05', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateInput();
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45', new DateTimeZone('America/New_York')));
	Assert::equal('2022-01-05', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateInput();
	$control->setDefaultValue(120); // timestamp
	Assert::equal('1970-01-01', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 00:00:00'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 00:00:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateInput();
	$control->setDefaultValue('2022-01-05');
	Assert::equal('2022-01-05', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateInput();
	$control->setDefaultValue('2022-01-05 12:30:45');
	Assert::equal('2022-01-05', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateInput();
	$control->setDefaultValue('2022-01-05T12:30');
	Assert::equal('2022-01-05', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateInput();
	$control->setDefaultValue('invalid');
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValueAs(DateTime::class));
});

// custom - default

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	Assert::equal(null, $control->getRawValue());
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(null, $control->getValueAs(DateTime::class));
});

// custom - setDefaultValue

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('05.01.2022', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	$control->setDefaultValue(new DateTime('2022-01-05 12:30:45'));
	Assert::equal('05.01.2022', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	$control->setDefaultValue(120); // timestamp
	Assert::equal('01.01.1970', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 00:00:00'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 00:00:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	$control->setDefaultValue('05.01.2022');
	Assert::equal('05.01.2022', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	$control->setDefaultValue('2022-01-05 12:30:45');
	Assert::equal('05.01.2022', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	$control->setDefaultValue('2022-01-05T12:30');
	Assert::equal('05.01.2022', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	$control->setDefaultValue('invalid');
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValueAs(DateTime::class));
});

// native - addRule

test(function (): void {
	$control = new DateInput();
	$control->addRule(Form::MIN, 'Min %d', new DateTimeImmutable('2022-01-05 12:45:00'));
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getMin());
	Assert::equal(null, $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('2022-01-05', $rule->arg);
	}
});

test(function (): void {
	$control = new DateInput();
	$control->addRule(Form::MAX, 'Max %d', new DateTimeImmutable('2022-01-05 12:15:00'));
	Assert::equal(null, $control->getMin());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('2022-01-05', $rule->arg);
	}
});

test(function (): void {
	$control = new DateInput();
	$control->addRule(Form::RANGE, 'Range %d - %d', [
		new DateTimeImmutable('2022-01-05 12:15:00'),
		new DateTimeImmutable('2022-01-05 12:45:00'),
	]);
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getMin());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal(['2022-01-05', '2022-01-05'], $rule->arg);
	}
});

// custom - addRule

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	$control->addRule(Form::MIN, 'Min %d', new DateTimeImmutable('2022-01-05 12:45:00'));
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getMin());
	Assert::equal(null, $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('05.01.2022', $rule->arg);
	}
});

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	$control->addRule(Form::MAX, 'Max %d', new DateTimeImmutable('2022-01-05 12:15:00'));
	Assert::equal(null, $control->getMin());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('05.01.2022', $rule->arg);
	}
});

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	$control->addRule(Form::RANGE, 'Range %d - %d', [
		new DateTimeImmutable('2022-01-05 12:15:00'),
		new DateTimeImmutable('2022-01-05 12:45:00'),
	]);
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getMin());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal(['05.01.2022', '05.01.2022'], $rule->arg);
	}
});

// value type

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	$control->setValueType(DateTime::class);
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('05.01.2022', $control->getRawValue());
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValueAs(DateTime::class));
	Assert::equal('2022-01-05T00:00:00+01:00', $control->getValueAs(function (DateTimeImmutable $value) {
		return $value->format(DateTimeInterface::ATOM);
	}));
});

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	$control->setValueType(NetteDateTime::class);
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('05.01.2022', $control->getRawValue());
	Assert::equal(new NetteDateTime('2022-01-05 00:00:00'), $control->getValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValueAs(DateTime::class));
	Assert::equal('2022-01-05T00:00:00+01:00', $control->getValueAs(function (DateTimeImmutable $value) {
		return $value->format(DateTimeInterface::ATOM);
	}));
});

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	$control->setValueType(function (DateTimeImmutable $value) {
		return NetteDateTime::from($value);
	});
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('05.01.2022', $control->getRawValue());
	Assert::equal(new NetteDateTime('2022-01-05 00:00:00'), $control->getValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00:00'), $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(new DateTime('2022-01-05 00:00:00'), $control->getValueAs(DateTime::class));
	Assert::equal('2022-01-05T00:00:00+01:00', $control->getValueAs(function (DateTimeImmutable $value) {
		return $value->format(DateTimeInterface::ATOM);
	}));
});
