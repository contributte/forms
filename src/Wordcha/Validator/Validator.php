<?php declare(strict_types = 1);

namespace Contributte\Forms\Wordcha\Validator;

interface Validator
{

	public function validate(string $answer, string $hash): bool;

}
