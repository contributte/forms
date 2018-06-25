<?php declare(strict_types = 1);

namespace Contributte\Forms;

use Nette\Forms\Form;

interface IStandaloneFormFactory
{

	public function create(): Form;

}
