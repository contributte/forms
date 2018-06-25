<?php declare(strict_types = 1);

namespace Contributte\Forms;

use Nette\Forms\Form;

interface IFormFactory
{

	public function create(): Form;

}
