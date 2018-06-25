<?php declare(strict_types = 1);

namespace Contributte\Forms\Renderers;

use Nette\Forms\Controls;
use Nette\Forms\Form;
use Nette\Forms\Rendering\DefaultFormRenderer;

class Bootstrap3HorizontalRenderer extends DefaultFormRenderer
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
			'container' => 'div class=form-group',
			'.required' => 'required',
			'.optional' => null,
			'.odd' => null,
			'.error' => 'has-error',
		],
		'control' => [
			'container' => 'div class=col-sm-9',
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
			'container' => 'div class="col-sm-3 control-label"',
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

		$form->getElementPrototype()->addClass('form-horizontal');

		foreach ($form->getControls() as $control) {

			switch (true) {
				case $control instanceof Controls\Button:
					/** @var string|null $class */
					$class = $control->getControlPrototype()->getAttribute('class');
					if ($class === null || mb_strpos($class, 'btn') === false) {
						$control->getControlPrototype()->addClass($usedPrimary === false ? 'btn btn-primary' : 'btn btn-default');
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
					$control->getSeparatorPrototype()->setName('div')->addClass($control->getControlPrototype()->type);
					break;
			}
		}

		return parent::render($form, $mode);
	}

}
