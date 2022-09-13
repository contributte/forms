<?php declare(strict_types = 1);

use Contributte\Forms\Controls\DateTime\DateTimeInput;
use Nette\Forms\Form;
use Nette\Forms\Rule;
use Nette\Utils\DateTime as NetteDateTime;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

date_default_timezone_set('Europe/Prague');

// native - default

test(function (): void {
	$control = new DateTimeInput();
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValueAs(DateTime::class));
});

// native - setDefaultValue

test(function (): void {
	$control = new DateTimeInput();
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('2022-01-05T12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateTimeInput();
	$control->setDefaultValue(new DateTime('2022-01-05 12:30:45'));
	Assert::equal('2022-01-05T12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateTimeInput();
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45', new DateTimeZone('America/New_York')));
	Assert::equal('2022-01-05T12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateTimeInput();
	$control->setDefaultValue(120); // timestamp
	Assert::equal('1970-01-01T01:02', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 01:02:00'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 01:02:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateTimeInput();
	$control->setDefaultValue('2022-01-05T12:30');
	Assert::equal('2022-01-05T12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateTimeInput();
	$control->setDefaultValue('2022-01-05 12:30:45');
	Assert::equal('2022-01-05T12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateTimeInput();
	$control->setDefaultValue('2022-01-05T12:30');
	Assert::equal('2022-01-05T12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateTimeInput();
	$control->setDefaultValue('invalid');
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValueAs(DateTime::class));
});

// custom - default

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	Assert::equal(null, $control->getRawValue());
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValueAs(DateTime::class));
});

// custom - setDefaultValue

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('05.01.2022 12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->setDefaultValue(new DateTime('2022-01-05 12:30:45'));
	Assert::equal('05.01.2022 12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->setDefaultValue(120); // timestamp
	Assert::equal('01.01.1970 01:02', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 01:02:00'), $control->getValue());
	Assert::equal(new DateTime('1970-01-01 01:02:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->setDefaultValue('05.01.2022 12:30');
	Assert::equal('05.01.2022 12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->setDefaultValue('2022-01-05 12:30:45');
	Assert::equal('05.01.2022 12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->setDefaultValue('2022-01-05T12:30');
	Assert::equal('05.01.2022 12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00'), $control->getValue());
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValueAs(DateTime::class));
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->setDefaultValue('invalid');
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
	Assert::equal(null, $control->getValueAs(DateTime::class));
});

// native - addRule

test(function (): void {
	$control = new DateTimeInput();
	$control->addRule(Form::MIN, 'Min %d', new DateTimeImmutable('2022-01-05 12:45:00'));
	Assert::equal(new DateTimeImmutable('2022-01-05 12:45:00'), $control->getMin());
	Assert::equal(null, $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('2022-01-05 12:45', $rule->arg);
	}
});

test(function (): void {
	$control = new DateTimeInput();
	$control->addRule(Form::MAX, 'Max %d', new DateTimeImmutable('2022-01-05 12:15:00'));
	Assert::equal(null, $control->getMin());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:15:00'), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('2022-01-05 12:15', $rule->arg);
	}
});

test(function (): void {
	$control = new DateTimeInput();
	$control->addRule(Form::RANGE, 'Range %d - %d', [
		new DateTimeImmutable('2022-01-05 12:15:00'),
		new DateTimeImmutable('2022-01-05 12:45:00'),
	]);
	Assert::equal(new DateTimeImmutable('2022-01-05 12:15:00'), $control->getMin());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:45:00'), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal(['2022-01-05 12:15', '2022-01-05 12:45'], $rule->arg);
	}
});

// custom - addRule

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->addRule(Form::MIN, 'Min %d', new DateTimeImmutable('2022-01-05 12:45:00'));
	Assert::equal(new DateTimeImmutable('2022-01-05 12:45:00'), $control->getMin());
	Assert::equal(null, $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('05.01.2022 12:45', $rule->arg);
	}
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->addRule(Form::MAX, 'Max %d', new DateTimeImmutable('2022-01-05 12:15:00'));
	Assert::equal(null, $control->getMin());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:15:00'), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('05.01.2022 12:15', $rule->arg);
	}
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->addRule(Form::RANGE, 'Range %d - %d', [
		new DateTimeImmutable('2022-01-05 12:15:00'),
		new DateTimeImmutable('2022-01-05 12:45:00'),
	]);
	Assert::equal(new DateTimeImmutable('2022-01-05 12:15:00'), $control->getMin());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:45:00'), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal(['05.01.2022 12:15', '05.01.2022 12:45'], $rule->arg);
	}
});

// input timezone - setDefaultValue

