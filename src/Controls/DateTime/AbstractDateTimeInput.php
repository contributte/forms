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

	protected DateTimeParser $parser;

	/** @var callable(DateTimeImmutable ): mixed|null */
	protected $valueTransformation = null;

	protected ?DateTimeInterface $min = null;

	protected ?DateTimeInterface $max = null;

	protected string $defaultHumanFormat = 'Y-m-d H:i';

	protected string $htmlFormat = 'Y-m-d\TH:i';

	protected string $htmlClass = '';

	protected string|object $invalidValueMessage = 'Invalid format';

	/**
	 * @param string|string[] $format
	 */
	public function __construct(?string $label = null, string|array|null $format = null)
	{
		parent::__construct($label);

		$this->parser = new DateTimeParser();
		if ($format !== null) {
			$this->setFormat($format);
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

	/**
	 * @param string|string[] $format
	 * @return static
	 */
	public function setFormat(string|array $format): static
	{
		if (is_string($format)) {
			$format = [$format];
		}

		if (!is_array($format) || /** @phpstan-ignore-line */
			Arrays::some($format, fn ($item) => !is_string($item))
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
	public function setValueType(string|callable $type): static
	{
		$this->valueTransformation = $this->createValueTransformation($type);

		return $this;
	}

	/**
	 * @return static
	 */
	public function addFormat(string $format): static
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

	public function setValue(mixed $value): static
	{
		if ($value instanceof DateTimeInterface) {
			$value = $this->normalizeDateTime($value)->format($this->getFormat());
		} elseif (is_int($value)) { // timestamp
			$value = (new DateTimeImmutable())->setTimestamp($value)->format($this->getFormat());
		}

		parent::setValue($value);

		return $this;
	}

	public function setDefaultValue(mixed $value): static
	{
		if (is_string($value)) {
			$value = $this->parser->parse($value) ?? $value;
		}

		return parent::setDefaultValue($value);
	}

	public function getValue(): mixed
	{
		return $this->transformValue($this->getValueAsDateTimeImmutable());
	}

	public function getRawValue(): mixed
	{
		return parent::getValue();
	}

	/**
	 * @template T
	 * @phpstan-param class-string<T>|callable(DateTimeInterface): ?T $type
	 * @phpstan-return ?T
	 */
	public function getValueAs(string|callable $type): mixed
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
	 * @return static
	 */
	public function setInvalidValueMessage(string|object $message): static
	{
		$this->invalidValueMessage = $message;

		return $this;
	}

	/**
	 * @return static
	 */
	public function addRule(callable|string $validator, string|object|null $message = null, mixed $arg = null): static
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

	/**
	 * @template T
	 * @phpstan-param class-string<T>|callable(DateTimeInterface): ?T $type
	 * @return callable(DateTimeInterface): ?T
	 */
	protected function createValueTransformation(string|callable $type): callable
	{
		if (is_callable($type)) {
			return $type;
		} elseif (is_string($type)) {
			if (!class_exists($type) || class_implements($type) === false || !in_array('DateTimeInterface', class_implements($type), true)) {
				throw new InvalidArgumentException('Value type must be existing class implementing \DateTimeInterface');
			}
		} else {
			throw new InvalidArgumentException('Value type can be only string with class name or or callback.');
		}

		if ($type === 'Nette\Utils\DateTime' || (class_parents($type) !== false && in_array('Nette\Utils\DateTime', class_parents($type), true))) {
			return [$type, 'from'];
		} else {
			$transformation = fn (DateTimeInterface $value) => call_user_func([$type, 'createFromFormat'], 'Y-m-d\TH:i:s.ue|', $value->format('Y-m-d\TH:i:s.ue'));

			return $transformation;
		}
	}

	protected function transformValue(?DateTimeImmutable $value): mixed
	{
		if ($value === null) {
			return null;
		}

		if (is_callable($this->valueTransformation)) {
			$value = call_user_func($this->valueTransformation, $value);
		}

		return $value;
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

	protected function getValueAsDateTimeImmutable(): ?DateTimeImmutable
	{
		if ($this->value === null || $this->value === '') {
			return null;
		}

		$date = $this->parser->parse($this->value);
		if ($date === null) {
			$this->addError($this->invalidValueMessage);

			return null;
		}

		return $date;
	}

}
