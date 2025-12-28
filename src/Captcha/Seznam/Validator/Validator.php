<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Seznam\Validator;

interface Validator
{

	public function validate(string $code, string $hash): bool;

}
