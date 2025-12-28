<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Wordcha\Validator;

use Contributte\Forms\Captcha\Wordcha\Generator\Generator;

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
