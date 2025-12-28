<?php declare(strict_types = 1);

namespace Contributte\Forms\Wordcha\Generator;

class Security
{

	private string $question;

	private string $hash;

	public function __construct(string $question, string $hash)
	{
		$this->question = $question;
		$this->hash = $hash;
	}

	public function getQuestion(): string
	{
		return $this->question;
	}

	public function getHash(): string
	{
		return $this->hash;
	}

}
