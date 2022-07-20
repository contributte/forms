<?php declare(strict_types = 1);

/**
 * Test: Rendering/Bootstrap3VerticalRenderer
 */

use Contributte\Forms\Rendering\Bootstrap3VerticalRenderer;
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
	$renderer = new Bootstrap3VerticalRenderer();
	Assert::matchFile(__DIR__ . '/expected/bootstrap3vertical.html', $renderer->render($form));
});
