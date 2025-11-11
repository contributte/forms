/**
 * Unsaved Form Changes Tracker
 *
 * Monitors form changes and displays a confirmation dialog when the user
 * attempts to navigate away from the page with unsaved changes.
 *
 * @author Contributte Forms
 * @license MIT
 */
(function() {
    'use strict';

    /**
     * UnsavedChangesTracker constructor
     * @param {HTMLFormElement|string} form - Form element or selector
     * @param {Object} options - Configuration options
     */
    function UnsavedChangesTracker(form, options) {
        // Default options
        this.options = Object.assign({
            message: 'You have unsaved changes. Are you sure you want to leave?',
            trackInputs: true,
            trackTextareas: true,
            trackSelects: true,
            trackCheckboxes: true,
            trackRadios: true,
            excludeSelectors: [], // Array of selectors to exclude from tracking
            resetOnSubmit: true,
            debug: false
        }, options || {});

        // Get form element
        if (typeof form === 'string') {
            this.form = document.querySelector(form);
        } else {
            this.form = form;
        }

        if (!this.form) {
            console.error('UnsavedChangesTracker: Form not found');
            return;
        }

        this.isDirty = false;
        this.initialValues = {};

        this.init();
    }

    /**
     * Initialize the tracker
     */
    UnsavedChangesTracker.prototype.init = function() {
        this.log('Initializing tracker for form', this.form);

        // Store initial values
        this.storeInitialValues();

        // Attach change listeners
        this.attachListeners();

        // Attach beforeunload handler
        this.attachBeforeUnloadHandler();
    };

    /**
     * Store initial form values
     */
    UnsavedChangesTracker.prototype.storeInitialValues = function() {
        var self = this;
        var elements = this.getTrackedElements();

        elements.forEach(function(element) {
            var key = self.getElementKey(element);
            self.initialValues[key] = self.getElementValue(element);
        });

        this.log('Initial values stored:', this.initialValues);
    };

    /**
     * Get all tracked form elements
     */
    UnsavedChangesTracker.prototype.getTrackedElements = function() {
        var selectors = [];

        if (this.options.trackInputs) {
            selectors.push('input[type="text"]', 'input[type="email"]', 'input[type="password"]',
                          'input[type="number"]', 'input[type="tel"]', 'input[type="url"]',
                          'input[type="date"]', 'input[type="datetime-local"]', 'input[type="time"]',
                          'input[type="search"]', 'input[type="color"]', 'input[type="hidden"]');
        }

        if (this.options.trackTextareas) {
            selectors.push('textarea');
        }

        if (this.options.trackSelects) {
            selectors.push('select');
        }

        if (this.options.trackCheckboxes) {
            selectors.push('input[type="checkbox"]');
        }

        if (this.options.trackRadios) {
            selectors.push('input[type="radio"]');
        }

        var elements = this.form.querySelectorAll(selectors.join(','));
        var filtered = [];

        // Filter out excluded elements
        for (var i = 0; i < elements.length; i++) {
            var element = elements[i];
            var excluded = false;

            for (var j = 0; j < this.options.excludeSelectors.length; j++) {
                if (element.matches(this.options.excludeSelectors[j])) {
                    excluded = true;
                    break;
                }
            }

            if (!excluded) {
                filtered.push(element);
            }
        }

        return filtered;
    };

    /**
     * Get unique key for an element
     */
    UnsavedChangesTracker.prototype.getElementKey = function(element) {
        return element.name || element.id || 'element_' + Array.prototype.indexOf.call(this.form.elements, element);
    };

    /**
     * Get element value
     */
    UnsavedChangesTracker.prototype.getElementValue = function(element) {
        if (element.type === 'checkbox') {
            return element.checked;
        } else if (element.type === 'radio') {
            var radios = this.form.querySelectorAll('input[name="' + element.name + '"]');
            for (var i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    return radios[i].value;
                }
            }
            return null;
        } else {
            return element.value;
        }
    };

    /**
     * Attach change listeners to form elements
     */
    UnsavedChangesTracker.prototype.attachListeners = function() {
        var self = this;
        var elements = this.getTrackedElements();

        elements.forEach(function(element) {
            // Use 'input' event for real-time tracking on text inputs
            if (element.tagName === 'INPUT' &&
                ['text', 'email', 'password', 'number', 'tel', 'url', 'search'].indexOf(element.type) !== -1) {
                element.addEventListener('input', function() {
                    self.checkChanges();
                });
            }

            // Use 'change' event for other elements
            element.addEventListener('change', function() {
                self.checkChanges();
            });
        });

        // Handle form submit
        if (this.options.resetOnSubmit) {
            this.form.addEventListener('submit', function() {
                self.log('Form submitted, resetting dirty flag');
                self.reset();
            });
        }

        this.log('Attached listeners to', elements.length, 'elements');
    };

    /**
     * Check if form has changes
     */
    UnsavedChangesTracker.prototype.checkChanges = function() {
        var self = this;
        var elements = this.getTrackedElements();
        var hasChanges = false;

        elements.forEach(function(element) {
            var key = self.getElementKey(element);
            var currentValue = self.getElementValue(element);
            var initialValue = self.initialValues[key];

            if (currentValue !== initialValue) {
                hasChanges = true;
            }
        });

        this.isDirty = hasChanges;
        this.log('Form dirty status:', this.isDirty);

        return hasChanges;
    };

    /**
     * Attach beforeunload handler
     */
    UnsavedChangesTracker.prototype.attachBeforeUnloadHandler = function() {
        var self = this;

        window.addEventListener('beforeunload', function(event) {
            if (self.isDirty) {
                self.log('Preventing navigation - unsaved changes detected');

                // Modern browsers ignore custom messages, but we set it anyway
                event.preventDefault();
                event.returnValue = self.options.message;
                return self.options.message;
            }
        });
    };

    /**
     * Reset the tracker (mark as clean)
     */
    UnsavedChangesTracker.prototype.reset = function() {
        this.isDirty = false;
        this.storeInitialValues();
        this.log('Tracker reset');
    };

    /**
     * Mark form as dirty
     */
    UnsavedChangesTracker.prototype.setDirty = function(dirty) {
        this.isDirty = dirty !== false;
        this.log('Dirty flag manually set to:', this.isDirty);
    };

    /**
     * Check if form is dirty
     */
    UnsavedChangesTracker.prototype.isDirtyForm = function() {
        return this.isDirty;
    };

    /**
     * Destroy the tracker
     */
    UnsavedChangesTracker.prototype.destroy = function() {
        this.isDirty = false;
        this.initialValues = {};
        this.log('Tracker destroyed');
    };

    /**
     * Debug logging
     */
    UnsavedChangesTracker.prototype.log = function() {
        if (this.options.debug) {
            console.log.apply(console, ['[UnsavedChangesTracker]'].concat(Array.prototype.slice.call(arguments)));
        }
    };

    // Export to global scope
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = UnsavedChangesTracker;
    } else {
        window.UnsavedChangesTracker = UnsavedChangesTracker;
    }

    // Auto-initialize forms with data-unsaved-warning attribute
    if (typeof document !== 'undefined') {
        document.addEventListener('DOMContentLoaded', function() {
            var forms = document.querySelectorAll('form[data-unsaved-warning]');

            forms.forEach(function(form) {
                var message = form.getAttribute('data-unsaved-warning');
                var options = {
                    debug: form.hasAttribute('data-unsaved-debug')
                };

                if (message && message !== 'true') {
                    options.message = message;
                }

                new UnsavedChangesTracker(form, options);
            });
        });
    }
})();
