<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Wordcha\Validator;

interface Validator
{

	public function validate(string $answer, string $hash): bool;

}
