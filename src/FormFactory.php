<?php declare(strict_types = 1);

namespace Contributte\Forms;

class FormFactory implements IFormFactory
{

	public function create(): Form
	{
		return new Form();
	}

}
