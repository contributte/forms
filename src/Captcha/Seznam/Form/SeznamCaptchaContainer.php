<?php declare(strict_types = 1);

namespace Contributte\Forms\Captcha\Seznam\Form;

use Contributte\Forms\Captcha\Seznam\Factory;
use Contributte\Forms\Captcha\Seznam\Provider\Provider;
use Contributte\Forms\Captcha\Seznam\Validator\Validator;
use Nette\Forms\Container;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\HiddenField;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Nette\Utils\Html;

class SeznamCaptchaContainer extends Container
{

	private Validator $validator;

	private Provider $provider;

	public function __construct(Factory $factory)
	{
		$this->provider = $factory->createProvider();
		$this->validator = $factory->createValidator();

		$imageControl = new class ('Captcha') extends BaseControl {

			private string $imageUrl = '';

			public function __construct(string $label)
			{
				parent::__construct($label);

				$this->control = Html::el('img');
				$this->control->addClass('captcha-image seznam-captcha-image');
			}

			public function setImageUrl(string $url): void
			{
				$this->imageUrl = $url;
			}

			public function getControl(): Html
			{
				$img = parent::getControl();
				assert($img instanceof Html);

				$img->addAttributes(['src' => $this->imageUrl]);

				return $img;
			}

		};
		$imageControl->setImageUrl($this->provider->getImage());

		$codeInput = new TextInput('Code', 5);
		$codeInput->getControlPrototype()->addClass('captcha-input seznam-captcha-input');

		$hashField = new HiddenField($this->provider->getHash());

		$this['image'] = $imageControl;
		$this['code'] = $codeInput;
		$this['hash'] = $hashField;
	}

	public function getImage(): BaseControl
	{
		$control = $this->getComponent('image');
		assert($control instanceof BaseControl);

		return $control;
	}

	public function getCode(): TextInput
	{
		$control = $this->getComponent('code');
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

		/** @var string $code */
		$code = $form->getHttpData(Form::DataLine, $this->getCode()->getHtmlName());

		return $this->validator->validate($code, $hash);
	}

	public function getValidator(): Validator
	{
		return $this->validator;
	}

	public function getProvider(): Provider
	{
		return $this->provider;
	}

}
