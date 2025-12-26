<?php declare(strict_types = 1);

use Contributte\Forms\Controls\DateTime\TimeInput;
use Nette\Forms\Form;
use Tester\Assert;
use Tester\Environment;
use Tests\Fixtures\Forms\TestForm;

require_once __DIR__ . '/../../../bootstrap.php';

if (!method_exists(Form::class, 'initialize')) {
	Environment::skip('This test requires nette/forms >= 3.1');
}

test(function (): void {
	$control = new TimeInput();

	$form = new TestForm(['time' => '12:30']);
	$form->addComponent($control, 'time');
	$form->submit();

	Assert::equal([], $form->getErrors());
	Assert::equal('12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30'), $control->getValue());
});

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');

	$form = new TestForm(['time' => '12-30-45']);
	$form->addComponent($control, 'time');
	$form->submit();

	Assert::equal([], $form->getErrors());
	Assert::equal('12-30-45', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30:45'), $control->getValue());
});

test(function (): void {
	$control = new TimeInput();

	$form = new TestForm(['time' => 'invalid']);
	$form->addComponent($control, 'time');
	$form->submit();

	Assert::equal(['Invalid format'], $form->getErrors());
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
});

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');

	$form = new TestForm(['time' => 'invalid']);
	$form->addComponent($control, 'time');
	$form->submit();

	Assert::equal(['Invalid format'], $form->getErrors());
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
});

test(function (): void {
	$control = new TimeInput();
	$control->setInvalidValueMessage('This value is invalid');

	$form = new TestForm(['time' => 'invalid']);
	$form->addComponent($control, 'time');
	$form->submit();

	Assert::equal(['This value is invalid'], $form->getErrors());
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
});

test(function (): void {
	$control = new TimeInput(null, 'H-i-s');
	$control->setInvalidValueMessage('This value is invalid');

	$form = new TestForm(['time' => 'invalid']);
	$form->addComponent($control, 'time');
	$form->submit();

	Assert::equal(['This value is invalid'], $form->getErrors());
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
});

test(function (): void {
	$control = new TimeInput();
	$control->addRule(Form::MIN, 'Min %d', new DateTimeImmutable('2022-01-05 12:45:00'));

	$form = new TestForm(['time' => '12:30']);
	$form->addComponent($control, 'time');
	$form->submit();

	Assert::equal(['Min 12:45'], $form->getErrors());
	Assert::equal('12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30'), $control->getValue());
});

test(function (): void {
	$control = new TimeInput();
	$control->addRule(Form::MAX, 'Max %d', new DateTimeImmutable('2022-01-05 12:15:00'));

	$form = new TestForm(['time' => '12:30']);
	$form->addComponent($control, 'time');
	$form->submit();

	Assert::equal(['Max 12:15'], $form->getErrors());
	Assert::equal('12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30'), $control->getValue());
});

test(function (): void {
	$control = new TimeInput();
	$control->addRule(Form::RANGE, 'Range %d - %d', [new DateTimeImmutable('2022-01-05 12:15:00'), new DateTimeImmutable('2022-01-05 12:45:00')]);

	$form = new TestForm(['time' => '12:30']);
	$form->addComponent($control, 'time');
	$form->submit();

	Assert::equal([], $form->getErrors());
	Assert::equal('12:30', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:30'), $control->getValue());
});

test(function (): void {
	$control = new TimeInput();
	$control->addRule(Form::RANGE, 'Range %d - %d', [new DateTimeImmutable('2022-01-05 12:15:00'), new DateTimeImmutable('2022-01-05 12:45:00')]);

	$form = new TestForm(['time' => '12:50']);
	$form->addComponent($control, 'time');
	$form->submit();

	Assert::equal(['Range 12:15 - 12:45'], $form->getErrors());
	Assert::equal('12:50', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('1970-01-01 12:50'), $control->getValue());
});
