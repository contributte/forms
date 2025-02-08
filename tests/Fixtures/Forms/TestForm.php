<?php declare(strict_types = 1);

namespace Tests\Fixtures\Forms;

use Nette\Forms\Form;

final class TestForm extends Form
{

	/**
	 * @param array<mixed> $values
	 */
	public function __construct(array $values)
	{
		parent::__construct();

		$this->allowCrossOrigin();

		// Setup global variables
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_POST = $values;
		$_POST['btn'] = '';

		$this->addSubmit('btn')->onClick[] = function (): void {
			// No-op
		};
	}

	public function submit(): void
	{
		Form::initialize(true);
		$this->fireEvents();
	}

}
