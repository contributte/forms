<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Seznam\DI;

use Contributte\Forms\Captcha\Seznam\Factory;
use Contributte\Forms\Captcha\Seznam\Form\SeznamCaptchaContainer;
use Nette\Forms\Container;

final class FormBinder
{

	public static function bind(Factory $factory): void
	{
		Container::extensionMethod(
			'addSeznamCaptcha',
			fn (Container $container, string $name = 'captcha'): SeznamCaptchaContainer => $container[$name] = new SeznamCaptchaContainer($factory) // @phpcs:ignore
		);
	}

}
