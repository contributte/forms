<?php declare(strict_types = 1);

namespace Contributte\Forms\Rendering;

use Nette\Forms\Controls;
use Nette\Forms\Form;
use Nette\Forms\IControl;
use Nette\Utils\Html;

class Bootstrap5HorizontalRenderer extends AbstractBootstrapHorizontalRenderer
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
	  'container' => 'div class="mb-3 row"',
	  '.required' => 'required',
	  '.optional' => null,
	  '.odd' => null,
	],
	'control' => [
	  'container' => 'div class="col-%colsControl%"',
	  '.odd' => null,
	  'description' => 'span class="form-text"',
	  'requiredsuffix' => '',
	  'errorcontainer' => 'div class="invalid-feedback"',
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
   */
	public function render(Form $form, $mode = null): string
	{
		$form->getElementPrototype()->setNovalidate(true);

		$onlyButton = Helpers::onlyOneButton($form);

		foreach ($form->getControls() as $control) {
			if ($control instanceof Controls\BaseControl && $control->hasErrors()) {
				$control->getControlPrototype()->addClass('is-invalid');
			}

			if ($control instanceof Controls\BaseControl
			&& !($control instanceof Controls\Checkbox)
			&& !($control instanceof Controls\CheckboxList)
			&& !($control instanceof Controls\RadioList)) {
				$control->getLabelPrototype()->addClass($this->replacePlaceholders('col-form-label col-%colsLabel%'));
			}

			// the .invalid-feedback expects .is-invalid on the same level to be displayed
			if ($control->hasErrors()
			&& ($control instanceof Controls\Checkbox
			|| $control instanceof Controls\CheckboxList
			|| $control instanceof Controls\RadioList)) {
				$control->getSeparatorPrototype()->setName('div')->addClass('is-invalid');
			}

			switch (true) {
				case $control instanceof Controls\Button:
					if (!Helpers::htmlClassContains($control->getControlPrototype(), 'btn')) {
						$control->getControlPrototype()->addClass($onlyButton ? 'btn btn-primary' : 'btn btn-secondary');
					}

					break;

				case $control instanceof Controls\TextBase:
				case $control instanceof Controls\SelectBox:
				case $control instanceof Controls\MultiSelectBox:
					$control->getControlPrototype()->addClass('form-control');
					break;

				case $control instanceof Controls\Checkbox:
					$control->getControlPrototype()->addClass('form-check-input');
					$control->getLabelPrototype()->addClass('form-check-label');
					$control->getLabelPrototype()->addWrapper('div')->addClass('form-check');
					break;
				case $control instanceof Controls\CheckboxList:
				case $control instanceof Controls\RadioList:
					$control->getContainerPrototype()
						->setName('div')
						->addAttributes(['class' => 'pt-2']);
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
			return Html::el('div')
				->addClass($this->replacePlaceholders('col-form-label col-%colsLabel%'))
				->addHtml($label);
		}

		return $label;
	}

}
