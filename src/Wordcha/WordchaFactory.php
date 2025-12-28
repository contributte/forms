<?php declare(strict_types = 1);

namespace Contributte\Forms\Wordcha;

use Contributte\Forms\Wordcha\DataSource\DataSource;
use Contributte\Forms\Wordcha\Generator\Generator;
use Contributte\Forms\Wordcha\Generator\WordchaGenerator;
use Contributte\Forms\Wordcha\Validator\Validator;
use Contributte\Forms\Wordcha\Validator\WordchaValidator;

class WordchaFactory implements Factory
{

	private DataSource $dataSource;

	public function __construct(DataSource $dataSource)
	{
		$this->dataSource = $dataSource;
	}

	/**
	 * @return WordchaValidator
	 */
	public function createValidator(): Validator
	{
		return new WordchaValidator($this->createGenerator());
	}

	/**
	 * @return WordchaGenerator
	 */
	public function createGenerator(): Generator
	{
		return new WordchaGenerator($this->dataSource);
	}

}
