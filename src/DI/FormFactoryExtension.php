<?php declare(strict_types=1);

namespace Contributte\Forms\DI;

use Contributte\Forms\FormFactory;
use Nette\DI\CompilerExtension;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
class FormFactoryExtension extends CompilerExtension
{

	/**
	 * Register services
	 */
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('factory'))
			->setFactory(FormFactory::class);
	}

}
