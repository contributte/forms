<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Seznam\Provider;

use Contributte\Forms\Captcha\Seznam\Backend\Client;

class SeznamProvider implements Provider
{

	private Client $client;

	private string $hash;

	public function __construct(Client $client)
	{
		$this->client = $client;
		$this->hash = $client->create();
	}

	public function getHash(): string
	{
		return $this->hash;
	}

	public function getImage(): string
	{
		return $this->client->getImage($this->hash);
	}

	public function check(string $code, string $hash): bool
	{
		return $this->client->check($hash, $code);
	}

}
