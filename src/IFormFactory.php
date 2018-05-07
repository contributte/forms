<?php declare(strict_types = 1);

namespace Contributte\Forms;

interface IFormFactory
{

	public function create(): Form;

}
