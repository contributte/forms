<?php declare(strict_types = 1);

namespace Contributte\Forms\Wordcha;

use Contributte\Forms\Wordcha\DataSource\DataSource;
use Contributte\Forms\Wordcha\Generator\Generator;
use Contributte\Forms\Wordcha\Generator\WordchaGenerator;

class WordchaUniqueFactory extends WordchaFactory
{

	private string $uniqueKey;

	public function __construct(DataSource $dataSource, string $uniqueKey)
	{
		parent::__construct($dataSource);

		$this->uniqueKey = $uniqueKey;
	}

	/**
	 * @return WordchaGenerator
	 */
	public function createGenerator(): Generator
	{
		$generator = parent::createGenerator();
		$generator->setUniqueKey($this->uniqueKey);

		return $generator;
	}

}
