<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Seznam\Backend;

abstract class Client
{

	protected string $serverHostname;

	protected int $serverPort;

	protected ?string $proxyHostname = null;

	protected ?int $proxyPort = null;

	public function __construct(string $hostname, int $port)
	{
		$this->serverHostname = $hostname;
		$this->serverPort = $port;
	}

	abstract public function create(): string;

	abstract public function getImage(string $hash): string;

	abstract public function check(string $hash, string $code): bool;

	public function setProxy(string $hostname, int $port): void
	{
		$this->proxyHostname = $hostname;
		$this->proxyPort = $port;
	}

}
