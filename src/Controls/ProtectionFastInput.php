<?php declare(strict_types = 1);

namespace Contributte\Forms\Controls;

use Nette\Forms\Controls\HiddenField;
use Nette\Utils\Html;

class ProtectionFastInput extends HiddenField
{

	/** @var string */
	private $diff;

	public function __construct(string $diff = '+5 second', string $message = 'Form was submitted to fast. Are you robot?')
	{
		parent::__construct();

		$this->diff = $diff;

		$this->setOmitted()
			->setRequired(false)
			->addRule([$this, 'validateInput'], $message);
	}

	/**
	 * @inheritdoc
	 */
	public function setValue($value): self
	{
		return $this;
	}

	public function loadHttpData(): void
	{
		$this->value = $this->getHttpData(Form::DATA_TEXT);
	}

	public function getControl(): Html
	{
		return parent::getControl()->value(time());
	}

	public function validateInput(ProtectionFastInput $control): bool
	{
		$value = (string) $control->getValue();

		$d1 = new DateTime('@' . $value);
		$d1->modify($this->diff);

		$d2 = new DateTime('@' . time());

		return $d1 <= $d2;
	}

}
