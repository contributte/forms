# Forms Design

Contributte Forms renders Nette forms as Bootstrap 3, 4 or 5 markup and adds date and time inputs and two captchas
to them. Developers pick a renderer and the controls; the visitors of their site fill in the form.

## Principles

- **Markup only, no assets.** The package ships no CSS, JavaScript, fonts or icons. The host page loads Bootstrap and
  any date picker; the renderers only choose elements and classes.
- **One renderer per Bootstrap major and layout.** `Bootstrap{3,4,5}{Vertical,Horizontal,Inline}Renderer` each target
  the class names of one Bootstrap major. Don't mix majors in one class.
- **Renderers are `DefaultFormRenderer` with a `$wrappers` array.** Layout lives in `$wrappers`; `render()` only adds
  classes to control prototypes. Apps override `$wrappers` in a subclass, so keys are public.
- **Controls degrade to native HTML.** Date and time inputs render native `date`, `time` and `datetime-local` inputs
  unless a custom format is set; then they render `type="text"` with `data-*` attributes for a JS picker.

## Inventory

- Renderers: `src/Rendering/Bootstrap3*Renderer.php`, `Bootstrap4*Renderer.php`, `Bootstrap5*Renderer.php`
- Shared column handling for horizontal layouts: `src/Rendering/AbstractBootstrapHorizontalRenderer.php`
- Button and class helpers: `src/Rendering/Helpers.php`
- Date and time inputs: `src/Controls/DateTime/DateTimeInput.php`, `DateInput.php`, `TimeInput.php`, shared markup in
  `AbstractDateTimeInput::getControl()`
- Submit-timing guard (hidden field with a timestamp): `src/Controls/ProtectionFastInput.php`
- Wordcha captcha (text question and hidden hash): `src/Captcha/Wordcha/Form/WordchaContainer.php`
- Seznam captcha (image, code input, hidden hash): `src/Captcha/Seznam/Form/SeznamCaptchaContainer.php`

## Layout

- Vertical: label above control in `div.form-group` (Bootstrap 3, 4) or `div.mb-3` (Bootstrap 5).
- Horizontal: `div.form-group.row` or `div.mb-3.row` with a label column and a control column. The default split is
  3 / 9 columns (`$colsLabel`, `$colsControl`), changed with `setColumns()`. Bootstrap 3 and 4 use `col-sm-*`,
  Bootstrap 5 uses `col-*` without a breakpoint.
- Inline: `form-inline` on the form with `div.form-group` pairs (Bootstrap 3, 4), or `d-inline-flex gap-2` on the
  form with no pair container (Bootstrap 5). Inline text controls get `mx-sm-3` (Bootstrap 4) or `me-3` (Bootstrap 5).
- Groups render as `fieldset` with a `legend`, except in the Bootstrap 5 inline renderer.

## Typography

- Inherits the host page and Bootstrap. No renderer or control sets a font, size or weight.

## Colors and Tokens

- No colors of our own. The meaning comes from Bootstrap classes the renderers add:
- The only button in a form gets `btn btn-primary`; with more buttons each gets `btn btn-default` (Bootstrap 3, 4)
  or `btn btn-secondary` (Bootstrap 5). A button that already has a `btn*` class is left alone.
- Form errors render in `div.alert.alert-danger`.
- Control hooks for app CSS and JS: `datetime-input`, `date-input`, `time-input`, `captcha-image`,
  `seznam-captcha-image`, `captcha-input`, `seznam-captcha-input`.

## States

- Error, Bootstrap 3: the pair gets `has-error`, messages render in `span.help-block`.
- Error, Bootstrap 4: messages render in `span.form-text`; controls get no `is-invalid`, so errors are not red.
- Error, Bootstrap 5: controls get `is-invalid`, messages render in `div.invalid-feedback`. Checkbox and radio
  containers also get `is-invalid` so the feedback shows.
- Required: the pair and the control get the `required` class; no asterisk is added.
- Empty, loading and success states are not rendered by this package; Nette Forms and the app handle them.
- Captcha failure: `verify()` returns `false`; the app adds the error message itself.

## Accessibility

- Labels are real `label for` elements from Nette Forms; checkboxes are wrapped in their label.
- Native date and time inputs use the browser picker and keyboard support.
- Known gaps: the Seznam captcha image has no `alt` text and no audio alternative. Custom-format date inputs rely on
  the app's JS picker for keyboard use. The Bootstrap 4 horizontal submit row has no empty label column, so the
  button is not aligned with the controls.

## Dark Mode

- Not handled here; the markup follows the host page Bootstrap theme (`data-bs-theme` in Bootstrap 5.3).

## Responsive

- Bootstrap 3 and 4 horizontal forms stack below the `sm` breakpoint (768 px in Bootstrap 3, 576 px in Bootstrap 4).
- Bootstrap 5 horizontal forms keep the label and control side by side at every width.

## Screenshots

- `.docs/wordcha.png` and `.docs/seznam-captcha.png` show the two captchas; `.docs/README.md` embeds them. They sit in
  `.docs/` rather than `.docs/assets/`. Take a new screenshot by hand when the captcha markup changes.
- The renderers have no screenshots; `tests/Cases/Rendering/expected/*.html` is their reference output.

## Changing the UI

- `$wrappers` keys and values are public API; apps subclass the renderers and override them. Treat a changed
  default class as a breaking change for Bootstrap 3 and 4.
- Class names and `data-format`, `data-value`, `data-min`, `data-max`, `data-settings` attributes on date inputs are
  read by app JavaScript. Don't rename them.
- Bootstrap 5 renderers put `form-control` on selects; Bootstrap 5 styles selects with `form-select`. Fixing it
  changes the output for existing apps, so list it in the release notes.
- Update `tests/Cases/Rendering/expected/*.html` with every Bootstrap 3 or 4 markup change. Bootstrap 5 renderers
  have no expected files; add one when you touch them.

## Checklist

- [ ] Expected HTML files updated and `make tests` passes
- [ ] No CSS, JS or icon dependency added to the package
- [ ] Class names and `data-*` attributes read by apps are unchanged, or the change is in the release notes
- [ ] The form still submits and validates on the server without JavaScript
- [ ] `.docs/README.md` updated when a control or renderer option changes
