/**
 * Settings Validation for Fluid Font Forge v5.1.2
 *
 * Validates settings inputs and displays error modals when values are out of range.
 * Automatically corrects invalid values after 10 seconds.
 *
 * @package FluidFontForge
 * @since 5.1.2
 */

(function () {
  "use strict";

  // Validation limits
  const LIMITS = {
    MIN_ROOT_MIN: 8,
    MIN_ROOT_MAX: 32,
    MAX_ROOT_MAX: 80,
    MIN_VIEWPORT_MIN: 200,
    MIN_VIEWPORT_MAX: 992,
    MAX_VIEWPORT_MAX: 1920,
  };

  /**
   * Initialize validation event listeners
   */
  function initValidation() {
    // Wait for DOM to be ready
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", bindValidationEvents);
    } else {
      bindValidationEvents();
    }
  }

  /**
   * Bind blur event listeners to settings inputs
   */
  function bindValidationEvents() {
    const minRootInput = document.getElementById("min-root-size");
    const maxRootInput = document.getElementById("max-root-size");
    const minViewportInput = document.getElementById("min-viewport");
    const maxViewportInput = document.getElementById("max-viewport");

    if (minRootInput) {
      minRootInput.addEventListener("blur", (e) =>
        validateInput(e.target, "min-root")
      );
    }
    if (maxRootInput) {
      maxRootInput.addEventListener("blur", (e) =>
        validateInput(e.target, "max-root")
      );
    }
    if (minViewportInput) {
      minViewportInput.addEventListener("blur", (e) =>
        validateInput(e.target, "min-viewport")
      );
    }
    if (maxViewportInput) {
      maxViewportInput.addEventListener("blur", (e) =>
        validateInput(e.target, "max-viewport")
      );
    }

    // Auto-select text on focus (v5.1.2)
    const autoSelectInputs = [
      minRootInput,
      maxRootInput,
      minViewportInput,
      maxViewportInput,
    ];

    autoSelectInputs.forEach((input) => {
      if (input) {
        input.addEventListener("focus", (e) => {
          e.target.select();
        });
      }
    });
  }

  /**
   * Validate input value and show error if invalid
   *
   * @param {HTMLInputElement} input - The input element to validate
   * @param {string} type - Type of input (min-root, max-root, min-viewport, max-viewport)
   */
  function validateInput(input, type) {
    const value = parseFloat(input.value);
    if (isNaN(value)) return;

    let isValid = true;
    let errorMessage = "";
    let correctedValue = value;

    // Get current values for dynamic bounds
    const minRootSize =
      parseFloat(document.getElementById("min-root-size")?.value) || 12;
    const minViewport =
      parseFloat(document.getElementById("min-viewport")?.value) || 375;

    switch (type) {
      case "min-root":
        if (value < LIMITS.MIN_ROOT_MIN) {
          isValid = false;
          errorMessage = `Min Viewport Font Size must be at least ${LIMITS.MIN_ROOT_MIN}px`;
          correctedValue = LIMITS.MIN_ROOT_MIN;
        } else if (value > LIMITS.MIN_ROOT_MAX) {
          isValid = false;
          errorMessage = `Min Viewport Font Size must not exceed ${LIMITS.MIN_ROOT_MAX}px`;
          correctedValue = LIMITS.MIN_ROOT_MAX;
        }
        break;

      case "max-root":
        if (value < minRootSize) {
          isValid = false;
          errorMessage = `Max Viewport Font Size must be at least ${minRootSize}px (current Min Viewport Font Size)`;
          correctedValue = minRootSize;
        } else if (value > LIMITS.MAX_ROOT_MAX) {
          isValid = false;
          errorMessage = `Max Viewport Font Size must not exceed ${LIMITS.MAX_ROOT_MAX}px`;
          correctedValue = LIMITS.MAX_ROOT_MAX;
        }
        break;

      case "min-viewport":
        if (value < LIMITS.MIN_VIEWPORT_MIN) {
          isValid = false;
          errorMessage = `Min Viewport Width must be at least ${LIMITS.MIN_VIEWPORT_MIN}px`;
          correctedValue = LIMITS.MIN_VIEWPORT_MIN;
        } else if (value > LIMITS.MIN_VIEWPORT_MAX) {
          isValid = false;
          errorMessage = `Min Viewport Width must not exceed ${LIMITS.MIN_VIEWPORT_MAX}px`;
          correctedValue = LIMITS.MIN_VIEWPORT_MAX;
        }
        break;

      case "max-viewport":
        if (value < minViewport) {
          isValid = false;
          errorMessage = `Max Viewport Width must be at least ${minViewport}px (current Min Viewport Width)`;
          correctedValue = minViewport;
        } else if (value > LIMITS.MAX_VIEWPORT_MAX) {
          isValid = false;
          errorMessage = `Max Viewport Width must not exceed ${LIMITS.MAX_VIEWPORT_MAX}px`;
          correctedValue = LIMITS.MAX_VIEWPORT_MAX;
        }
        break;
    }

    if (!isValid) {
      showValidationError(errorMessage, input, correctedValue);
    }
  }

  /**
   * Show validation error modal
   *
   * @param {string} message - Error message to display
   * @param {HTMLInputElement} input - The input element that failed validation
   * @param {number} correctedValue - The corrected value to apply after 3 seconds
   */
  function showValidationError(message, input, correctedValue) {
    // Get input position
    const rect = input.getBoundingClientRect();
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

    // Create modal positioned below input
    const modal = document.createElement("div");
    modal.className = "fff-validation-error-modal";
    modal.style.cssText = `
      position: absolute;
      top: ${rect.bottom + scrollTop + 8}px;
      left: ${rect.left + scrollLeft}px;
      width: ${rect.width}px;
      background: #dc2626;
      color: white;
      padding: 12px 16px;
      border-radius: 6px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      z-index: 10000;
      font-family: Inter, sans-serif;
      font-size: 14px;
      font-weight: 600;
      text-align: left;
      animation: fadeIn 0.2s ease-in-out;
      box-sizing: border-box;
    `;
    modal.textContent = message;
    document.body.appendChild(modal);

    // Auto-correct after 3 seconds
    setTimeout(() => {
      input.value = correctedValue;

      // Trigger input event to update all calculations
      const event = new Event('input', { bubbles: true });
      input.dispatchEvent(event);

      modal.style.animation = "fadeOut 0.2s ease-in-out";
      setTimeout(() => {
        modal.remove();
      }, 200);
    }, 3000);
  }

  // Initialize validation
  initValidation();
})();
