<?php declare(strict_types = 1);

/**
 * Test: Inputs\ProtectionFastInput
 */

use Contributte\Forms\Controls\ProtectionFastInput;
use Nette\Forms\Form;
use Tester\Assert;
use Tester\Environment;

require_once __DIR__ . '/../../bootstrap.php';

if (!method_exists(Form::class, 'initialize')) {
	Environment::skip('This test requires nette/forms >= 3.1');
}

// OK
test(function (): void {
	$_SERVER['REQUEST_METHOD'] = 'POST';
	$_POST = ['btn' => '', 'fast' => (string) (time() - 10)];

	$form = new Form();
	$form->allowCrossOrigin();
	$form->addSubmit('btn')->onClick[] = function () {
	};

	$form['fast'] = $input = new ProtectionFastInput('+5 seconds');
	Form::initialize(true);
	$form->fireEvents();

	Assert::equal([], $form->getErrors());
});

// Form was send too fast
test(function (): void {
	$_SERVER['REQUEST_METHOD'] = 'POST';
	$_POST = ['btn' => '', 'fast' => (string) (time() - 3)];

	$form = new Form();
	$form->allowCrossOrigin();
	$form->addSubmit('btn')->onClick[] = function () {
	};

	$form['fast'] = $input = new ProtectionFastInput('+5 seconds');
	Form::initialize(true);
	$form->fireEvents();

	Assert::equal(['Form was submitted too fast. Are you robot?'], $form->getErrors());
});

// From send too fast own message
test(function (): void {
	$_SERVER['REQUEST_METHOD'] = 'POST';
	$_POST = ['btn' => '', 'fast' => (string) (time() - 3)];

	$form = new Form();
	$form->allowCrossOrigin();
	$form->addSubmit('btn')->onClick[] = function () {
	};

	$form['fast'] = $input = new ProtectionFastInput('+5 seconds', 'Bot? Bot?');
	Form::initialize(true);
	$form->fireEvents();

	Assert::equal(['Bot? Bot?'], $form->getErrors());
});
