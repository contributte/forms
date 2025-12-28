<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Seznam\Backend;

use Contributte\Forms\Captcha\Seznam\Exception\RuntimeException;

class HttpClient extends Client
{

	public function create(): string
	{
		$result = $this->call('captcha.create');

		if ($result['status'] !== 200 || $result['data'] === false) {
			throw new RuntimeException(sprintf('Captcha create failed: %s', print_r($result, true)));
		}

		return $result['data'];
	}

	public function getImage(string $hash): string
	{
		return sprintf(
			'https://%s:%d/%s?%s',
			$this->serverHostname,
			$this->serverPort,
			'captcha.getImage',
			http_build_query(['hash' => $hash])
		);
	}

	public function check(string $hash, string $code): bool
	{
		$result = $this->call('captcha.check', ['hash' => $hash, 'code' => $code]);

		if (!in_array($result['status'], [200, 402, 403, 404], true)) {
			throw new RuntimeException(sprintf('Captcha check failed: %s', print_r($result, true)));
		}

		return $result['status'] === 200;
	}

	/**
	 * @param array<string, mixed> $params
	 * @return array{status: int, data: string|false}
	 */
	protected function call(string $methodName, array $params = []): array
	{
		$url = sprintf('https://%s:%d/%s?%s', $this->serverHostname, $this->serverPort, $methodName, http_build_query($params));
		$ch = curl_init($url);

		if ($ch === false) {
			throw new RuntimeException('Failed to initialize curl');
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if ($this->proxyHostname !== null) {
			curl_setopt($ch, CURLOPT_PROXY, $this->proxyHostname);
			curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxyPort);
		}

		/** @var string|false $response */
		$response = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		return [
			'status' => $info['http_code'],
			'data' => $response,
		];
	}

}
