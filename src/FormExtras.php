<?php declare(strict_types = 1);

namespace Contributte\Forms;

use Nette\Forms\Form;

final class FormExtras
{

	private const DEFAULT_CONFIRM_MESSAGE = 'You have unsaved changes. Are you sure you want to leave?';

	public static function applyConfirm(Form $form, ?string $message = null): void
	{
		$el = $form->getElementPrototype();
		$el->data('confirm-unsaved', true);
		$el->data('confirm-message', $message ?? self::DEFAULT_CONFIRM_MESSAGE);
	}

}
