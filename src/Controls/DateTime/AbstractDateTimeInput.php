<?php declare(strict_types = 1);

namespace Contributte\Forms\Controls\DateTime;

use Contributte\Forms\Exception\InvalidArgumentException;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Form;
use Nette\Utils\Arrays;
use Nette\Utils\Html;
use function class_exists;
use function count;
use function in_array;
use function is_callable;
use function is_string;

abstract class AbstractDateTimeInput extends BaseControl
{

	/** @var DateTimeParser */
	protected $parser;

	/** @var callable(DateTimeImmutable ): mixed|null */
	protected $valueTransformation = null;

	/** @var ?DateTimeInterface */
	protected $min = null;

	/** @var ?DateTimeInterface */
	protected $max = null;

	/** @var string */
	protected $defaultHumanFormat = 'Y-m-d H:i';

	/** @var string */
	protected $htmlFormat = 'Y-m-d\TH:i';

	/** @var string */
	protected $htmlClass = '';

	/** @var string|object */
	protected $invalidValueMessage = 'Invalid format';

	/**
	 * @param string|string[] $format
	 */
	public function __construct(?string $label = null, $format = null)
	{
		parent::__construct($label);
		$this->parser = new DateTimeParser();
		if ($format !== null) {
			$this->setFormat($format);
		}
	}

	/**
	 * @param string|string[] $format
	 * @return static
	 */
	public function setFormat($format)
	{
		if (is_string($format)) {
			$format = [$format];
		}

		if (!is_array($format) || /** @phpstan-ignore-line */
			Arrays::some($format, function ($item) {
				return !is_string($item);
			})
		) {
			throw new InvalidArgumentException('Format must be either string or array of strings.');
		}

		$this->parser->setFormats($format);
		return $this;
	}

	/**
	 * @param string|callable(DateTimeInterface): mixed $type
	 * @phpstan-param class-string|callable(DateTimeInterface): mixed $type
	 * @return static
	 */
	public function setValueType($type)
	{
		$this->valueTransformation = $this->createValueTransformation($type);
		return $this;
	}

	/**
	 * @template T
	 * @param string|callable $type
	 * @phpstan-param class-string<T>|callable(DateTimeInterface): ?T $type
	 * @return callable(DateTimeInterface): ?T
	 */
	protected function createValueTransformation($type)
	{
		if (is_callable($type)) {
			return $type;
		} elseif (is_string($type)) { /** @phpstan-ignore-line */
			if (!class_exists($type) || class_implements($type) === false || !in_array('DateTimeInterface', class_implements($type), true)) {
				throw new InvalidArgumentException('Value type must be existing class implementing \DateTimeInterface');
			}
		} else {
			throw new InvalidArgumentException('Value type can be only string with class name or or callback.');
		}

		if ($type === 'Nette\Utils\DateTime' || (class_parents($type) !== false && in_array('Nette\Utils\DateTime', class_parents($type), true))) {
			return [$type, 'from']; /** @phpstan-ignore-line */
		} else {
			/** @phpstan-var callable(DateTimeInterface): ?T $transformation */
			$transformation = function (DateTimeInterface $value) use ($type) {
				/** @phpstan-ignore-next-line */
				return call_user_func([$type, 'createFromFormat'], 'Y-m-d\TH:i:s.ue|', $value->format('Y-m-d\TH:i:s.ue'));
			};
			return $transformation;
		}
	}

	/**
	 * @return mixed
	 */
	protected function transformValue(?DateTimeImmutable $value)
	{
		if ($value === null) {
			return null;
		}

		if (is_callable($this->valueTransformation)) {
			$value = call_user_func($this->valueTransformation, $value);
		}

		return $value;
	}

	/**
	 * @return static
	 */
	public function addFormat(string $format)
	{
		$this->parser->addFormat($format);
		return $this;
	}

	public function getFormat(): string
	{
		return $this->parser->getFormats()[0] ?? $this->htmlFormat;
	}

	public function getHumanFormat(): string
	{
		return $this->parser->getFormats()[0] ?? $this->defaultHumanFormat;
	}

	public function hasCustomFormat(): bool
	{
		return count($this->parser->getFormats()) > 0;
	}

	protected function normalizeDateTime(DateTimeInterface $datetime): DateTimeImmutable
	{
		if ($datetime instanceof DateTime) {
			$datetime = $this->createValueTransformation(DateTimeImmutable::class)($datetime);
			if ($datetime === null) {
				throw new InvalidArgumentException('Cannot normalize value');
			}
		}

		return $datetime;
	}

	/**
	 * @return static
	 */
	public function setValue($value)
	{
		if ($value instanceof DateTimeInterface) {
			$value = $this->normalizeDateTime($value)->format($this->getFormat());
		} elseif (is_int($value)) { // timestamp
			$value = (new DateTimeImmutable())->setTimestamp($value)->format($this->getFormat());
		}

		parent::setValue($value);
		return $this;
	}

	/**
	 * @param mixed $value
	 */
	public function setDefaultValue($value)
	{
		if (is_string($value)) {
			$value = $this->parser->parse($value) ?? $value;
		}

		return parent::setDefaultValue($value);
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->transformValue($this->getValueAsDateTimeImmutable());
	}

	/**
	 * @return mixed
	 */
	public function getRawValue()
	{
		return parent::getValue();
	}

