<?php

namespace Contributte\Forms;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
class FormFactory implements IFormFactory
{

	/**
	 * @return Form
	 */
	public function create()
	{
		return new Form();
	}

}
