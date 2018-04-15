<?php declare(strict_types=1);

namespace Contributte\Forms;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
interface IFormFactory
{

	public function create(): Form;

}
