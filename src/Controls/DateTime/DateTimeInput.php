<?php declare(strict_types = 1);

namespace Contributte\Forms\Controls\DateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Nette\Forms\Container;
use Nette\Utils\Html;

class DateTimeInput extends AbstractDateTimeInput
{

	protected string $defaultHumanFormat = 'Y-m-d H:i';

	protected string $htmlFormat = 'Y-m-d\TH:i';

	protected string $htmlClass = 'datetime-input';

	protected ?DateTimeZone $inputTimezone = null;

	/**
	 * @param string|string[] $format
	 */
	public function __construct(?string $label = null, string|array|null $format = null, ?DateTimeZone $inputTimezone = null)
	{
		parent::__construct($label, $format);

		$this->parser->setDefaultTimezone($inputTimezone);
		$this->inputTimezone = $inputTimezone;
	}

	public static function register(?string $defaultFormat = null): void
	{
		Container::extensionMethod(
			'addDateTime',
			fn (Container $container, string $name, ?string $label = null, ?string $format = null, ?DateTimeZone $inputTimezone = null): DateTimeInput => $container->addComponent($name, new DateTimeInput($label, $format ?? $defaultFormat, $inputTimezone))
		);
	}

	public function getValueInTz(?DateTimeZone $timezone = null): mixed
	{
		$value = $this->getValueAsDateTimeImmutable();
		if ($value === null) {
			return null;
		}

		if ($timezone === null) {
			$timezone = new DateTimeZone(date_default_timezone_get());
		}

		$value = $value->setTimezone($timezone);

		return $this->transformValue($value);
	}

	/**
	 * @template T
	 * @phpstan-param class-string<T>|callable(DateTimeInterface): ?T $type
	 * @phpstan-return ?T
	 */
	public function getValueInTzAs(string|callable $type, ?DateTimeZone $timezone = null): mixed
	{
		$value = $this->getValueAsDateTimeImmutable();
		if ($value === null) {
			return null;
		}

		if ($timezone === null) {
			$timezone = new DateTimeZone(date_default_timezone_get());
		}

		$value = $value->setTimezone($timezone);

		if ($type !== DateTimeImmutable::class) {
			$value = $this->createValueTransformation($type)($value);
		}

		/** @phpstan-var ?T */
		return $value;
	}

	public function getControl(): Html
	{
		$control = parent::getControl();
		if (!$this->hasCustomFormat()) {
			$control->setAttribute('type', 'datetime-local');
		}

		if ($this->inputTimezone !== null) {
			$control->setAttribute('data-timezone', $this->inputTimezone->getName());
		}

		return $control;
	}

	protected function normalizeDateTime(DateTimeInterface $datetime): DateTimeImmutable
	{
		$datetime = parent::normalizeDateTime($datetime);
		if ($this->inputTimezone !== null) {
			$datetime = $datetime->setTimezone($this->inputTimezone);
		}

		return $datetime;
	}

}
