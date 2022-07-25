<?php declare(strict_types = 1);

/**
 * Test: Inputs\ProtectionFastInput
 */

use Contributte\Forms\Controls\ProtectionFastInput;
use Nette\Forms\Form;
use Tester\Assert;
use Tester\Environment;
use Tests\Fixtures\Forms\TestForm;

require_once __DIR__ . '/../../bootstrap.php';

if (!method_exists(Form::class, 'initialize')) {
	Environment::skip('This test requires nette/forms >= 3.1');
}

// OK
test(function (): void {
	$form = new TestForm(['fast' => (string) (time() - 10)]);
	$form['fast'] = new ProtectionFastInput('+5 seconds');
	$form->submit();

	Assert::equal([], $form->getErrors());
});

// Form was send too fast
test(function (): void {
	$form = new TestForm(['fast' => (string) (time() - 3)]);
	$form['fast'] = new ProtectionFastInput('+5 seconds');
	$form->submit();

	Assert::equal(['Form was submitted too fast. Are you robot?'], $form->getErrors());
});

// From send too fast own message
test(function (): void {
	$form = new TestForm(['fast' => (string) (time() - 3)]);
	$form['fast'] = new ProtectionFastInput('+5 seconds', 'Bot? Bot?');
	$form->submit();

	Assert::equal(['Bot? Bot?'], $form->getErrors());
});
