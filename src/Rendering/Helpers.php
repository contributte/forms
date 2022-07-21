<?php declare(strict_types = 1);

namespace Contributte\Forms\Rendering;

use Nette\Forms\Controls\Button;
use Nette\Forms\Form;
use Nette\Utils\Html;

class Helpers
{

	public static function htmlClassContains(Html $html, string $contains): bool
	{
		/** @var string|string[]|null $class */
		$class = $html->getAttribute('class');
		if (is_array($class)) {
			$class = implode(' ', array_keys($class));
		}

		return $class !== null && mb_strpos($class, $contains) !== false;
	}

	public static function onlyOneButton(Form $form): bool
	{
		$count = 0;
		foreach ($form->getControls() as $control) {
			if ($control instanceof Button) {
				$count++;
			}
		}

		return $count === 1;
	}

}
