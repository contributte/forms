<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Wordcha\Generator;

use Contributte\Forms\Captcha\Wordcha\DataSource\DataSource;

class WordchaGenerator implements Generator
{

	private DataSource $dataSource;

	private ?string $uniqueKey = null;

	public function __construct(DataSource $dataSource)
	{
		$this->dataSource = $dataSource;
	}

	public function setUniqueKey(string $uniqueKey): void
	{
		$this->uniqueKey = $uniqueKey;
	}

	public function generate(): Security
	{
		$pair = $this->dataSource->get();
		$hash = $this->hash($pair->getAnswer());
		$question = $pair->getQuestion();

		return new Security($question, $hash);
	}

	public function hash(string $answer): string
	{
		if ($this->uniqueKey !== null) {
			$answer .= $this->uniqueKey;
		}

		return sha1(strtolower($answer));
	}

}
