<?php declare(strict_types = 1);

/**
 * Test: DI\FormFactoryExtension
 */

use Contributte\Forms\DI\FormFactoryExtension;
use Contributte\Forms\FormFactory;
use Contributte\Forms\IFormFactory;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nette\Forms\Form;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

test(function (): void {
	$loader = new ContainerLoader(TEMP_DIR, true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('formFactory', new FormFactoryExtension());
	}, 1);

	/** @var Container $container */
	$container = new $class();

	Assert::type(FormFactory::class, $container->getByType(IFormFactory::class));
	Assert::type(Form::class, $container->getByType(IFormFactory::class)->create());
});
