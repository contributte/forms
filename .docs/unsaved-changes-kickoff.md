# Kick-off: JavaScript Alert Before Leaving Page (Issue #2)

## Overview

Implement a feature to warn users when they attempt to leave a page with unsaved form changes. This is a common UX pattern to prevent accidental data loss.

## Current State

- **No JavaScript exists** in the codebase
- Forms use **data attributes** for JS library integration (e.g., DateTime controls)
- 9 Bootstrap renderers exist (Bootstrap 3/4/5 × Vertical/Horizontal/Inline)
- Custom controls extend `Nette\Forms\Controls\BaseControl`

---

## Proposed Approaches

### Option A: JavaScript Module + PHP Helper Control (Recommended)

**Architecture:**
```
src/
├── Controls/
│   └── UnsavedChangesControl.php    # Hidden control that enables tracking
├── assets/
│   └── unsavedChanges.js            # Vanilla JS tracker (no dependencies)
```

**How it works:**
1. Add `UnsavedChangesControl` to form → renders hidden input + data attributes
2. Include JS file once on page
3. JS auto-initializes based on data attributes

**PHP Control:**
```php
<?php

namespace Contributte\Forms\Controls;

use Nette\Forms\Controls\HiddenField;

class UnsavedChangesControl extends HiddenField
{
    private string $message = 'You have unsaved changes. Are you sure you want to leave?';
    private bool $trackOnlyModified = true;
    private ?string $excludeSelector = null;

    public function __construct(?string $message = null)
    {
        parent::__construct();
        if ($message !== null) {
            $this->message = $message;
        }
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function setExcludeSelector(string $selector): self
    {
        $this->excludeSelector = $selector;
        return $this;
    }

    public function getControl(): Html
    {
        $control = parent::getControl();
        $control->data('unsaved-changes', true);
        $control->data('unsaved-message', $this->message);
        if ($this->excludeSelector) {
            $control->data('unsaved-exclude', $this->excludeSelector);
        }
        return $control;
    }

    public static function register(string $method = 'addUnsavedWarning'): void
    {
        Container::extensionMethod($method, function (Container $container, string $name, ?string $message = null) {
            return $container[$name] = new UnsavedChangesControl($message);
        });
    }
}
```

**JavaScript (Vanilla, no dependencies):**
```javascript
// src/assets/unsavedChanges.js
(function() {
    'use strict';

    class UnsavedChangesTracker {
        constructor(form, options = {}) {
            this.form = form;
            this.message = options.message || 'You have unsaved changes. Are you sure you want to leave?';
            this.excludeSelector = options.excludeSelector || null;
            this.isDirty = false;
            this.initialState = this.getFormState();

            this.init();
        }

        init() {
            // Track all input changes
            this.form.addEventListener('input', () => this.markDirty());
            this.form.addEventListener('change', () => this.markDirty());

            // Clear dirty flag on submit
            this.form.addEventListener('submit', () => {
                this.isDirty = false;
            });

            // Warn before leaving
            window.addEventListener('beforeunload', (e) => {
                if (this.isDirty && this.hasChanges()) {
                    e.preventDefault();
                    e.returnValue = this.message;
                    return this.message;
                }
            });
        }

        markDirty() {
            this.isDirty = true;
        }

        getFormState() {
            const data = new FormData(this.form);
            return JSON.stringify([...data.entries()]);
        }

        hasChanges() {
            return this.getFormState() !== this.initialState;
        }

        reset() {
            this.isDirty = false;
            this.initialState = this.getFormState();
        }
    }

    // Auto-initialize
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-unsaved-changes]').forEach(function(input) {
            const form = input.closest('form');
            if (form && !form._unsavedTracker) {
                form._unsavedTracker = new UnsavedChangesTracker(form, {
                    message: input.dataset.unsavedMessage,
                    excludeSelector: input.dataset.unsavedExclude
                });
            }
        });
    });

    // Export for manual use
    window.UnsavedChangesTracker = UnsavedChangesTracker;
})();
```

**Usage:**
```php
$form = new Form();
$form->addText('name', 'Name');
$form->addUnsavedWarning('_unsaved', 'You have unsaved changes!');
$form->addSubmit('save', 'Save');
```

```html
<!-- Include JS once -->
<script src="vendor/contributte/forms/assets/unsavedChanges.js"></script>
```

**Pros:**
- Clean separation (PHP control + JS module)
- Works with all existing renderers
- Optional per-form (add control only where needed)
- Configurable message per form
- No dependencies

**Cons:**
- Requires manual JS include

---

### Option B: Renderer Enhancement (Data Attribute Approach)

**Architecture:**
Add data attributes at the form level via renderer.

**New Method in Renderers:**
```php
abstract class AbstractBootstrapRenderer extends DefaultFormRenderer
{
    protected bool $trackUnsavedChanges = false;
    protected string $unsavedMessage = 'You have unsaved changes.';

    public function enableUnsavedChangesWarning(string $message = null): self
    {
        $this->trackUnsavedChanges = true;
        if ($message) {
            $this->unsavedMessage = $message;
        }
        return $this;
    }

    public function render(Form $form, ?string $mode = null): string
    {
        if ($this->trackUnsavedChanges) {
            $form->getElementPrototype()->data('track-unsaved', true);
            $form->getElementPrototype()->data('unsaved-message', $this->unsavedMessage);
        }
        return parent::render($form, $mode);
    }
}
```

