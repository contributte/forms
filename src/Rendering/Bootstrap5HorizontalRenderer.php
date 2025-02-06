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
	  'container' => 'div class="col col-%colsControl%"',
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
   */
	public function render(Form $form, $mode = null): string
	{
		$form->getElementPrototype()->setNovalidate(true);

		$onlyButton = Helpers::onlyOneButton($form);

		foreach ($form->getControls() as $control) {
			if ($control instanceof Controls\BaseControl
			&& !($control instanceof Controls\Checkbox)
			&& !($control instanceof Controls\CheckboxList)
			&& !($control instanceof Controls\RadioList)) {
				$control->getLabelPrototype()->addClass($this->replacePlaceholders('col-form-label col col-%colsLabel%'));
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
			$label->addHtml($this->replacePlaceholders('<div class="col col-%colsLabel%"></div>'));
		}

		return $label;
	}

}
