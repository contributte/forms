<?php declare(strict_types = 1);

namespace Contributte\Forms\Wordcha\Validator;

use Contributte\Forms\Wordcha\Generator\Generator;

class WordchaValidator implements Validator
{

	private Generator $generator;

	public function __construct(Generator $generator)
	{
		$this->generator = $generator;
	}

	public function validate(string $answer, string $hash): bool
	{
		$answerHash = $this->generator->hash($answer);

		return $hash === $answerHash;
	}

}
