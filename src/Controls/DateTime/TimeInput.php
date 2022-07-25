<?php declare(strict_types = 1);

namespace Contributte\Forms\Controls\DateTime;

use DateTimeImmutable;
use DateTimeInterface;
use Nette\Forms\Container;
use Nette\Utils\Html;

class TimeInput extends AbstractDateTimeInput
{

	protected $defaultHumanFormat = 'H:i';

	protected $htmlFormat = 'H:i';

	protected $htmlClass = 'time-input';

	protected function normalizeDateTime(DateTimeInterface $datetime): DateTimeImmutable
	{
		$datetime = parent::normalizeDateTime($datetime);
		$datetime = $datetime->setDate(1970, 1, 1);
		return $datetime;
	}

	public function getControl(): Html
	{
		$control = parent::getControl();
		if (!$this->hasCustomFormat()) {
			$control->setAttribute('type', 'time');
		}

		return $control;
	}

	public static function register(?string $defaultFormat = null): void
	{
		Container::extensionMethod(
			'addTime',
			function ($container, $name, $label = null, $format = null) use ($defaultFormat): TimeInput {
				return $container[$name] = new TimeInput($label, $format ?? $defaultFormat);
			}
		);
	}

}
