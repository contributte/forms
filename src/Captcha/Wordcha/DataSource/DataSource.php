<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Wordcha\DataSource;

interface DataSource
{

	public function get(): Pair;

}
