<?php declare(strict_types = 1);

use Contributte\Forms\Captcha\Wordcha\DI\FormBinder;
use Contributte\Forms\Captcha\Wordcha\Factory;
use Contributte\Forms\Captcha\Wordcha\Form\WordchaContainer;
use Contributte\Forms\Captcha\Wordcha\Generator\Generator;
use Contributte\Forms\Captcha\Wordcha\Generator\Security;
use Contributte\Forms\Captcha\Wordcha\Validator\Validator;
use Contributte\Tester\Toolkit;
use Nette\Forms\Controls\HiddenField;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

Toolkit::test(function (): void {
	$hash = '12345';
	$validator = Mockery::mock(Validator::class);

	$generator = Mockery::mock(Generator::class)
		->shouldReceive('generate')
		->andReturn(new Security('..', $hash))
		->getMock()
		->shouldReceive('hash')
		->andReturn($hash)
		->getMock();

	$factory = Mockery::mock(Factory::class)
		->shouldReceive('createValidator')
		->andReturn($validator)
		->shouldReceive('createGenerator')
		->andReturn($generator)
		->getMock();

	FormBinder::bind($factory);

	$form = new Form();
	$captcha = $form->addWordcha();

	Assert::type(WordchaContainer::class, $captcha);
	Assert::type(TextInput::class, $captcha['question']);
	Assert::type(HiddenField::class, $captcha['hash']);

	Assert::equal($hash, $captcha['hash']->getValue());
});
