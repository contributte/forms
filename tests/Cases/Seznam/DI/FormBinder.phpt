<?php declare(strict_types = 1);

use Contributte\Forms\Captcha\Seznam\DI\FormBinder;
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

	FormBinder::bind($factory);

	$form = new Form();
	$captcha = $form->addSeznamCaptcha();

	Assert::type(SeznamCaptchaContainer::class, $captcha);
	Assert::type(BaseControl::class, $captcha['image']);
	Assert::type(TextInput::class, $captcha['code']);
	Assert::type(HiddenField::class, $captcha['hash']);

	Assert::equal($hash, $captcha['hash']->getValue());
});
