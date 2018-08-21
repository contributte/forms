# Forms

## Content

- [ApplicationFormFactoryExtension - provides Nette\Application\UI\Form factory](#application-form-factory)
- [StandaloneFormFactoryExtension - provides Nette\Forms\Form factory](#standalone-form-factory)

## Application Form Factory

ApplicationFormFactory returns instance of `Nette\Application\UI\Form`. It should be used in place of StandaloneFormFactory if [nette/application](https://github.com/nette/application) is installed.

```yaml
extensions:
    forms.application: Contributte\Forms\DI\ApplicationFormFactoryExtension
```

You can override it by your implementation.

```yaml
services:
    forms.application.factory: My\FormFactory
```

Straightforward way is to **inject** the factory in a presenter.

```php
namespace App\Presenters;

use Contributte\Forms\IApplicationFormFactory;
use Nette\Application\UI\Form;

final class UserPresenter extends BasePresenter
{

    /** @var IApplicationFormFactory @inject */
    public $factory;

    protected function createComponentUserForm(): Form
    {
        $form = $this->factory->create();

        // Add inputs here!

        return $form;
    }

}
```

Even better is to use the factory in your custom form factory.

```php
namespace App\Forms;

use Contributte\Forms\IApplicationFormFactory;
use Nette\Application\UI\Form;

final class UserFormFactory
{

    /** @var IApplicationFormFactory */
    private $factory;

    public function __construct(IApplicationFormFactory $factory)
    {
        $this->factory = $factory;
    }

    public function create(): Form
    {
        $form = $this->factory->create();

        // Add inputs here!

        return $form;
    }

}
```

## Standalone Form Factory

StandaloneFormFactory returns instance of `Nette\Forms\Form`. It should be used only if [nette/application](https://github.com/nette/application) is not installed.

```yaml
extensions:
    forms.standalone: Contributte\Forms\DI\StandaloneFormFactoryExtension
```

You can override it by your implementation.

```yaml
services:
    forms.standalone.factory: My\FormFactory
```

Straightforward way is to **inject** factory in a presenter.

```php
namespace App\Presenters;

use Contributte\Forms\IStandaloneFormFactory;
use Nette\Forms\Form;

final class UserPresenter extends BasePresenter
{

    /** @var IStandaloneFormFactory @inject */
    public $factory;

    protected function createComponentUserForm(): Form
    {
        $form = $this->factory->create();

        // Add inputs here!

        return $form;
    }

}
```

Even better is to use the factory in your custom form factory.

```php
namespace App\Forms;

use Contributte\Forms\IStandaloneFormFactory;
use Nette\Forms\Form;

final class UserFormFactory
{

    /** @var IStandaloneFormFactory */
    private $factory;

    public function __construct(IStandaloneFormFactory $factory)
    {
        $this->factory = $factory;
    }

    public function create(): Form
    {
        $form = $this->factory->create();

        // Add inputs here!

        return $form;
    }

}
```
