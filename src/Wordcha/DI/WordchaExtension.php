<?php declare(strict_types = 1);

namespace Contributte\Forms\Wordcha\DI;

use Contributte\Forms\Wordcha\DataSource\DataSource;
use Contributte\Forms\Wordcha\DataSource\NumericDataSource;
use Contributte\Forms\Wordcha\DataSource\QuestionDataSource;
use Contributte\Forms\Wordcha\Factory;
use Contributte\Forms\Wordcha\WordchaFactory;
use Contributte\Forms\Wordcha\WordchaUniqueFactory;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Literal;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\Utils\AssertionException;
use stdClass;

/**
 * @property-read stdClass $config
 */
final class WordchaExtension extends CompilerExtension
{

	public const DATASOURCE_NUMERIC = 'numeric';
	public const DATASOURCE_QUESTIONS = 'questions';

	public const DATASOURCES = [
		self::DATASOURCE_NUMERIC,
		self::DATASOURCE_QUESTIONS,
	];

	private bool $debugMode;

	public function __construct(bool $debugMode = false)
	{
		$this->debugMode = $debugMode;
	}

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'auto' => Expect::bool()->default(true),
			'datasource' => Expect::anyOf(...self::DATASOURCES)->default(self::DATASOURCE_NUMERIC),
			'questions' => Expect::listOf('string'),
		]);
	}

	/**
	 * Register services
	 *
	 * @throws AssertionException
	 */
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		// Add datasource
		$dataSource = $builder->addDefinition($this->prefix('dataSource'))
			->setType(DataSource::class);

		if ($this->config->datasource === self::DATASOURCE_NUMERIC) {
			$dataSource->setFactory(NumericDataSource::class);
		} elseif ($this->config->datasource === self::DATASOURCE_QUESTIONS) {
			$dataSource->setFactory(QuestionDataSource::class, [$this->config->questions]);
		}

		// Add factory
		$factory = $builder->addDefinition($this->prefix('factory'))
			->setType(Factory::class);
		if ($this->debugMode) {
			$factory->setFactory(WordchaFactory::class, [$dataSource]);
		} else {
			$uniqueKey = sha1(random_bytes(10) . microtime(true));
			$factory->setFactory(WordchaUniqueFactory::class, [$dataSource, $uniqueKey]);
		}
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
