<?php declare(strict_types = 1);

use Contributte\Forms\Captcha\Wordcha\DataSource\NumericDataSource;
use Contributte\Forms\Captcha\Wordcha\Exception\LogicalException;
use Contributte\Tester\Toolkit;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

// Test default min/max values (0-10)
Toolkit::test(function (): void {
	$dataSource = new NumericDataSource();
	$pair = $dataSource->get();

	Assert::type('string', $pair->getQuestion());
	Assert::type('string', $pair->getAnswer());
	Assert::match('~^\d+ \+ \d+$~', $pair->getQuestion());

	// Answer should be between 0 and 20 (0+0 to 10+10)
	$answer = (int) $pair->getAnswer();
	Assert::true($answer >= 0 && $answer <= 20);
});

// Test custom min/max values
Toolkit::test(function (): void {
	$dataSource = new NumericDataSource(5, 15);
	$pair = $dataSource->get();

	Assert::type('string', $pair->getQuestion());
	Assert::type('string', $pair->getAnswer());
	Assert::match('~^\d+ \+ \d+$~', $pair->getQuestion());

	// Answer should be between 10 and 30 (5+5 to 15+15)
	$answer = (int) $pair->getAnswer();
	Assert::true($answer >= 10 && $answer <= 30);
});

// Test min equals max
Toolkit::test(function (): void {
	$dataSource = new NumericDataSource(5, 5);
	$pair = $dataSource->get();

	Assert::equal('5 + 5', $pair->getQuestion());
	Assert::equal('10', $pair->getAnswer());
});

// Test min greater than max throws exception
Toolkit::test(function (): void {
	Assert::exception(function (): void {
		new NumericDataSource(10, 5);
	}, LogicalException::class, 'Min (10) must be less than or equal to max (5)');
});

// Test negative numbers
Toolkit::test(function (): void {
	$dataSource = new NumericDataSource(-5, 5);
	$pair = $dataSource->get();

	Assert::type('string', $pair->getQuestion());
	Assert::type('string', $pair->getAnswer());

	// Answer should be between -10 and 10 (-5+-5 to 5+5)
	$answer = (int) $pair->getAnswer();
	Assert::true($answer >= -10 && $answer <= 10);
});
