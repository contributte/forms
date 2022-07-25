<?php declare(strict_types = 1);

use Contributte\Forms\Controls\DateTime\DateTimeParser;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

date_default_timezone_set('America/New_York');

// Default formats
test(function (): void {
	$parser = new DateTimeParser();

	$dateTimeOffsetTz = new DateTimeImmutable('2022-01-05T12:30:45', new DateTimeZone('-04:00'));
	Assert::equal($dateTimeOffsetTz, $parser->parse($dateTimeOffsetTz->format(DateTimeInterface::ATOM)));
	Assert::equal($dateTimeOffsetTz, $parser->parse($dateTimeOffsetTz->format(DateTimeInterface::ISO8601)));
	Assert::equal($dateTimeOffsetTz, $parser->parse($dateTimeOffsetTz->format(DateTimeInterface::W3C)));
	Assert::equal($dateTimeOffsetTz, $parser->parse($dateTimeOffsetTz->format(DateTimeInterface::RSS)));

	$dateTimeNamedTzAlt = new DateTimeImmutable('2022-01-05T12:30:45', new DateTimeZone('EST'));
	Assert::equal($dateTimeNamedTzAlt, $parser->parse($dateTimeNamedTzAlt->format(DateTimeInterface::COOKIE)));

	$dateTimeLocalTz = new DateTimeImmutable('2022-01-05T12:30:45');
	Assert::equal($dateTimeLocalTz, $parser->parse($dateTimeLocalTz->format('Y-m-d H:i:s.u')));
	Assert::equal($dateTimeLocalTz, $parser->parse($dateTimeLocalTz->format('Y-m-d H:i:s')));
	Assert::equal($dateTimeLocalTz, $parser->parse($dateTimeLocalTz->format('Y-m-d\TH:i:s')));

	$dateTimeLocalTzWithoutSeconds = new DateTimeImmutable('2022-01-05T12:30:00');
	Assert::equal($dateTimeLocalTzWithoutSeconds, $parser->parse($dateTimeLocalTzWithoutSeconds->format('Y-m-d H:i')));
	Assert::equal($dateTimeLocalTzWithoutSeconds, $parser->parse($dateTimeLocalTzWithoutSeconds->format('Y-m-d\TH:i')));

	$dateLocalTz = new DateTimeImmutable('2022-01-05T00:00:00');
	Assert::equal($dateLocalTz, $parser->parse($dateLocalTz->format('Y-m-d')));
});

// Custom formats
test(function (): void {
	$parser = new DateTimeParser();
	$parser->setFormats([
		'd.m.Y',
		'm/d/Y',
	]);
	$parser->addFormat('H:i');

	$dateLocalTz = new DateTimeImmutable('2022-01-05T00:00:00');
	Assert::equal($dateLocalTz, $parser->parse($dateLocalTz->format('d.m.Y')));
	Assert::equal($dateLocalTz, $parser->parse($dateLocalTz->format('m/d/Y')));

	$timeLocalTz = new DateTimeImmutable('1970-01-01T12:30:00');
	Assert::equal($timeLocalTz, $parser->parse($timeLocalTz->format('H:i')));
});