	/**
	 * @return ?DateTimeImmutable
	 */
	protected function getValueAsDateTimeImmutable()
	{
		$date = $this->parser->parse($this->value);
		if ($date === null) {
			$this->addError($this->invalidValueMessage);
			return null;
		}

		return $date;
	}

	/**
	 * @template T
	 * @param string|callable $type
	 * @phpstan-param class-string<T>|callable(DateTimeInterface): ?T $type
	 * @return mixed
	 * @phpstan-return ?T
	 */
	public function getValueAs($type)
	{
		$value = $this->getValueAsDateTimeImmutable();
		if ($value === null) {
			return null;
		}

		if ($type !== DateTimeImmutable::class) {
			$value = $this->createValueTransformation($type)($value);
		}

		/** @phpstan-var ?T */
		return $value;
	}

	/**
	 * @param string|object $message
	 * @return static
	 */
	public function setInvalidValueMessage($message)
	{
		$this->invalidValueMessage = $message;
		return $this;
	}

	/**
	 * @param callable|string $validator
	 * @param string|object $message
	 * @param mixed $arg
	 * @return static
	 */
	public function addRule($validator, $message = null, $arg = null)
	{
		switch ($validator) {
			case Form::MIN:
				if (!$arg instanceof DateTimeInterface) {
					throw new InvalidArgumentException('Rule parameter expected to be \DateTimeInterface');
				}

				$this->min = $this->normalizeDateTime($arg);
				$arg = $this->min->format($this->getHumanFormat());
				$validator = static::class . '::validateMin';
				break;

			case Form::MAX:
				if (!$arg instanceof DateTimeInterface) {
					throw new InvalidArgumentException('Rule parameter expected to be \DateTimeInterface');
				}

				$this->max = $this->normalizeDateTime($arg);
				$arg = $this->max->format($this->getHumanFormat());
				$validator = static::class . '::validateMax';
				break;

			case Form::RANGE:
				if (!is_array($arg) || !$arg[0] instanceof DateTimeInterface || !$arg[1] instanceof DateTimeInterface) {
					throw new InvalidArgumentException('Rule parameter expected to be 2 item array [min, max] of \DateTimeInterface');
				}

				$this->min = $this->normalizeDateTime($arg[0]);
				$this->max = $this->normalizeDateTime($arg[1]);

				$arg[0] = $this->min->format($this->getHumanFormat());
				$arg[1] = $this->max->format($this->getHumanFormat());

				$validator = static::class . '::validateRange';
				break;

			default:
				break;
		}

		return parent::addRule($validator, $message, $arg);
	}

	public function getMin(): ?DateTimeInterface
	{
		return $this->min;
	}


	public function getMax(): ?DateTimeInterface
	{
		return $this->max;
	}

	/**
	 * @return array{min: ?DateTimeInterface, max: ?DateTimeInterface}
	 */
	public function getRange(): array
	{
		return ['min' => $this->min, 'max' => $this->max];
	}

	public function getControl(): Html
	{
		/** @var Html $control */
		$control = parent::getControl();
		$control->setAttribute('type', 'text');
		if (isset($control->placeholder)) {
			$control->placeholder = $this->translate($control->placeholder);
		}

		if ($this->htmlClass !== '') {
			$control->appendAttribute('class', $this->htmlClass);
		}

		if ($this->hasCustomFormat()) {
			$this->setCustomInputAttributes($control);
		} else {
			$this->setNativeInputAttributes($control);
		}

		if ($this->getOption('settings') !== null) {
			$control->setAttribute('data-settings', json_encode($this->getOption('settings')));
		}

		return $control;
	}

	public function setCustomInputAttributes(Html $control): void
	{
		$control->setAttribute('data-format', $this->getFormat());
		if ($this->value !== null) {
			$control->setAttribute('value', $this->value);
		}

		if ($this->getValueAsDateTimeImmutable() !== null) {
			$control->setAttribute('data-value', $this->getValueAsDateTimeImmutable()->format($this->htmlFormat));
		}

		if ($this->min !== null) {
			$control->setAttribute('data-min', $this->min->format($this->htmlFormat));
		}

		if ($this->max !== null) {
			$control->setAttribute('data-max', $this->max->format($this->htmlFormat));
		}
	}

	public function setNativeInputAttributes(Html $control): void
	{
		if ($this->getValueAsDateTimeImmutable() !== null) {
			$control->setAttribute('value', $this->getValueAsDateTimeImmutable()->format($this->htmlFormat));
		}

		if ($this->min !== null) {
			$control->setAttribute('min', $this->min->format($this->htmlFormat));
		}

		if ($this->max !== null) {
			$control->setAttribute('max', $this->max->format($this->htmlFormat));
		}
	}

	public static function validateMin(AbstractDateTimeInput $control, string $minimum): bool
	{
		return $control->getValueAsDateTimeImmutable() === null || $control->getValueAsDateTimeImmutable() >= $control->getMin();
	}

	public static function validateMax(AbstractDateTimeInput $control, string $maximum): bool
	{
		return $control->getValueAsDateTimeImmutable() === null || $control->getValueAsDateTimeImmutable() <= $control->getMax();
	}

	/**
	 * @param array{0: string, 1: string} $range
	 */
	public static function validateRange(AbstractDateTimeInput $control, array $range): bool
	{
		if ($control->getMin() !== null && !self::validateMin($control, $range[0])) {
			return false;
		}

		return $control->getMax() === null || self::validateMax($control, $range[1]);
	}

}
