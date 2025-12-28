<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Seznam\DI;

use Contributte\Forms\Captcha\Seznam\Backend\HttpClient;
use Contributte\Forms\Captcha\Seznam\Factory;
use Contributte\Forms\Captcha\Seznam\SeznamFactory;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Literal;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;

/**
 * @property-read stdClass $config
 */
final class SeznamCaptchaExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'auto' => Expect::bool()->default(true),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$client = $builder->addDefinition($this->prefix('client'))
			->setFactory(HttpClient::class, ['captcha.seznam.cz', 443]);

		$builder->addDefinition($this->prefix('factory'))
			->setType(Factory::class)
			->setFactory(SeznamFactory::class, [$client]);
	}

	public function afterCompile(ClassType $class): void
	{
		if ($this->config->auto === true) {
			$method = $class->getMethod('initialize');
			$method->addBody(
				'?::bind($this->getService(?));',
				[
					new Literal(FormBinder::class),
					$this->prefix('factory'),
				]
			);
		}
	}

}
