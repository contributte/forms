<?php declare(strict_types = 1);

use Contributte\Forms\Controls\DateTime\DateTimeInput;
use Nette\Forms\Form;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

date_default_timezone_set('Europe/Prague');

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput();
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="datetime-local"', $rendered);
	Assert::contains(' class="datetime-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="datetime-input text"', $rendered);
	Assert::contains(' data-format="d.m.Y H:i"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput(null, ['d.m.Y H:i', 'Y-m-d H:i']);
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="datetime-input text"', $rendered);
	Assert::contains(' data-format="d.m.Y H:i"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput();
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="datetime-local"', $rendered);
	Assert::contains(' class="datetime-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' value="2022-01-05T12:30"', $rendered);
	Assert::notContains(' data-value=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->setDefaultValue(new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="datetime-input text"', $rendered);
	Assert::contains(' data-format="d.m.Y H:i"', $rendered);
	Assert::contains(' value="05.01.2022 12:30"', $rendered);
	Assert::contains(' data-value="2022-01-05T12:30"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput();
	$control->setDefaultValue('2022-01-05 12:30:45');
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="datetime-local"', $rendered);
	Assert::contains(' class="datetime-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' value="2022-01-05T12:30"', $rendered);
	Assert::notContains(' data-value=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->setDefaultValue('2022-01-05 12:30:45');
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="datetime-input text"', $rendered);
	Assert::contains(' data-format="d.m.Y H:i"', $rendered);
	Assert::contains(' value="05.01.2022 12:30"', $rendered);
	Assert::contains(' data-value="2022-01-05T12:30"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput();
	$control->addRule(Form::MIN, 'Min', new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="datetime-local"', $rendered);
	Assert::contains(' class="datetime-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' min="2022-01-05T12:30"', $rendered);
	Assert::notContains(' max=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput();
	$control->addRule(Form::MAX, 'Max', new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="datetime-local"', $rendered);
	Assert::contains(' class="datetime-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' max="2022-01-05T12:30"', $rendered);
	Assert::notContains(' min=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput();
	$control->addRule(Form::RANGE, 'Range', [new DateTimeImmutable('2022-01-05 12:30:45'), new DateTimeImmutable('2022-01-05 12:45:45')]);
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="datetime-local"', $rendered);
	Assert::contains(' class="datetime-input"', $rendered);
	Assert::notContains(' data-format=', $rendered);
	Assert::contains(' min="2022-01-05T12:30"', $rendered);
	Assert::contains(' max="2022-01-05T12:45"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->addRule(Form::MIN, 'Min', new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="datetime-input text"', $rendered);
	Assert::contains(' data-format="d.m.Y H:i"', $rendered);
	Assert::contains(' data-min="2022-01-05T12:30"', $rendered);
	Assert::notContains(' data-max=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->addRule(Form::MAX, 'Max', new DateTimeImmutable('2022-01-05 12:30:45'));
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="datetime-input text"', $rendered);
	Assert::contains(' data-format="d.m.Y H:i"', $rendered);
	Assert::contains(' data-max="2022-01-05T12:30"', $rendered);
	Assert::notContains(' data-min=', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput(null, 'd.m.Y H:i');
	$control->addRule(Form::RANGE, 'Range', [new DateTimeImmutable('2022-01-05 12:30:45'), new DateTimeImmutable('2022-01-05 12:45:45')]);
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' type="text"', $rendered);
	Assert::contains(' class="datetime-input text"', $rendered);
	Assert::contains(' data-format="d.m.Y H:i"', $rendered);
	Assert::contains(' data-min="2022-01-05T12:30"', $rendered);
	Assert::contains(' data-max="2022-01-05T12:45"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput();
	$control->setHtmlAttribute('class', 'custom-class');
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' class="custom-class datetime-input"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput(null, 'd.m.Y');
	$control->setHtmlAttribute('class', 'custom-class');
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' class="custom-class datetime-input text"', $rendered);
});

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput();
	$control->setOption('settings', ['option1' => 'val1', 'option2' => 2]);
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' data-settings=\'{"option1":"val1","option2":2}\'', $rendered);
});

// input timezone

test(function (): void {
	$form = new Form();
	$control = new DateTimeInput(null, null, new DateTimeZone('America/New_York'));
	$form->addComponent($control, 'datetime');
	$rendered = $form->getRenderer()->render($form);
	Assert::contains(' data-timezone="America/New_York"', $rendered);
});
