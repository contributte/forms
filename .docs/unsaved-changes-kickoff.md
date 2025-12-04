# Kick-off: JavaScript Alert Before Leaving Page (Issue #2)

## Overview

Implement a feature to warn users when they attempt to leave a page with unsaved form changes.

## Implementation

Simple decorator pattern with two classes:

### 1. FormExtras (Static Helper)

Sets data attributes on the form:

```php
<?php
namespace Contributte\Forms;

final class FormExtras
{
    public static function applyConfirm(Form $form, ?string $message = null): void;
}
```

### 2. FormExtrasRenderer (Decorator)

Wraps any existing renderer and injects JS when data attributes are present:

```php
<?php
namespace Contributte\Forms\Rendering;

class FormExtrasRenderer implements IFormRenderer
{
    public function __construct(IFormRenderer $inner);
    public function render(Form $form, ?string $mode = null): string;
}
```

## Usage

```php
use Contributte\Forms\FormExtras;
use Contributte\Forms\Rendering\FormExtrasRenderer;

$form = new Form();
$form->addText('name', 'Name');
$form->addSubmit('save', 'Save');

// Apply confirmation behavior
FormExtras::applyConfirm($form, 'You have unsaved changes!');

// Wrap existing renderer
$form->setRenderer(new FormExtrasRenderer($form->getRenderer()));
```

## How It Works

1. `FormExtras::applyConfirm()` adds `data-confirm-unsaved` and `data-confirm-message` attributes to form
2. `FormExtrasRenderer` wraps any renderer (Bootstrap, default, custom)
3. When rendering, if `data-confirm-unsaved` is present, inline JS is injected after form
4. JS tracks form changes and warns on `beforeunload`

## Features

- Works with any renderer (decorator pattern)
- No external JS files needed (inline script)
- Only injects JS when feature is used
- Configurable message per form
- Smart change detection (compares actual values, not just "touched")
- Auto-disables on form submit

## File Structure

```
src/
├── FormExtras.php                    # Static helper
├── Rendering/
│   └── FormExtrasRenderer.php        # Decorator renderer
```
