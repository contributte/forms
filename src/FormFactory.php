<?php declare(strict_types=1);

namespace Contributte\Forms;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
class FormFactory implements IFormFactory
{

	public function create(): Form
	{
		return new Form();
	}

}
