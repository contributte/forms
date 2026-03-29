<?php

/**
 * Example: Using Unsaved Changes Warning with Nette Forms
 *
 * This example demonstrates how to integrate the unsaved changes
 * warning feature with Nette Forms and Bootstrap renderers.
 */

require __DIR__ . '/../vendor/autoload.php';

use Nette\Forms\Form;
use Contributte\Forms\Controls\UnsavedChangesControl;
use Contributte\Forms\Rendering\Bootstrap5VerticalRenderer;

// Create a new form
$form = new Form();

// Add form fields
$form->addText('name', 'Name:')
    ->setRequired('Please enter your name');

$form->addEmail('email', 'Email:')
    ->setRequired('Please enter your email');

$form->addTextArea('message', 'Message:')
    ->setRequired('Please enter a message')
    ->setAttribute('rows', 5);

$form->addSelect('priority', 'Priority:', [
    'low' => 'Low',
    'medium' => 'Medium',
    'high' => 'High',
])
    ->setPrompt('Select priority');

$form->addCheckbox('subscribe', 'Subscribe to newsletter');

$form->addSubmit('submit', 'Submit');

// Enable unsaved changes warning - Example 1: Simple
// UnsavedChangesControl::enable($form);

// Enable unsaved changes warning - Example 2: With custom message
UnsavedChangesControl::enable($form, 'You have unsaved changes. Are you sure you want to leave?');

// Enable unsaved changes warning - Example 3: With debug mode
// UnsavedChangesControl::enable($form, 'Custom warning!', debug: true);

// Enable unsaved changes warning - Example 4: With custom options
// UnsavedChangesControl::enableWithOptions($form, [
//     'message' => 'You have unsaved changes!',
//     'debug' => true,
//     'trackCheckboxes' => true,
//     'trackRadios' => true,
// ]);

// Use Bootstrap 5 renderer
$form->setRenderer(new Bootstrap5VerticalRenderer());

// Handle form submission
if ($form->isSuccess()) {
    $values = $form->getValues();

    // Process form data...
    echo '<div class="alert alert-success">Form submitted successfully!</div>';
    echo '<pre>' . print_r($values, true) . '</pre>';
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nette Forms - Unsaved Changes Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 40px 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .alert {
            margin-bottom: 20px;
        }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Nette Forms + Unsaved Changes Warning</h1>

        <div class="alert alert-info">
            <strong>Test Instructions:</strong>
            <ol class="mb-0">
                <li>Fill out the form below</li>
                <li>Try to close the tab or navigate away</li>
                <li>You'll see a confirmation dialog about unsaved changes</li>
                <li>Click "Submit" to save and clear the warning</li>
            </ol>
        </div>

        <div class="alert alert-warning">
            <strong>Note:</strong> This is a demonstration. The form is rendered with Bootstrap 5
            and the unsaved changes warning is enabled using
            <code>UnsavedChangesControl::enable($form)</code>.
        </div>

        <?php echo $form; ?>

        <hr class="my-4">

        <h3>Code Example</h3>
        <pre><code>&lt;?php
use Nette\Forms\Form;
use Contributte\Forms\Controls\UnsavedChangesControl;
use Contributte\Forms\Rendering\Bootstrap5VerticalRenderer;

$form = new Form();
$form->addText('name', 'Name:');
$form->addEmail('email', 'Email:');
$form->addSubmit('submit', 'Submit');

// Enable unsaved changes warning
UnsavedChangesControl::enable($form, 'Custom message here!');

// Use Bootstrap renderer
$form->setRenderer(new Bootstrap5VerticalRenderer());

echo $form;
?&gt;</code></pre>
    </div>

    <!-- Include the unsaved changes tracker script -->
    <script src="../assets/unsaved-changes.js"></script>
</body>
</html>
