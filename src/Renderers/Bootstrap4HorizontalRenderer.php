<?php declare(strict_types = 1);

namespace Contributte\Forms\Renderers;

use Nette\Forms\Controls;
use Nette\Forms\Form;
use Nette\Forms\IControl;
use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\Utils\Html;

class Bootstrap4HorizontalRenderer extends DefaultFormRenderer
{

	/** @var mixed[] */
	public $wrappers = [
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
			'container' => 'div class="col col-sm-9"',
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
	public function render(Form $form, $mode = null): string
	{
		$usedPrimary = false;

		$form->getElementPrototype()->setNovalidate(true);

		foreach ($form->getControls() as $control) {

			if ($control instanceof Controls\BaseControl
				&& !($control instanceof Controls\Checkbox)
				&& !($control instanceof Controls\CheckboxList)
				&& !($control instanceof Controls\RadioList)) {
				$control->getLabelPrototype()->addClass('col-form-label col col-sm-3');
			}

			switch (true) {
				case $control instanceof Controls\Button:
					/* @var $class string|null */
					$class = $control->getControlPrototype()->getAttribute('class');
					if ($class === null || mb_strpos($class, 'btn') === false) {
						$control->getControlPrototype()->addClass($usedPrimary === false ? 'btn btn-primary' : 'btn btn-secondary');
						$usedPrimary = true;
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
					$control->getSeparatorPrototype()->setName('div')->addClass('form-check');
					$control->getControlPrototype()->addClass('form-check-input');
					$control->getLabelPrototype()->addClass('form-check-label');
					break;
			}
		}

		return parent::render($form, $mode);
	}

	public function renderLabel(IControl $control): Html
	{
		$label = parent::renderLabel($control);
		if ($control instanceof Controls\Checkbox || $control instanceof Controls\CheckboxList || $control instanceof Controls\RadioList) {
			$label->addHtml('<div class="col col-sm-3"></div>');
		}
		return $label;
	}

}
