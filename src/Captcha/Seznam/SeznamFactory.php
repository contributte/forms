<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Seznam;

use Contributte\Forms\Captcha\Seznam\Backend\Client;
use Contributte\Forms\Captcha\Seznam\Provider\Provider;
use Contributte\Forms\Captcha\Seznam\Provider\SeznamProvider;
use Contributte\Forms\Captcha\Seznam\Validator\SeznamValidator;
use Contributte\Forms\Captcha\Seznam\Validator\Validator;

class SeznamFactory implements Factory
{

	private Client $client;

	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	public function createValidator(): Validator
	{
		return new SeznamValidator($this->createProvider());
	}

	public function createProvider(): Provider
	{
		return new SeznamProvider($this->client);
	}

}
