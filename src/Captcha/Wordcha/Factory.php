<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Wordcha;

use Contributte\Forms\Captcha\Wordcha\Generator\Generator;
use Contributte\Forms\Captcha\Wordcha\Validator\Validator;

interface Factory
{

	public function createValidator(): Validator;

	public function createGenerator(): Generator;

}
