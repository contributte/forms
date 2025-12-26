<?php declare(strict_types = 1);

use Contributte\Forms\Controls\DateTime\TimeInput;
use Nette\Forms\Form;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

date_default_timezone_set('Europe/Prague');

test(function (): void {
	$form = new Form();
	$control = new TimeInput();
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="time"', $rendered);
	Assert::contains(' class="time-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput(null, 'H-i-s');
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="time-input text"', $rendered);
	Assert::contains(' data-format="H-i-s"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput(null, ['H-i-s', 'Y-m-d']);
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="time-input text"', $rendered);
	Assert::contains(' data-format="H-i-s"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput();
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="time"', $rendered);
	Assert::contains(' class="time-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' value="12:30"', $rendered);
	Assert::notContains(' data-value=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput(null, 'H-i-s');
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="time-input text"', $rendered);
	Assert::contains(' data-format="H-i-s"', $rendered);
	Assert::contains(' value="12-30-45"', $rendered);
	Assert::contains(' data-value="12:30"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput();
	$control->setDefaultValue('2022-01-05 12:30:45');
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="time"', $rendered);
	Assert::contains(' class="time-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' value="12:30"', $rendered);
	Assert::notContains(' data-value=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput(null, 'H-i-s');
	$control->setDefaultValue('2022-01-05 12:30:45');
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="time-input text"', $rendered);
	Assert::contains(' data-format="H-i-s"', $rendered);
	Assert::contains(' value="12-30-45"', $rendered);
	Assert::contains(' data-value="12:30"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput();
	$control->addRule(Form::MIN, 'Min', new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="time"', $rendered);
	Assert::contains(' class="time-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' min="12:30"', $rendered);
	Assert::notContains(' max=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput();
	$control->addRule(Form::MAX, 'Max', new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="time"', $rendered);
	Assert::contains(' class="time-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' max="12:30"', $rendered);
	Assert::notContains(' min=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput();
	$control->addRule(Form::RANGE, 'Range', [new DateTimeImmutable('2022-01-06 12:30:45'), new DateTimeImmutable('2022-01-08 12:45:45')]);
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="time"', $rendered);
	Assert::contains(' class="time-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' min="12:30"', $rendered);
	Assert::contains(' max="12:45"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput(null, 'H-i-s');
	$control->addRule(Form::MIN, 'Min', new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="time-input text"', $rendered);
	Assert::contains(' data-format="H-i-s"', $rendered);
	Assert::contains(' data-min="12:30"', $rendered);
	Assert::notContains(' data-max=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput(null, 'H-i-s');
	$control->addRule(Form::MAX, 'Max', new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="time-input text"', $rendered);
	Assert::contains(' data-format="H-i-s"', $rendered);
	Assert::contains(' data-max="12:30"', $rendered);
	Assert::notContains(' data-min=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput(null, 'H-i-s');
	$control->addRule(Form::RANGE, 'Range', [new DateTimeImmutable('2022-01-06 12:30:45'), new DateTimeImmutable('2022-01-08 12:45:45')]);
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="time-input text"', $rendered);
	Assert::contains(' data-format="H-i-s"', $rendered);
	Assert::contains(' data-min="12:30"', $rendered);
	Assert::contains(' data-max="12:45"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput();
	$control->setHtmlAttribute('class', 'custom-class');
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' class="custom-class time-input"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput(null, 'H-i-s');
	$control->setHtmlAttribute('class', 'custom-class');
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' class="custom-class time-input text"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new TimeInput();
	$control->setOption('settings', ['option1' => 'val1', 'option2' => 2]);
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' data-settings=\'{"option1":"val1","option2":2}\'', $rendered);
});
