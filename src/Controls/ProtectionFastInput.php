<?php declare(strict_types = 1);

namespace Contributte\Forms\Controls;

use DateTime;
use Nette\Forms\Controls\HiddenField;
use Nette\Forms\Form;
use Nette\Utils\Html;

class ProtectionFastInput extends HiddenField
{

	private string $diff;

	public function __construct(string $diff = '+5 seconds', string $message = 'Form was submitted too fast. Are you robot?')
	{
		parent::__construct();

		$this->diff = $diff;

		$this->setOmitted()
			->setRequired(false)
			->addRule([$this, 'validateInput'], $message);
	}

	/**
	 * @return static
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function setValue(string $value): self
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
		/** @var string|null $value */
		$value = $control->getValue();

		$d1 = new DateTime('@' . $value);
		$d1->modify($this->diff);

		$d2 = new DateTime('@' . time());

		return $d1 <= $d2;
	}

}
