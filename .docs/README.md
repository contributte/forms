# Forms

## Content

- [StandaloneFormFactoryExtension - provides Nette\Forms\Form factory](#standalone-form-factory)

## Standalone Form Factory

```yaml
extensions:
    forms.standalone: Contributte\Forms\DI\StandaloneFormFactoryExtension
```

You can override it by your implementation.

```yaml
services:
    forms.standalone.factory: My\FormFactory
```

Straightforward is to **inject** factory to presenter.

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

Even better is to use factory in your custom form factory.

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
