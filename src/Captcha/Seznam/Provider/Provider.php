<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Seznam\Provider;

interface Provider
{

	public function getHash(): string;

	public function getImage(): string;

	public function check(string $code, string $hash): bool;

}
