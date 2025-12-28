<?php declare(strict_types = 1);

namespace Contributte\Forms\Wordcha\DataSource;

interface DataSource
{

	public function get(): Pair;

}
