<?php declare(strict_types = 1);

namespace Contributte\Forms;

use Nette\Application\UI\Form;

interface IApplicationFormFactory
{

	public function create(): Form;

}
