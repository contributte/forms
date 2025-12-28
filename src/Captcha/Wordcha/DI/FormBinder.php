<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Wordcha\DI;

use Contributte\Forms\Captcha\Wordcha\Factory;
use Contributte\Forms\Captcha\Wordcha\Form\WordchaContainer;
use Nette\Forms\Container;

final class FormBinder
{

	public static function bind(Factory $factory): void
	{
		Container::extensionMethod(
			'addWordcha',
			fn (Container $container, string $name = 'captcha', string $label = 'Captcha'): WordchaContainer => $container[$name] = new WordchaContainer($factory) // @phpcs:ignore
		);
	}

}
