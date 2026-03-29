# Unsaved Form Changes Warning - Proof of Concept

**Related Issue:** [#2 - JavaScript - show alert before leaving page](https://github.com/contributte/forms/issues/2)

This PoC demonstrates a feature that warns users when they attempt to navigate away from a page with unsaved form changes. The implementation is based on the browser's `beforeunload` event and works with both vanilla HTML forms and Nette Forms.

## Features

- ✅ **Automatic change detection** - Tracks all form field changes in real-time
- ✅ **Browser confirmation dialog** - Shows native browser warning before leaving
- ✅ **Multiple initialization methods** - Declarative (HTML attributes) or programmatic (JavaScript)
- ✅ **Nette Forms integration** - Easy-to-use PHP helper class
- ✅ **Customizable messages** - Set custom warning text
- ✅ **Smart form submission** - Automatically clears warning on form submit
- ✅ **Debug mode** - Console logging for development
- ✅ **Zero dependencies** - Pure vanilla JavaScript, no jQuery required
- ✅ **Framework agnostic** - Works with any form, not just Nette

## Files Added

```
assets/
  └── unsaved-changes.js          # Core JavaScript tracker

src/Controls/
  └── UnsavedChangesControl.php   # PHP helper for Nette Forms

examples/
  ├── unsaved-changes-demo.html   # HTML demo with multiple examples
  └── unsaved-changes-nette.php   # Nette Forms integration demo
```

## Usage

### 1. HTML Forms (Declarative)

The simplest way to enable the warning is using a data attribute:

```html
<form data-unsaved-warning="You have unsaved changes!">
    <input type="text" name="name" placeholder="Name">
    <textarea name="message"></textarea>
    <button type="submit">Submit</button>
</form>

<script src="assets/unsaved-changes.js"></script>
```

**With debug mode:**

```html
<form data-unsaved-warning="true" data-unsaved-debug>
    <!-- form fields -->
</form>
```

### 2. JavaScript (Programmatic)

For more control, initialize the tracker manually:

```javascript
var tracker = new UnsavedChangesTracker('#myForm', {
    message: 'You have unsaved changes. Are you sure you want to leave?',
    debug: true,
    trackInputs: true,
    trackTextareas: true,
    trackSelects: true,
    trackCheckboxes: true,
    trackRadios: true,
    resetOnSubmit: true
});

// Check if form is dirty
if (tracker.isDirty) {
    console.log('Form has unsaved changes');
}

// Manually reset the tracker
tracker.reset();

// Manually mark as dirty
tracker.setDirty(true);

// Destroy the tracker
tracker.destroy();
```

### 3. Nette Forms (PHP)

Use the `UnsavedChangesControl` helper class:

```php
use Nette\Forms\Form;
use Contributte\Forms\Controls\UnsavedChangesControl;
use Contributte\Forms\Rendering\Bootstrap5VerticalRenderer;

$form = new Form();
$form->addText('name', 'Name:');
$form->addEmail('email', 'Email:');
$form->addSubmit('submit', 'Submit');

// Enable unsaved changes warning (default message)
UnsavedChangesControl::enable($form);

// Or with custom message
UnsavedChangesControl::enable($form, 'You have unsaved changes!');

// With debug mode
UnsavedChangesControl::enable($form, 'Custom message', debug: true);

// With advanced options
UnsavedChangesControl::enableWithOptions($form, [
    'message' => 'Unsaved changes detected!',
    'debug' => true,
]);

// Disable the warning
UnsavedChangesControl::disable($form);

// Render form (works with any renderer)
$form->setRenderer(new Bootstrap5VerticalRenderer());
echo $form;
```

## Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `message` | string | "You have unsaved changes..." | Warning message shown to user |
| `trackInputs` | boolean | `true` | Track text input fields |
| `trackTextareas` | boolean | `true` | Track textarea fields |
| `trackSelects` | boolean | `true` | Track select dropdowns |
| `trackCheckboxes` | boolean | `true` | Track checkboxes |
| `trackRadios` | boolean | `true` | Track radio buttons |
| `excludeSelectors` | array | `[]` | Array of CSS selectors to exclude |
| `resetOnSubmit` | boolean | `true` | Clear warning on form submit |
| `debug` | boolean | `false` | Enable console logging |

## How It Works

1. **Initial State** - When initialized, the tracker stores the current value of all tracked form fields
2. **Change Detection** - Listens to `input` and `change` events on form fields
3. **Dirty Flag** - Sets an internal `isDirty` flag when any field value differs from its initial value
4. **beforeunload Handler** - Attaches a `beforeunload` event listener that shows a confirmation dialog if the form is dirty
5. **Form Submit** - On form submission, the dirty flag is cleared to allow navigation without warning

## Browser Compatibility

The `beforeunload` event is supported in all modern browsers:

- ✅ Chrome/Edge 1+
- ✅ Firefox 1+
- ✅ Safari 3+
- ✅ Opera 12+
- ✅ Internet Explorer 4+

**Note:** Modern browsers (Chrome 51+, Firefox 44+) ignore custom messages in the confirmation dialog for security reasons. They display a generic browser-provided message instead. However, the feature still works correctly.

## Testing the PoC

### Option 1: HTML Demo

1. Open `examples/unsaved-changes-demo.html` in your browser
2. Fill out any form on the page
3. Try to close the tab or navigate away
4. You'll see a browser confirmation dialog

### Option 2: Nette Forms Demo

1. Make sure composer dependencies are installed:
   ```bash
   composer install
   ```

2. Start a PHP development server:
   ```bash
   php -S localhost:8000 -t examples/
   ```

3. Open `http://localhost:8000/unsaved-changes-nette.php` in your browser

4. Fill out the form and try to navigate away

### Option 3: Integration Test

Create your own test file:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Test</title>
</head>
<body>
    <form data-unsaved-warning="Don't leave!">
        <input type="text" name="test">
        <button type="submit">Submit</button>
    </form>
    <script src="../assets/unsaved-changes.js"></script>
</body>
</html>
```

## API Reference

### Constructor

```javascript
new UnsavedChangesTracker(form, options)
```

- `form` - HTMLFormElement or CSS selector string
- `options` - Configuration object (see Configuration Options above)

### Methods

#### `reset()`
Resets the tracker and marks the form as clean.

```javascript
tracker.reset();
```

#### `setDirty(dirty)`
Manually set the dirty flag.

```javascript
tracker.setDirty(true);  // Mark as dirty
tracker.setDirty(false); // Mark as clean
```

#### `isDirtyForm()`
Returns whether the form has unsaved changes.

```javascript
if (tracker.isDirtyForm()) {
    console.log('Form has changes');
}
```

#### `checkChanges()`
Manually check if form has changes and update the dirty flag.

```javascript
var hasChanges = tracker.checkChanges();
```

#### `destroy()`
Destroys the tracker and cleans up.

```javascript
tracker.destroy();
```

### Properties

#### `isDirty`
Boolean flag indicating whether the form has unsaved changes.

```javascript
console.log(tracker.isDirty); // true or false
```

## Integration with Existing Projects

### Adding to Your Project

1. **Copy the JavaScript file:**
   ```bash
   cp assets/unsaved-changes.js public/js/
   ```

2. **Include in your layout/template:**
   ```html
   <script src="/js/unsaved-changes.js"></script>
   ```

3. **For Nette projects, copy the PHP helper:**
   ```bash
   cp src/Controls/UnsavedChangesControl.php your-project/src/Forms/
   ```

### With Asset Management

If using Webpack, Vite, or similar:

```javascript
import UnsavedChangesTracker from './unsaved-changes.js';

const tracker = new UnsavedChangesTracker('#myForm', {
    message: 'You have unsaved changes!'
});
```

## Potential Improvements

This is a PoC. For production use, consider:

- [ ] **NPM package** - Publish as a standalone package
- [ ] **TypeScript version** - Add type definitions
- [ ] **Framework integrations** - React, Vue, Angular adapters
- [ ] **Advanced options** - Exclude specific fields, custom validators
- [ ] **Callbacks** - `onDirty`, `onChange`, `onBeforeUnload` hooks
- [ ] **AJAX forms** - Integration with AJAX submissions
- [ ] **Multiple forms** - Support tracking multiple forms independently
- [ ] **localStorage** - Auto-save drafts to localStorage
- [ ] **Visual indicator** - Show a visual "unsaved changes" badge
- [ ] **Unit tests** - Automated testing suite
- [ ] **Minified version** - Production-ready minified build

## Known Limitations

1. **Custom message ignored** - Modern browsers show a generic message for security
2. **No mobile support** - Mobile browsers don't support `beforeunload` reliably
3. **SPA routing** - Doesn't work with client-side routing (React Router, Vue Router, etc.) without additional setup
4. **File inputs** - File input changes are tracked but the actual file isn't stored
5. **Dynamic forms** - Fields added after initialization aren't automatically tracked

## Security Considerations

- ✅ **No data storage** - Doesn't store or transmit form data
- ✅ **Client-side only** - All tracking happens in the browser
- ✅ **No external dependencies** - No third-party scripts loaded
- ✅ **XSS safe** - Custom messages are not rendered as HTML

## License

MIT (same as contributte/forms)

## Contributing

This is a proof of concept for issue #2. Feedback and suggestions are welcome!

## Related Resources

- [MDN: beforeunload event](https://developer.mozilla.org/en-US/docs/Web/API/Window/beforeunload_event)
- [Nette Forms Documentation](https://doc.nette.org/en/forms)
- [Contributte Forms Documentation](https://contributte.org/packages/contributte/forms.html)
