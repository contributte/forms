<?php declare(strict_types = 1);

namespace Contributte\Forms\Rendering;

use Nette\Forms\Controls;
use Nette\Forms\Form;
use Nette\Forms\Rendering\DefaultFormRenderer;

class Bootstrap5InlineRenderer extends DefaultFormRenderer
{

	public array $wrappers = [
	'form' => [
	  'container' => '',
	],
	'error' => [
	  'container' => 'div class="alert alert-danger"',
	  'item' => 'p',
	],
	'group' => [
	  'container' => '',
	  'label' => 'legend',
	  'description' => 'p',
	],
	'controls' => [
	  'container' => '',
	],
	'pair' => [
	  '.required' => 'required',
	  '.optional' => null,
	  '.odd' => null,
	],
	'control' => [
	  'container' => null,
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
	public function render(Form $form, ?string $mode = null): string
	{
		$form->getElementPrototype()->addClass('d-inline-flex gap-2');

		$onlyButton = Helpers::onlyOneButton($form);

		foreach ($form->getControls() as $control) {
			if ($control instanceof Controls\BaseControl && $control->hasErrors()) {
				$control->getControlPrototype()->addClass('is-invalid');
			}

			if ($control instanceof Controls\BaseControl) {
				$control->getLabelPrototype()->addClass('col-form-label');
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
					$control->getControlPrototype()->addClass('form-control me-3');
					break;

				case $control instanceof Controls\Checkbox:
				case $control instanceof Controls\CheckboxList:
				case $control instanceof Controls\RadioList:
					// the .invalid-feedback expects .is-invalid on the same level to be displayed
					if ($control->hasErrors()) {
						$control->getContainerPrototype()->setName('div')->addClass('is-invalid');
					}

					$control->getContainerPrototype()->setName('div')->addClass('form-check form-check-inline');
					$control->getControlPrototype()->addClass('form-check-input');
					$control->getLabelPrototype()->addClass('form-check-label');
					break;
			}
		}

		return parent::render($form, $mode);
	}

}
