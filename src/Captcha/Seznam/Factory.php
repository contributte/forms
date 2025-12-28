<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Seznam;

use Contributte\Forms\Captcha\Seznam\Provider\Provider;
use Contributte\Forms\Captcha\Seznam\Validator\Validator;

interface Factory
{

	public function createValidator(): Validator;

	public function createProvider(): Provider;

}