test(function (): void {
	$control = new DateTimeInput(null, null, new DateTimeZone('America/New_York'));
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('2022-01-05T06:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 06:30:00', new DateTimeZone('America/New_York')), $control->getValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 06:30:00', new DateTimeZone('America/New_York')), $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(new DateTime('2022-01-05 06:30:00', new DateTimeZone('America/New_York')), $control->getValueAs(DateTime::class));
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00', new DateTimeZone('Europe/Prague')), $control->getValueInTz());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00', new DateTimeZone('Europe/Prague')), $control->getValueInTzAs(DateTimeImmutable::class));
	Assert::equal(new DateTimeImmutable('2022-01-05 11:30:00', new DateTimeZone('Europe/London')), $control->getValueInTzAs(DateTimeImmutable::class, new DateTimeZone('Europe/London')));
	Assert::equal(new DateTime('2022-01-05 12:30:00', new DateTimeZone('Europe/Prague')), $control->getValueInTzAs(DateTime::class));
	Assert::equal(new DateTime('2022-01-05 11:30:00', new DateTimeZone('Europe/London')), $control->getValueInTzAs(DateTime::class, new DateTimeZone('Europe/London')));
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i', new DateTimeZone('America/New_York'));
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('05.01.2022 06:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 06:30:00', new DateTimeZone('America/New_York')), $control->getValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 06:30:00', new DateTimeZone('America/New_York')), $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(new DateTime('2022-01-05 06:30:00', new DateTimeZone('America/New_York')), $control->getValueAs(DateTime::class));
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00', new DateTimeZone('Europe/Prague')), $control->getValueInTz());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00', new DateTimeZone('Europe/Prague')), $control->getValueInTzAs(DateTimeImmutable::class));
	Assert::equal(new DateTimeImmutable('2022-01-05 11:30:00', new DateTimeZone('Europe/London')), $control->getValueInTzAs(DateTimeImmutable::class, new DateTimeZone('Europe/London')));
	Assert::equal(new DateTime('2022-01-05 12:30:00', new DateTimeZone('Europe/Prague')), $control->getValueInTzAs(DateTime::class));
	Assert::equal(new DateTime('2022-01-05 11:30:00', new DateTimeZone('Europe/London')), $control->getValueInTzAs(DateTime::class, new DateTimeZone('Europe/London')));
});

// input timezone - native - addRule

test(function (): void {
	$control = new DateTimeInput(null, null, new DateTimeZone('America/New_York'));
	$control->addRule(Form::MIN, 'Min %d', new DateTimeImmutable('2022-01-05 12:45:00'));
	Assert::equal(new DateTimeImmutable('2022-01-05 06:45:00', new DateTimeZone('America/New_York')), $control->getMin());
	Assert::equal(null, $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('2022-01-05 06:45', $rule->arg);
	}
});

test(function (): void {
	$control = new DateTimeInput(null, null, new DateTimeZone('America/New_York'));
	$control->addRule(Form::MAX, 'Max %d', new DateTimeImmutable('2022-01-05 12:15:00'));
	Assert::equal(null, $control->getMin());
	Assert::equal(new DateTimeImmutable('2022-01-05 06:15:00', new DateTimeZone('America/New_York')), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('2022-01-05 06:15', $rule->arg);
	}
});

test(function (): void {
	$control = new DateTimeInput(null, null, new DateTimeZone('America/New_York'));
	$control->addRule(Form::RANGE, 'Range %d - %d', [
		new DateTimeImmutable('2022-01-05 12:15:00'),
		new DateTimeImmutable('2022-01-05 12:45:00'),
	]);
	Assert::equal(new DateTimeImmutable('2022-01-05 06:15:00', new DateTimeZone('America/New_York')), $control->getMin());
	Assert::equal(new DateTimeImmutable('2022-01-05 06:45:00', new DateTimeZone('America/New_York')), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal(['2022-01-05 06:15', '2022-01-05 06:45'], $rule->arg);
	}
});

// input timezone - custom - addRule

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i', new DateTimeZone('America/New_York'));
	$control->addRule(Form::MIN, 'Min %d', new DateTimeImmutable('2022-01-05 12:45:00'));
	Assert::equal(new DateTimeImmutable('2022-01-05 06:45:00', new DateTimeZone('America/New_York')), $control->getMin());
	Assert::equal(null, $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('05.01.2022 06:45', $rule->arg);
	}
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i', new DateTimeZone('America/New_York'));
	$control->addRule(Form::MAX, 'Max %d', new DateTimeImmutable('2022-01-05 12:15:00'));
	Assert::equal(null, $control->getMin());
	Assert::equal(new DateTimeImmutable('2022-01-05 06:15:00', new DateTimeZone('America/New_York')), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal('05.01.2022 06:15', $rule->arg);
	}
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i', new DateTimeZone('America/New_York'));
	$control->addRule(Form::RANGE, 'Range %d - %d', [
		new DateTimeImmutable('2022-01-05 12:15:00'),
		new DateTimeImmutable('2022-01-05 12:45:00'),
	]);
	Assert::equal(new DateTimeImmutable('2022-01-05 06:15:00', new DateTimeZone('America/New_York')), $control->getMin());
	Assert::equal(new DateTimeImmutable('2022-01-05 06:45:00', new DateTimeZone('America/New_York')), $control->getMax());
	/** @var Rule $rule */
	foreach ($control->getRules() as $rule) {
		Assert::equal(['05.01.2022 06:15', '05.01.2022 06:45'], $rule->arg);
	}
});

// value type

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->setValueType(DateTime::class);
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('05.01.2022 12:30', $control->getRawValue());
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00'), $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValueAs(DateTime::class));
	Assert::equal('2022-01-05T12:30:00+01:00', $control->getValueAs(function (DateTimeImmutable $value) {
		return $value->format(DateTimeInterface::ATOM);
	}));
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->setValueType(NetteDateTime::class);
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('05.01.2022 12:30', $control->getRawValue());
	Assert::equal(new NetteDateTime('2022-01-05 12:30:00'), $control->getValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00'), $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValueAs(DateTime::class));
	Assert::equal('2022-01-05T12:30:00+01:00', $control->getValueAs(function (DateTimeImmutable $value) {
		return $value->format(DateTimeInterface::ATOM);
	}));
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->setValueType(function (DateTimeImmutable $value) {
		return NetteDateTime::from($value);
	});
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	Assert::equal('05.01.2022 12:30', $control->getRawValue());
	Assert::equal(new NetteDateTime('2022-01-05 12:30:00'), $control->getValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30:00'), $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(new DateTime('2022-01-05 12:30:00'), $control->getValueAs(DateTime::class));
	Assert::equal('2022-01-05T12:30:00+01:00', $control->getValueAs(function (DateTimeImmutable $value) {
		return $value->format(DateTimeInterface::ATOM);
	}));
});
