<?php declare(strict_types = 1);

/**
 * Test: DI\ApplicationFormFactoryExtension
 */

use Contributte\Forms\DI\ApplicationFormFactoryExtension;
use Contributte\Forms\IApplicationFormFactory;
use Nette\Application\UI\Form;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

test(function (): void {
	$loader = new ContainerLoader(TEMP_DIR, true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('formFactory', new ApplicationFormFactoryExtension());
	}, 1);

	/** @var Container $container */
	$container = new $class();

	Assert::type(IApplicationFormFactory::class, $container->getByType(IApplicationFormFactory::class));
	Assert::type(Form::class, $container->getByType(IApplicationFormFactory::class)->create());
});
