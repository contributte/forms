<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Wordcha\Form;

use Contributte\Forms\Captcha\Wordcha\Factory;
use Contributte\Forms\Captcha\Wordcha\Generator\Generator;
use Contributte\Forms\Captcha\Wordcha\Validator\Validator;
use Nette\Forms\Container;
use Nette\Forms\Controls\HiddenField;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Nette\Utils\Strings;

class WordchaContainer extends Container
{

	private Validator $validator;

	private Generator $generator;

	public function __construct(Factory $factory)
	{
		$this->validator = $factory->createValidator();
		$this->generator = $factory->createGenerator();

		$security = $this->generator->generate();

		$textInput = new TextInput($security->getQuestion());
		$textInput->setRequired(true);

		$hiddenField = new HiddenField($security->getHash());

		$this['question'] = $textInput;
		$this['hash'] = $hiddenField;
	}

	public function getQuestion(): TextInput
	{
		$control = $this->getComponent('question');
		assert($control instanceof TextInput);

		return $control;
	}

	public function getHash(): HiddenField
	{
		$control = $this->getComponent('hash');
		assert($control instanceof HiddenField);

		return $control;
	}

	public function verify(): bool
	{
		/** @var Form $form */
		$form = $this->getForm();

		/** @var string $hash */
		$hash = $form->getHttpData(Form::DataLine, $this->getHash()->getHtmlName());

		/** @var string $answer */
		$answer = $form->getHttpData(Form::DataLine, $this->getQuestion()->getHtmlName());

		$answer = Strings::lower($answer);

		return $this->validator->validate($answer, $hash);
	}

	public function getValidator(): Validator
	{
		return $this->validator;
	}

	public function getGenerator(): Generator
	{
		return $this->generator;
	}

}
