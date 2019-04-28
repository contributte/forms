<?php declare(strict_types = 1);

namespace Contributte\Forms\DI;

use Contributte\Forms\Exception\LogicalException;
use Contributte\Forms\IApplicationFormFactory;
use Nette\Application\UI\Form;
use Nette\DI\CompilerExtension;

class ApplicationFormFactoryExtension extends CompilerExtension
{

	/**
	 * Register services
	 */
	public function loadConfiguration(): void
	{
		if (!class_exists(Form::class)) {
			throw new LogicalException(sprintf('Install nette/application to use %s factory', Form::class));
		}

		$builder = $this->getContainerBuilder();

		$builder->addFactoryDefinition($this->prefix('factory'))
			->setImplement(IApplicationFormFactory::class)
			->getResultDefinition();
	}

}
