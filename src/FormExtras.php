<?php declare(strict_types = 1);

namespace Contributte\Forms;

use Nette\Forms\Form;

/**
 * Static helpers for applying extra form behaviors via data attributes.
 */
final class FormExtras
{

	private const DEFAULT_CONFIRM_MESSAGE = 'You have unsaved changes. Are you sure you want to leave?';

	/**
	 * Apply unsaved changes confirmation to form.
	 * Adds data attributes that FormExtrasRenderer will use to inject JS.
	 */
	public static function applyConfirm(Form $form, ?string $message = null): void
	{
		$el = $form->getElementPrototype();
		$el->data('confirm-unsaved', true);
		$el->data('confirm-message', $message ?? self::DEFAULT_CONFIRM_MESSAGE);
	}

}
