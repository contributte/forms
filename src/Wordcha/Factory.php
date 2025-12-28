<?php declare(strict_types = 1);

namespace Contributte\Forms\Wordcha;

use Contributte\Forms\Wordcha\Generator\Generator;
use Contributte\Forms\Wordcha\Validator\Validator;

interface Factory
{

	public function createValidator(): Validator;

	public function createGenerator(): Generator;

}
