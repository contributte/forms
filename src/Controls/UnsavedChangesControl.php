<?php declare(strict_types = 1);

namespace Contributte\Forms\Controls;

use Nette\Forms\Form;

/**
 * Trait for adding unsaved changes warning to forms
 *
 * Usage:
 * $form = new Form();
 * UnsavedChangesControl::enable($form);
 * // or with custom message:
 * UnsavedChangesControl::enable($form, 'Custom warning message');
 */
final class UnsavedChangesControl
{

	/**
	 * Enable unsaved changes warning on a form
	 *
	 * @param Form $form Form to enable warning on
	 * @param string|bool $message Custom warning message or true for default message
	 * @param bool $debug Enable debug mode
	 */
	public static function enable(Form $form, $message = true, bool $debug = false): void
	{
		$prototype = $form->getElementPrototype();

		// Add data attribute to enable auto-initialization
		if ($message === true) {
			$prototype->setAttribute('data-unsaved-warning', 'true');
		} else {
			$prototype->setAttribute('data-unsaved-warning', $message);
		}

		if ($debug) {
			$prototype->setAttribute('data-unsaved-debug', 'true');
		}
	}

	/**
	 * Enable unsaved changes warning with custom options
	 *
	 * @param Form $form Form to enable warning on
	 * @param array<string, mixed> $options Custom options for the tracker
	 */
	public static function enableWithOptions(Form $form, array $options = []): void
	{
		$prototype = $form->getElementPrototype();

		// Add data attribute for auto-initialization
		$prototype->setAttribute('data-unsaved-warning', 'true');

		// Add custom options as data attributes
		if (isset($options['message'])) {
			$prototype->setAttribute('data-unsaved-warning', $options['message']);
		}

		if (isset($options['debug']) && $options['debug']) {
			$prototype->setAttribute('data-unsaved-debug', 'true');
		}

		// Store additional options in data-unsaved-options as JSON
		$additionalOptions = array_diff_key($options, ['message' => null, 'debug' => null]);
		if (!empty($additionalOptions)) {
			$prototype->setAttribute('data-unsaved-options', json_encode($additionalOptions));
		}
	}

	/**
	 * Disable unsaved changes warning on a form
	 *
	 * @param Form $form Form to disable warning on
	 */
	public static function disable(Form $form): void
	{
		$prototype = $form->getElementPrototype();
		$prototype->removeAttribute('data-unsaved-warning');
		$prototype->removeAttribute('data-unsaved-debug');
		$prototype->removeAttribute('data-unsaved-options');
	}

}
