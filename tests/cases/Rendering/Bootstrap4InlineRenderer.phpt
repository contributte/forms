<?php declare(strict_types = 1);

/**
 * Test: Rendering/Bootstrap4InlineRenderer
 */

use Contributte\Forms\Rendering\Bootstrap4InlineRenderer;
use Nette\Forms\Form;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

test(function (): void {
	$form = new Form();
	$form->addText('text1', 'Text 1');
	$form->addText('text2', 'Text 2')->setHtmlAttribute('class', 'my-text');
	$form->addSelect('select', 'Select', ['1' => 'Option 1', '2' => 'Option 2']);
	$form->addCheckbox('checkbox', 'Checkbox');
	$form->addSubmit('button', 'Button')->setHtmlAttribute('class', 'my-button');
	$renderer = new Bootstrap4InlineRenderer();
	Assert::matchFile(__DIR__ . '/expected/bootstrap4inline.html', $renderer->render($form));
});

test(function (): void {
	$form = new Form();
	$form->addSubmit('button1', 'Button 1')->setHtmlAttribute('class', 'my-button');
	$form->addSubmit('button2', 'Button 2')->setHtmlAttribute('class', 'btn btn-danger');
	$renderer = new Bootstrap4InlineRenderer();
	Assert::matchFile(__DIR__ . '/expected/bootstrap4inline.buttons.html', $renderer->render($form));
});
