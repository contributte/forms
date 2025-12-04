<?php declare(strict_types = 1);

namespace Contributte\Forms\Rendering;

use Nette\Forms\Form;
use Nette\Forms\IFormRenderer;

/**
 * Decorator renderer that adds extra JS functionality.
 * Wraps any existing renderer and injects JS code when data attributes are present.
 */
class FormExtrasRenderer implements IFormRenderer
{

	private IFormRenderer $inner;

	public function __construct(IFormRenderer $inner)
	{
		$this->inner = $inner;
	}

	public function render(Form $form, ?string $mode = null): string
	{
		$html = $this->inner->render($form, $mode);

		// Only inject JS when rendering full form or 'end'
		if ($mode === null || $mode === 'end') {
			$html .= $this->renderExtras($form);
		}

		return $html;
	}

	private function renderExtras(Form $form): string
	{
		$el = $form->getElementPrototype();
		$extras = '';

		// Unsaved changes confirmation
		if ($el->data('confirm-unsaved')) {
			$extras .= $this->renderConfirmScript($form);
		}

		return $extras;
	}

	private function renderConfirmScript(Form $form): string
	{
		$formId = $form->getElementPrototype()->getId();
		$message = $form->getElementPrototype()->data('confirm-message');

		// Use form ID if available, otherwise use data attribute selector
		if ($formId) {
			$selector = '#' . $formId;
		} else {
			$selector = 'form[data-confirm-unsaved]';
		}

		return <<<JS

<script>
(function() {
	var form = document.querySelector('{$selector}');
	if (!form || form._confirmUnsaved) return;
	form._confirmUnsaved = true;

	var initial = new FormData(form);
	var initialState = JSON.stringify([...initial.entries()]);
	var isDirty = false;
	var isSubmitting = false;

	form.addEventListener('input', function() { isDirty = true; });
	form.addEventListener('change', function() { isDirty = true; });
	form.addEventListener('submit', function() { isSubmitting = true; });

	window.addEventListener('beforeunload', function(e) {
		if (isSubmitting) return;
		if (!isDirty) return;

		var current = new FormData(form);
		var currentState = JSON.stringify([...current.entries()]);
		if (currentState === initialState) return;

		e.preventDefault();
		e.returnValue = '{$message}';
		return '{$message}';
	});
})();
</script>
JS;
	}

}
