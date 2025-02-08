<?php declare(strict_types = 1);

namespace Contributte\Forms\Rendering;

use Nette\Forms\Controls;
use Nette\Forms\Form;
use Nette\Forms\Rendering\DefaultFormRenderer;

class Bootstrap4InlineRenderer extends DefaultFormRenderer
{

	/** @var mixed[] */
	public array $wrappers = [
		'form' => [
			'container' => '',
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
			'container' => '',
		],
		'pair' => [
			'container' => 'div class="form-group"',
			'.required' => 'required',
			'.optional' => null,
			'.odd' => null,
		],
		'control' => [
			'container' => null,
			'.odd' => null,
			'description' => 'span class="form-text"',
			'requiredsuffix' => '',
			'errorcontainer' => 'span class="form-text"',
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
			'container' => '',
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
		$form->getElementPrototype()->addClass('form-inline');

		$onlyButton = Helpers::onlyOneButton($form);

		foreach ($form->getControls() as $control) {
			if ($control instanceof Controls\BaseControl) {
				$control->getLabelPrototype()->addClass('col-form-label');
			}

			switch (true) {
				case $control instanceof Controls\Button:
				case $control instanceof Controls\Button:
					if (!Helpers::htmlClassContains($control->getControlPrototype(), 'btn')) {
						$control->getControlPrototype()->addClass($onlyButton ? 'btn btn-primary' : 'btn btn-default');
					}

					break;
				case $control instanceof Controls\TextBase:
				case $control instanceof Controls\SelectBox:
				case $control instanceof Controls\MultiSelectBox:
					$control->getControlPrototype()->addClass('form-control mx-sm-3');
					break;
				case $control instanceof Controls\Checkbox:
				case $control instanceof Controls\CheckboxList:
				case $control instanceof Controls\RadioList:
					$control->getSeparatorPrototype()->setName('div')->addClass('form-check form-check-inline');
					$control->getControlPrototype()->addClass('form-check-input');
					$control->getLabelPrototype()->addClass('form-check-label');
					break;
			}
		}

		return parent::render($form, $mode);
	}

}
