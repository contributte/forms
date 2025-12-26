<?php declare(strict_types = 1);

namespace Contributte\Forms\Rendering;

use Nette\Forms\Rendering\DefaultFormRenderer;

abstract class AbstractBootstrapHorizontalRenderer extends DefaultFormRenderer
{

	protected int $colsLabel = 3;

	protected int $colsControl = 9;

	public function setColumns(int $colsLabel, int $colsControl): void
	{
		$this->colsLabel = $colsLabel;
		$this->colsControl = $colsControl;
	}

	protected function getValue(string $name): mixed
	{
		$value = parent::getValue($name);
		if (is_string($value)) {
			$value = $this->replacePlaceholders($value);
		}

		return $value;
	}

	protected function replacePlaceholders(string $value): string
	{
		$value = str_replace('%colsLabel%', (string) $this->colsLabel, $value);
		$value = str_replace('%colsControl%', (string) $this->colsControl, $value);

		return $value;
	}

}
