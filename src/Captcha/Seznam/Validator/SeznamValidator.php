<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Seznam\Validator;

use Contributte\Forms\Captcha\Seznam\Provider\Provider;

class SeznamValidator implements Validator
{

	private Provider $provider;

	public function __construct(Provider $provider)
	{
		$this->provider = $provider;
	}

	public function validate(string $code, string $hash): bool
	{
		return $this->provider->check($code, $hash);
	}

}
