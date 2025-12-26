<?php declare(strict_types = 1);

namespace Contributte\Forms\Rendering;

use Nette\Forms\Control;
use Nette\Forms\Controls;
use Nette\Forms\Form;
use Nette\Utils\Html;

class Bootstrap4HorizontalRenderer extends AbstractBootstrapHorizontalRenderer
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
			'container' => 'div class="form-group row"',
			'.required' => 'required',
			'.optional' => null,
			'.odd' => null,
		],
		'control' => [
			'container' => 'div class="col col-sm-%colsControl%"',
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
		$form->getElementPrototype()->setNovalidate(true);

		$onlyButton = Helpers::onlyOneButton($form);

		foreach ($form->getControls() as $control) {
			if ($control instanceof Controls\BaseControl
				&& !($control instanceof Controls\Checkbox)
				&& !($control instanceof Controls\CheckboxList)
				&& !($control instanceof Controls\RadioList)) {
				$control->getLabelPrototype()->addClass($this->replacePlaceholders('col-form-label col col-sm-%colsLabel%'));
			}

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
					$control->getContainerPrototype()->setName('div')->addClass('form-check');
					$control->getControlPrototype()->addClass('form-check-input');
					$control->getLabelPrototype()->addClass('form-check-label');
					break;
			}
		}

		return parent::render($form, $mode);
	}

	public function renderLabel(Control $control): Html
	{
		$label = parent::renderLabel($control);
		if ($control instanceof Controls\Checkbox || $control instanceof Controls\CheckboxList || $control instanceof Controls\RadioList) {
			$label->addHtml($this->replacePlaceholders('<div class="col col-sm-%colsLabel%"></div>'));
		}

		return $label;
	}

}
