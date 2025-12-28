<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Wordcha\DataSource;

class Pair
{

	private string $question;

	private string $answer;

	public function __construct(string $question, string $answer)
	{
		$this->question = $question;
		$this->answer = $answer;
	}

	public function getQuestion(): string
	{
		return $this->question;
	}

	public function getAnswer(): string
	{
		return $this->answer;
	}

}
