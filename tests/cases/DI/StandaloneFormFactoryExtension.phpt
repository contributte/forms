<?php declare(strict_types = 1);

/**
 * Test: DI\StandaloneFormFactoryExtension
 */

use Contributte\Forms\DI\StandaloneFormFactoryExtension;
use Contributte\Forms\IStandaloneFormFactory;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nette\Forms\Form;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

test(function (): void {
	$loader = new ContainerLoader(TEMP_DIR, true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('formFactory', new StandaloneFormFactoryExtension());
	}, 1);

	/** @var Container $container */
	$container = new $class();

	Assert::type(IStandaloneFormFactory::class, $container->getByType(IStandaloneFormFactory::class));
	Assert::type(Form::class, $container->getByType(IStandaloneFormFactory::class)->create());
});
