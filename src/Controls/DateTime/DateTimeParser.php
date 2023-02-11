<?php declare(strict_types = 1);

namespace Contributte\Forms\Controls\DateTime;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Nette\SmartObject;
use function is_string;
use function strlen;

class DateTimeParser
{

	use SmartObject;

	/** @var string[] */
	private $formats = [];

	/** @var ?DateTimeZone */
	private $defaultTimeZone = null;

	/** @var string[] */
	private $defaultFormats = [
		DateTimeInterface::ATOM,
		DateTimeInterface::ISO8601,
		DateTimeInterface::W3C,
		DateTimeInterface::COOKIE,
		DateTimeInterface::RSS,
		'Y-m-d H:i:s.u',
		'Y-m-d H:i:s',
		'Y-m-d H:i',
		'Y-m-d\TH:i:s',
		'Y-m-d\TH:i',
		'Y-m-d',
		'H:i:s',
		'H:i',
	];

	/**
	 * @return string[]
	 */
	public function getFormats(): array
	{
		return $this->formats;
	}

	/**
	 * @param string[] $formats
	 */
	public function setFormats(array $formats): void
	{
		$this->formats = $formats;
	}

	public function addFormat(string $format): void
	{
		$this->formats[] = $format;
	}

	public function setDefaultTimezone(?DateTimeZone $timezone): void
	{
		$this->defaultTimeZone = $timezone;
	}

	/**
	 * @param mixed $value
	 */
	public function parse($value): ?DateTime
	{
		if (!is_string($value) || strlen(trim($value)) === 0) {
			return null;
		}

		$formats = array_merge($this->formats, $this->defaultFormats);

		foreach ($formats as $format) {
			// ending | added to prevent setting unparsed DateTime fields to current time instead of zero
			$date = DateTime::createFromFormat($format . '|', $value, $this->defaultTimeZone);
			if ($date !== false) {
				return $date;
			}
		}

		return null;
	}

}