**Usage:**
```php
$renderer = new Bootstrap5VerticalRenderer();
$renderer->enableUnsavedChangesWarning('Changes not saved!');
$form->setRenderer($renderer);
```

**Pros:**
- Integrated with existing renderer system
- No extra form fields needed

**Cons:**
- Requires modifying all 9 renderers (or creating abstract base)
- Less flexible (renderer-level, not form-level)
- Still requires JS file include

---

### Option C: Standalone JavaScript with Manual Integration

**Architecture:**
Pure JavaScript solution, documented for manual use.

```javascript
// assets/contributte-forms.js
window.ContributteForms = {
    trackUnsavedChanges: function(formSelector, options) {
        // ... implementation
    }
};
```

**Usage:**
```javascript
ContributteForms.trackUnsavedChanges('#my-form', {
    message: 'Unsaved changes!'
});
```

**Pros:**
- Maximum flexibility
- No PHP changes needed
- Can be used with any form

**Cons:**
- Requires JavaScript knowledge from user
- Not integrated with PHP library
- Less "Nette-like"

---

### Option D: Nette Extension with Asset Registration

**Architecture:**
DI extension that registers JS assets automatically via `nette/assets` or similar.

```php
class FormsExtension extends CompilerExtension
{
    public function afterCompile(ClassType $class): void
    {
        // Register JS assets
    }
}
```

**Pros:**
- Fully automated
- No manual JS includes

**Cons:**
- Complex implementation
- Depends on asset management strategy
- Over-engineered for this feature

---

## Recommended Implementation: Option A

### Why Option A?

1. **Follows existing patterns** - Similar to `ProtectionFastInput` control
2. **Opt-in per form** - Only forms that need it get it
3. **Configurable** - Message, excluded elements, etc.
4. **No renderer changes** - Works with all 9 existing renderers
5. **Clean separation** - PHP handles config, JS handles behavior
6. **Nette-idiomatic** - Extension methods like other controls

### Implementation Plan

| Phase | Task | Effort |
|-------|------|--------|
| 1 | Create `UnsavedChangesControl.php` | Small |
| 2 | Create `unsavedChanges.js` | Medium |
| 3 | Add tests | Small |
| 4 | Update documentation | Small |
| 5 | Add TypeScript definitions (optional) | Small |

### File Structure

```
src/
├── Controls/
│   ├── DateTime/
│   ├── ProtectionFastInput.php
│   └── UnsavedChangesControl.php     # NEW
├── assets/
│   └── unsavedChanges.js             # NEW (or .ts)
```

### Features to Include

**Core:**
- [x] `beforeunload` warning on page leave
- [x] Auto-disable on form submit
- [x] Configurable warning message
- [x] Form state comparison (not just "touched")

**Advanced (optional):**
- [ ] Exclude specific links/buttons (`data-unsaved-ignore`)
- [ ] localStorage/sessionStorage draft saving
- [ ] Visual indicator (badge/icon showing unsaved state)
- [ ] Programmatic API (`form.isDirty()`, `form.resetDirty()`)
- [ ] Integration with Nette AJAX (naja, nette.ajax.js)

### API Design

```php
// Registration (in bootstrap or DI)
UnsavedChangesControl::register(); // adds addUnsavedWarning()

// Basic usage
$form->addUnsavedWarning('_unsaved');

// With custom message
$form->addUnsavedWarning('_unsaved', 'Your changes will be lost!');

// With options
$form->addUnsavedWarning('_unsaved')
    ->setMessage('Custom message')
    ->setExcludeSelector('.no-warn, [data-no-warn]');
```

### JavaScript API

```javascript
// Auto-initialized via data attributes

// Manual initialization
const tracker = new UnsavedChangesTracker(formElement, {
    message: 'Custom message',
    excludeSelector: '.ignore-links'
});

// Programmatic control
tracker.isDirty;        // boolean
tracker.reset();        // Reset to current state
tracker.disable();      // Temporarily disable
tracker.enable();       // Re-enable
```

---

## Alternative Considerations

### Using naja (Nette AJAX library)

If project uses naja, could integrate:
```javascript
naja.addEventListener('before', (event) => {
    if (form._unsavedTracker?.isDirty) {
        if (!confirm(message)) {
            event.preventDefault();
        }
    }
});
```

### Using nette.ajax.js (legacy)

Similar pattern for legacy AJAX handler.

### Browser Support

- `beforeunload` - All modern browsers
- Custom messages - Limited (most browsers show generic message)
- `FormData` - IE10+ (polyfill available)

---

## Questions to Resolve

1. **Should JS be TypeScript?** - Better IDE support, but adds build step
2. **NPM package?** - Publish separately or bundle?
3. **Auto-include via CDN?** - Document CDN usage?
4. **AJAX compatibility?** - Support naja/nette.ajax.js out of box?
5. **Draft saving?** - Include localStorage persistence?

---

## Next Steps

1. Review and approve approach
2. Implement `UnsavedChangesControl.php`
3. Implement `unsavedChanges.js`
4. Write tests
5. Update `.docs/README.md`
6. Consider npm publishing strategy
