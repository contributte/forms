<?php declare(strict_types = 1);

use Contributte\Forms\Controls\DateTime\DateTimeInput;
use Nette\Forms\Form;
use Tester\Assert;
use Tester\Environment;
use Tests\Fixtures\Forms\TestForm;

require_once __DIR__ . '/../../../bootstrap.php';

if (!method_exists(Form::class, 'initialize')) {
	Environment::skip('This test requires nette/forms >= 3.1');
}

test(function (): void {
	$control = new DateTimeInput();

	$form = new TestForm(['datetime' => '2022-01-05T12:30']);
	$form->addComponent($control, 'datetime');
	$form->submit();

	Assert::equal([], $form->getErrors());
	Assert::equal('2022-01-05T12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30'), $control->getValue());
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');

	$form = new TestForm(['datetime' => '05.01.2022 12:30']);
	$form->addComponent($control, 'datetime');
	$form->submit();

	Assert::equal([], $form->getErrors());
	Assert::equal('05.01.2022 12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30'), $control->getValue());
});

test(function (): void {
	$control = new DateTimeInput();

	$form = new TestForm(['datetime' => 'invalid']);
	$form->addComponent($control, 'datetime');
	$form->submit();

	Assert::equal(['Invalid format'], $form->getErrors());
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');

	$form = new TestForm(['datetime' => 'invalid']);
	$form->addComponent($control, 'datetime');
	$form->submit();

	Assert::equal(['Invalid format'], $form->getErrors());
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
});

test(function (): void {
	$control = new DateTimeInput();
	$control->setInvalidValueMessage('This value is invalid');

	$form = new TestForm(['datetime' => 'invalid']);
	$form->addComponent($control, 'datetime');
	$form->submit();

	Assert::equal(['This value is invalid'], $form->getErrors());
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->setInvalidValueMessage('This value is invalid');

	$form = new TestForm(['datetime' => 'invalid']);
	$form->addComponent($control, 'datetime');
	$form->submit();

	Assert::equal(['This value is invalid'], $form->getErrors());
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
});

test(function (): void {
	$control = new DateTimeInput();
	$control->addRule(Form::MIN, 'Min %d', new DateTimeImmutable('2022-01-05 12:45:00'));

	$form = new TestForm(['datetime' => '2022-01-05T12:30']);
	$form->addComponent($control, 'datetime');
	$form->submit();

	Assert::equal(['Min 2022-01-05 12:45'], $form->getErrors());
	Assert::equal('2022-01-05T12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30'), $control->getValue());
});

test(function (): void {
	$control = new DateTimeInput();
	$control->addRule(Form::MAX, 'Max %d', new DateTimeImmutable('2022-01-05 12:15:00'));

	$form = new TestForm(['datetime' => '2022-01-05T12:30']);
	$form->addComponent($control, 'datetime');
	$form->submit();

	Assert::equal(['Max 2022-01-05 12:15'], $form->getErrors());
	Assert::equal('2022-01-05T12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30'), $control->getValue());
});

test(function (): void {
	$control = new DateTimeInput();
	$control->addRule(Form::RANGE, 'Range %d - %d', [new DateTimeImmutable('2022-01-05 12:15:00'), new DateTimeImmutable('2022-01-05 12:45:00')]);

	$form = new TestForm(['datetime' => '2022-01-05T12:30']);
	$form->addComponent($control, 'datetime');
	$form->submit();

	Assert::equal([], $form->getErrors());
	Assert::equal('2022-01-05T12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30'), $control->getValue());
});

test(function (): void {
	$control = new DateTimeInput();
	$control->addRule(Form::RANGE, 'Range %d - %d', [new DateTimeImmutable('2022-01-05 12:15:00'), new DateTimeImmutable('2022-01-05 12:45:00')]);

	$form = new TestForm(['datetime' => '2022-01-05T12:50']);
	$form->addComponent($control, 'datetime');
	$form->submit();

	Assert::equal(['Range 2022-01-05 12:15 - 2022-01-05 12:45'], $form->getErrors());
	Assert::equal('2022-01-05T12:50', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:50'), $control->getValue());
});

// input timezone

test(function (): void {
	$control = new DateTimeInput(null, null, new DateTimeZone('America/New_York'));

	$form = new TestForm(['datetime' => '2022-01-05T12:30']);
	$form->addComponent($control, 'datetime');
	$form->submit();

	Assert::equal([], $form->getErrors());
	Assert::equal('2022-01-05T12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30', new DateTimeZone('America/New_York')), $control->getValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30', new DateTimeZone('America/New_York')), $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(new DateTime('2022-01-05 12:30', new DateTimeZone('America/New_York')), $control->getValueAs(DateTime::class));
	Assert::equal(new DateTimeImmutable('2022-01-05 18:30', new DateTimeZone('Europe/Prague')), $control->getValueInTz());
	Assert::equal(new DateTimeImmutable('2022-01-05 18:30', new DateTimeZone('Europe/Prague')), $control->getValueInTzAs(DateTimeImmutable::class));
	Assert::equal(new DateTimeImmutable('2022-01-05 17:30', new DateTimeZone('Europe/London')), $control->getValueInTzAs(DateTimeImmutable::class, new DateTimeZone('Europe/London')));
	Assert::equal(new DateTime('2022-01-05 18:30', new DateTimeZone('Europe/Prague')), $control->getValueInTzAs(DateTime::class));
	Assert::equal(new DateTime('2022-01-05 17:30', new DateTimeZone('Europe/London')), $control->getValueInTzAs(DateTime::class, new DateTimeZone('Europe/London')));
});

test(function (): void {
	$control = new DateTimeInput(null, 'd.m.Y H:i', new DateTimeZone('America/New_York'));

	$form = new TestForm(['datetime' => '05.01.2022 12:30']);
	$form->addComponent($control, 'datetime');
	$form->submit();

	Assert::equal([], $form->getErrors());
	Assert::equal('05.01.2022 12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30', new DateTimeZone('America/New_York')), $control->getValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 12:30', new DateTimeZone('America/New_York')), $control->getValueAs(DateTimeImmutable::class));
	Assert::equal(new DateTime('2022-01-05 12:30', new DateTimeZone('America/New_York')), $control->getValueAs(DateTime::class));
	Assert::equal(new DateTimeImmutable('2022-01-05 18:30', new DateTimeZone('Europe/Prague')), $control->getValueInTz());
	Assert::equal(new DateTimeImmutable('2022-01-05 18:30', new DateTimeZone('Europe/Prague')), $control->getValueInTzAs(DateTimeImmutable::class));
	Assert::equal(new DateTimeImmutable('2022-01-05 17:30', new DateTimeZone('Europe/London')), $control->getValueInTzAs(DateTimeImmutable::class, new DateTimeZone('Europe/London')));
	Assert::equal(new DateTime('2022-01-05 18:30', new DateTimeZone('Europe/Prague')), $control->getValueInTzAs(DateTime::class));
	Assert::equal(new DateTime('2022-01-05 17:30', new DateTimeZone('Europe/London')), $control->getValueInTzAs(DateTime::class, new DateTimeZone('Europe/London')));
});
