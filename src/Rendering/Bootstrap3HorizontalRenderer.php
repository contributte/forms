<?php declare(strict_types = 1);

namespace Contributte\Forms\Rendering;

use Nette\Forms\Controls;
use Nette\Forms\Form;

class Bootstrap3HorizontalRenderer extends AbstractBootstrapHorizontalRenderer
{

	public array $wrappers = [
		'form' => [
			'container' => null,
		],
		'error' => [
			'container' => 'div class="alert alert-danger"',
			'item' => 'p',
		],
		'group' => [
			'container' => 'fieldset',
			'label' => 'legend',
			'description' => 'p',
		],
		'controls' => [
			'container' => 'div',
		],
		'pair' => [
			'container' => 'div class=form-group',
			'.required' => 'required',
			'.optional' => null,
			'.odd' => null,
			'.error' => 'has-error',
		],
		'control' => [
			'container' => 'div class=col-sm-%colsControl%',
			'.odd' => null,
			'description' => 'span class=help-block',
			'requiredsuffix' => '',
			'errorcontainer' => 'span class=help-block',
			'erroritem' => '',
			'.required' => 'required',
			'.text' => 'text',
			'.password' => 'text',
			'.file' => 'text',
			'.submit' => 'button',
			'.image' => 'imagebutton',
			'.button' => 'button',
		],
		'label' => [
			'container' => 'div class="col-sm-%colsLabel% control-label"',
			'suffix' => null,
			'requiredsuffix' => '',
		],
		'hidden' => [
			'container' => 'div',
		],
	];

	/**
	 * Provides complete form rendering.
	 *
	 * @param string|null $mode 'begin', 'errors', 'ownerrors', 'body', 'end' or empty to render all
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function render(Form $form, ?string $mode = null): string
	{
		$form->getElementPrototype()->setNovalidate(true);

		$form->getElementPrototype()->addClass('form-horizontal');

		$onlyButton = Helpers::onlyOneButton($form);

		foreach ($form->getControls() as $control) {
			switch (true) {
				case $control instanceof Controls\Button:
					if (!Helpers::htmlClassContains($control->getControlPrototype(), 'btn')) {
						$control->getControlPrototype()->addClass($onlyButton ? 'btn btn-primary' : 'btn btn-default');
					}

					break;
				case $control instanceof Controls\TextBase:
				case $control instanceof Controls\SelectBox:
				case $control instanceof Controls\MultiSelectBox:
					$control->getControlPrototype()->addClass('form-control');
					break;
				case $control instanceof Controls\Checkbox:
				case $control instanceof Controls\CheckboxList:
				case $control instanceof Controls\RadioList:
					$control->getContainerPrototype()->setName('div')->addClass($control->getControlPrototype()->type);
					break;
			}
		}

		return parent::render($form, $mode);
	}

}
