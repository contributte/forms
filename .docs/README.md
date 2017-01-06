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
    
    /**
     * @return Form
     */
    protected function createComponentUserForm()
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
    
    /**
     * @param IFormFactory $factory
     */
    public function __construct(IFormFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Form
     */
    public function create()
    {
        $form = $this->factory->create();
        
        // Add inputs here!
    
        return $form;
    }

}
```
