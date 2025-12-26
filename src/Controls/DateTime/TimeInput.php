<?php declare(strict_types = 1);

namespace Contributte\Forms\Controls\DateTime;

use DateTimeImmutable;
use DateTimeInterface;
use Nette\Forms\Container;
use Nette\Utils\Html;

class TimeInput extends AbstractDateTimeInput
{

	protected string $defaultHumanFormat = 'H:i';

	protected string $htmlFormat = 'H:i';

	protected string $htmlClass = 'time-input';

	public static function register(?string $defaultFormat = null): void
	{
		Container::extensionMethod(
			'addTime',
			fn ($container, $name, $label = null, $format = null): TimeInput => $container[$name] = new TimeInput($label, $format ?? $defaultFormat)
		);
	}

	public function getControl(): Html
	{
		$control = parent::getControl();
		if (!$this->hasCustomFormat()) {
			$control->setAttribute('type', 'time');
		}

		return $control;
	}

	protected function normalizeDateTime(DateTimeInterface $datetime): DateTimeImmutable
	{
		$datetime = parent::normalizeDateTime($datetime);
		$datetime = $datetime->setDate(1970, 1, 1);

		return $datetime;
	}

}
