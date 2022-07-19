<?php declare(strict_types = 1);

/**
 * Test: Rendering/Bootstrap4HorizontalRenderer
 */

use Contributte\Forms\Rendering\Bootstrap4HorizontalRenderer;
use Nette\Forms\Form;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

$form = new Form();
$form->addText('text1', 'Text 1');
$form->addText('text2', 'Text 2');
$form->addSelect('select', 'Select', ['1' => 'Option 1', '2' => 'Option 2']);
$form->addCheckbox('checkbox', 'Checkbox');
$form->addSubmit('button', 'Button');

test(function () use ($form): void {
	$renderer = new Bootstrap4HorizontalRenderer();
	Assert::matchFile(__DIR__ . '/expected/bootstrap4horizontal.html', $renderer->render($form));
});
