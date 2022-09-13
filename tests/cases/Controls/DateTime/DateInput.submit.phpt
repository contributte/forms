<?php declare(strict_types = 1);

use Contributte\Forms\Controls\DateTime\DateInput;
use Nette\Forms\Form;
use Tester\Assert;
use Tester\Environment;
use Tests\Fixtures\Forms\TestForm;

require_once __DIR__ . '/../../../bootstrap.php';

if (!method_exists(Form::class, 'initialize')) {
	Environment::skip('This test requires nette/forms >= 3.1');
}

test(function (): void {
	$control = new DateInput();

	$form = new TestForm(['date' => '2022-01-05']);
	$form->addComponent($control, 'date');
	$form->submit();

	Assert::equal([], $form->getErrors());
	Assert::equal('2022-01-05', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00'), $control->getValue());
});

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');

	$form = new TestForm(['date' => '05.01.2022']);
	$form->addComponent($control, 'date');
	$form->submit();

	Assert::equal([], $form->getErrors());
	Assert::equal('05.01.2022', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00'), $control->getValue());
});

test(function (): void {
	$control = new DateInput();

	$form = new TestForm(['date' => 'invalid']);
	$form->addComponent($control, 'date');
	$form->submit();

	Assert::equal(['Invalid format'], $form->getErrors());
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
});

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');

	$form = new TestForm(['date' => 'invalid']);
	$form->addComponent($control, 'date');
	$form->submit();

	Assert::equal(['Invalid format'], $form->getErrors());
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
});

test(function (): void {
	$control = new DateInput();
	$control->setInvalidValueMessage('This value is invalid');

	$form = new TestForm(['date' => 'invalid']);
	$form->addComponent($control, 'date');
	$form->submit();

	Assert::equal(['This value is invalid'], $form->getErrors());
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
});

test(function (): void {
	$control = new DateInput(null, 'd.m.Y');
	$control->setInvalidValueMessage('This value is invalid');

	$form = new TestForm(['date' => 'invalid']);
	$form->addComponent($control, 'date');
	$form->submit();

	Assert::equal(['This value is invalid'], $form->getErrors());
	Assert::equal('invalid', $control->getRawValue());
	Assert::equal(null, $control->getValue());
});

test(function (): void {
	$control = new DateInput();
	$control->addRule(Form::MIN, 'Min %d', new DateTimeImmutable('2022-01-06 12:45:00'));

	$form = new TestForm(['date' => '2022-01-05']);
	$form->addComponent($control, 'date');
	$form->submit();

	Assert::equal(['Min 2022-01-06'], $form->getErrors());
	Assert::equal('2022-01-05', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00'), $control->getValue());
});

test(function (): void {
	$control = new DateInput();
	$control->addRule(Form::MAX, 'Max %d', new DateTimeImmutable('2022-01-04 12:15:00'));

	$form = new TestForm(['date' => '2022-01-05']);
	$form->addComponent($control, 'date');
	$form->submit();

	Assert::equal(['Max 2022-01-04'], $form->getErrors());
	Assert::equal('2022-01-05', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05 00:00'), $control->getValue());
});

test(function (): void {
	$control = new DateInput();
	$control->addRule(Form::RANGE, 'Range %d - %d', [new DateTimeImmutable('2022-01-06 12:15:00'), new DateTimeImmutable('2022-01-08 12:45:00')]);

	$form = new TestForm(['date' => '2022-01-07']);
	$form->addComponent($control, 'date');
	$form->submit();

	Assert::equal([], $form->getErrors());
	Assert::equal('2022-01-07', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-07'), $control->getValue());
});

test(function (): void {
	$control = new DateInput();
	$control->addRule(Form::RANGE, 'Range %d - %d', [new DateTimeImmutable('2022-01-06 12:15:00'), new DateTimeImmutable('2022-01-08 12:45:00')]);

	$form = new TestForm(['date' => '2022-01-05']);
	$form->addComponent($control, 'date');
	$form->submit();

	Assert::equal(['Range 2022-01-06 - 2022-01-08'], $form->getErrors());
	Assert::equal('2022-01-05', $control->getRawValue());
	Assert::equal(new DateTimeImmutable('2022-01-05'), $control->getValue());
});
