<?php declare(strict_types = 1);

namespace Contributte\Forms\Controls\DateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Nette\Forms\Container;
use Nette\Utils\Html;

class DateInput extends AbstractDateTimeInput
{

	protected $defaultHumanFormat = 'Y-m-d';

	protected $htmlFormat = 'Y-m-d';

	protected $htmlClass = 'date-input';

	public static function register(?string $defaultFormat = null): void
	{
		Container::extensionMethod(
			'addDate',
			fn ($container, $name, $label = null, $format = null, ?DateTimeZone $inputTimezone = null): DateInput => $container[$name] = new DateInput($label, $format ?? $defaultFormat)
		);
	}

	public function getControl(): Html
	{
		$control = parent::getControl();
		if (!$this->hasCustomFormat()) {
			$control->setAttribute('type', 'date');
		}

		return $control;
	}

	protected function normalizeDateTime(DateTimeInterface $datetime): DateTimeImmutable
	{
		$datetime = parent::normalizeDateTime($datetime);
		$datetime = $datetime->setTime(0, 0, 0, 0);

		return $datetime;
	}

}
