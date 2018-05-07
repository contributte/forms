# Forms

## Content

- [FormFactoryExtension - provides simple FormFactory](#form-factory)

## Form Factory

```yaml
extensions:
    forms: Contributte\Forms\DI\FormFactoryExtension
```

You can override it by your implementation.

```yaml
services:
    forms.factory: My\FormFactory
```

Straightforward is to **inject** factory to presenter.

```php
namespace App\Presenters;

final class UserPresenter extends BasePresenter
{

    /** @var IFormFactory @inject */
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

use Contributte\Forms\IFormFactory;

final class UserFormFactory
{

    /** @var IFormFactory */
    private $factory;
    
    public function __construct(IFormFactory $factory)
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
