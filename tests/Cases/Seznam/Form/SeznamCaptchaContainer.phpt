<?php declare(strict_types = 1);

use Contributte\Forms\Captcha\Seznam\Factory;
use Contributte\Forms\Captcha\Seznam\Form\SeznamCaptchaContainer;
use Contributte\Forms\Captcha\Seznam\Provider\Provider;
use Contributte\Forms\Captcha\Seznam\Validator\Validator;
use Contributte\Tester\Toolkit;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\HiddenField;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

Toolkit::test(function (): void {
	$hash = '12345';
	$imageUrl = 'https://example.com/captcha.png';

	$validator = Mockery::mock(Validator::class);

	$provider = Mockery::mock(Provider::class)
		->shouldReceive('getHash')
		->andReturn($hash)
		->shouldReceive('getImage')
		->andReturn($imageUrl)
		->getMock();

	$factory = Mockery::mock(Factory::class)
		->shouldReceive('createValidator')
		->andReturn($validator)
		->shouldReceive('createProvider')
		->andReturn($provider)
		->getMock();

	$captcha = new SeznamCaptchaContainer($factory);

	$form = new Form();
	$form['captcha'] = $captcha;

	Assert::type(BaseControl::class, $captcha['image']);
	Assert::type(TextInput::class, $captcha['code']);
	Assert::type(HiddenField::class, $captcha['hash']);

	Assert::equal($hash, $captcha->getHash()->getValue());
	Assert::contains($imageUrl, (string) $captcha->getImage()->getControl());
});

Toolkit::test(function (): void {
	$validator = Mockery::mock(Validator::class)
		->shouldReceive('validate')
		->andReturn(true)
		->getMock();

	$provider = Mockery::mock(Provider::class)
		->shouldReceive('getHash')
		->andReturn('hash')
		->shouldReceive('getImage')
		->andReturn('image')
		->getMock();

	$factory = Mockery::mock(Factory::class)
		->shouldReceive('createValidator')
		->andReturn($validator)
		->shouldReceive('createProvider')
		->andReturn($provider)
		->getMock();

	$captcha = new SeznamCaptchaContainer($factory);

	Assert::true($captcha->getValidator()->validate('foo', 'bar'));
});

Toolkit::test(function (): void {
	$validator = Mockery::mock(Validator::class)
		->shouldReceive('validate')
		->andReturn(false)
		->getMock();

	$provider = Mockery::mock(Provider::class)
		->shouldReceive('getHash')
		->andReturn('hash')
		->shouldReceive('getImage')
		->andReturn('image')
		->getMock();

	$factory = Mockery::mock(Factory::class)
		->shouldReceive('createValidator')
		->andReturn($validator)
		->shouldReceive('createProvider')
		->andReturn($provider)
		->getMock();

	$captcha = new SeznamCaptchaContainer($factory);

	Assert::false($captcha->getValidator()->validate('foo', 'bar'));
});
