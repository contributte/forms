<?php declare(strict_types = 1);

use Contributte\Forms\Controls\DateTime\DateInput;
use Nette\Forms\Form;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

date_default_timezone_set('Europe/Prague');

test(function (): void {
	$form = new Form();
	$control = new DateInput();
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="date"', $rendered);
	Assert::contains(' class="date-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput(null, 'd.m.Y');
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="date-input text"', $rendered);
	Assert::contains(' data-format="d.m.Y"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput(null, ['d.m.Y', 'Y-m-d']);
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="date-input text"', $rendered);
	Assert::contains(' data-format="d.m.Y"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput();
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="date"', $rendered);
	Assert::contains(' class="date-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' value="2022-01-05"', $rendered);
	Assert::notContains(' data-value=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput(null, 'd.m.Y');
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="date-input text"', $rendered);
	Assert::contains(' data-format="d.m.Y"', $rendered);
	Assert::contains(' value="05.01.2022"', $rendered);
	Assert::contains(' data-value="2022-01-05"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput();
	$control->setDefaultValue('2022-01-05 12:30:45');
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="date"', $rendered);
	Assert::contains(' class="date-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' value="2022-01-05"', $rendered);
	Assert::notContains(' data-value=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput(null, 'd.m.Y');
	$control->setDefaultValue('2022-01-05 12:30:45');
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="date-input text"', $rendered);
	Assert::contains(' data-format="d.m.Y"', $rendered);
	Assert::contains(' value="05.01.2022"', $rendered);
	Assert::contains(' data-value="2022-01-05"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput();
	$control->addRule(Form::MIN, 'Min', new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="date"', $rendered);
	Assert::contains(' class="date-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' min="2022-01-05"', $rendered);
	Assert::notContains(' max=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput();
	$control->addRule(Form::MAX, 'Max', new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="date"', $rendered);
	Assert::contains(' class="date-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' max="2022-01-05"', $rendered);
	Assert::notContains(' min=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput();
	$control->addRule(Form::RANGE, 'Range', [new DateTimeImmutable('2022-01-06 12:30:45'), new DateTimeImmutable('2022-01-08 12:45:45')]);
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="date"', $rendered);
	Assert::contains(' class="date-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' min="2022-01-06"', $rendered);
	Assert::contains(' max="2022-01-08"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput(null, 'd.m.Y');
	$control->addRule(Form::MIN, 'Min', new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="date-input text"', $rendered);
	Assert::contains(' data-format="d.m.Y"', $rendered);
	Assert::contains(' data-min="2022-01-05"', $rendered);
	Assert::notContains(' data-max=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput(null, 'd.m.Y');
	$control->addRule(Form::MAX, 'Max', new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="date-input text"', $rendered);
	Assert::contains(' data-format="d.m.Y"', $rendered);
	Assert::contains(' data-max="2022-01-05"', $rendered);
	Assert::notContains(' data-min=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput(null, 'd.m.Y');
	$control->addRule(Form::RANGE, 'Range', [new DateTimeImmutable('2022-01-06 12:30:45'), new DateTimeImmutable('2022-01-08 12:45:45')]);
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="date-input text"', $rendered);
	Assert::contains(' data-format="d.m.Y"', $rendered);
	Assert::contains(' data-min="2022-01-06"', $rendered);
	Assert::contains(' data-max="2022-01-08"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput();
	$control->setHtmlAttribute('class', 'custom-class');
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' class="custom-class date-input"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput(null, 'd.m.Y');
	$control->setHtmlAttribute('class', 'custom-class');
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' class="custom-class date-input text"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateInput();
	$control->setOption('settings', ['option1' => 'val1', 'option2' => 2]);
	$form->addComponent($control, 'date');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' data-settings=\'{"option1":"val1","option2":2}\'', $rendered);
});
