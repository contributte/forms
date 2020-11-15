<?php declare(strict_types = 1);

/**
 * Test: Inputs\ProtectionFastInput
 */

use Contributte\Forms\Controls\ProtectionFastInput;
use Nette\Forms\Form;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

// OK
test(function (): void {
	$_SERVER['REQUEST_METHOD'] = 'POST';
	$_POST = ['btn' => '', 'fast' => (string) (time() - 10)];

	$form = new Form();
	$form->addSubmit('btn');

	$form['fast'] = $input = new ProtectionFastInput('+5 seconds');
	$form->fireEvents();

	Assert::equal([], $form->getErrors());
});

// Form was send too fast
test(function (): void {
	$_SERVER['REQUEST_METHOD'] = 'POST';
	$_POST = ['btn' => '', 'fast' => (string) (time() - 3)];

	$form = new Form();
	$form->addSubmit('btn');

	$form['fast'] = $input = new ProtectionFastInput('+5 seconds');
	$form->fireEvents();

	Assert::equal(['Form was submitted too fast. Are you robot?'], $form->getErrors());
});

// From send too fast own message
test(function (): void {
	$_SERVER['REQUEST_METHOD'] = 'POST';
	$_POST = ['btn' => '', 'fast' => (string) (time() - 3)];

	$form = new Form();
	$form->addSubmit('btn');

	$form['fast'] = $input = new ProtectionFastInput('+5 seconds', 'Bot? Bot?');
	$form->fireEvents();

	Assert::equal(['Bot? Bot?'], $form->getErrors());
});
