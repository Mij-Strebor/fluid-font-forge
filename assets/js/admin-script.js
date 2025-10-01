/**
 * Fluid Font Forge - Admin Interface Script
 *
 * Advanced fluid typography calculator with interactive controls,
 * real-time CSS clamp() generation, and responsive font previews.
 *
 * @package FluidFontForge
 * @version 4.0.3
 * @since 1.0.0
 *
 * Dependencies:
 * - WordPress wp-util (jQuery, AJAX utilities)
 * - Tailwind CSS (loaded via CDN)
 *
 * Features:
 * - Interactive font size calculator with mathematical scaling
 * - Real-time preview with synchronized hover effects
 * - Drag-and-drop table row reordering
 * - Modal editing system with validation
 * - Copy-to-clipboard functionality with visual feedback
 * - Autosave system with status indicators
 * - Multi-tab interface (Classes, Variables, Tags, Tailwind)
 */

/* ==========================================================================
   CUSTOM EVENT CONSTANTS
   ========================================================================== */

/**
 * Custom event names for inter-component communication
 *
 * @constant {Object} FLUIFOFO_EVENTS
 */
const FLUIFOFO_EVENTS = {
  TAB_CHANGED: "fluifofo:tab:changed",
  DATA_UPDATED: "fluifofo:data:updated",
  SETTINGS_CHANGED: "fluifofo:settings:changed",
  CORE_READY: "fluifofo:core:ready",
  ADVANCED_READY: "fluifofo:advanced:ready",
  SIZE_SELECTED: "fluifofo:size:selected",
  CALCULATION_COMPLETE: "fluifofo:calculation:complete",
};

/* ==========================================================================
   SHARED UTILITIES
   ========================================================================== */

/**
 * Font Forge Utility Functions
 *
 * Provides shared utility functions for data access across components.
 *
 * @namespace FontForgeUtils
 */
const FontForgeUtils = {
  /**
   * Get current sizes based on active tab
   *
   * @param {string|null} activeTab - Tab identifier (class, vars, tag, tailwind)
   * @param {Object|null} fontClampAdvanced - Advanced features instance
   * @returns {Array} Array of size objects for the active tab
   */
  getCurrentSizes(activeTab = null, fontClampAdvanced = null) {
    return window.FontForgeData?.getSizes(activeTab) || [];
  },
};

window.FontForgeUtils = FontForgeUtils;

/**
 * Check if unified system is available
 *
 * @returns {Object|undefined} FontForgeData object if available
 */
function checkUnifiedSystem() {
  return window.FontForgeData;
}

/* ==========================================================================
   CORE INTERFACE CONTROLLER
   ========================================================================== */

/**
 * Enhanced Core Interface Controller
 *
 * Manages tabs, unit selection, and coordination with advanced features.
 * Handles expandable sections, loading sequence, and visual state synchronization.
 *
 * @class FontClampEnhancedCoreInterface
 * @since 1.0.0
 */
class FontClampEnhancedCoreInterface {
  /**
   * Initialize core interface
   *
   * Sets up data, elements, events, and triggers the loading sequence.
   */
  constructor() {
    this.initializeData();
    this.cacheElements();
    this.bindBasicEvents();
    this.bindEnhancedEvents();
    this.bindToggleEvents();
    this.triggerSegmentHooks();
    this.syncVisualState();

    setTimeout(() => {
      this.updateBaseValueDropdown(this.activeTab);
    }, 100);

    this.initLoadingSequence();
    this.loadingSteps.coreReady = true;
  }

  /* ========================================================================
     INITIALIZATION & DATA METHODS
     ======================================================================== */

  /**
   * Initialize data from localized script
   *
   * Extracts settings and size data from wp_localize_script output.
   *
   * @private
   */
  initializeData() {
    const data = window.fluifofoAjax?.data || {};

    this.settings = data.settings || {};
    this.classSizes = data.classSizes || [];
    this.variableSizes = data.variableSizes || [];
    this.tagSizes = data.tagSizes || [];
    this.tailwindSizes = data.tailwindSizes || [];

    this.activeTab = this.settings.activeTab || "class";
    this.unitType = this.settings.unitType || "px";
  }

  /**
   * Cache frequently accessed DOM elements
   *
   * Improves performance by reducing repeated DOM queries and provides
   * centralized element references for easier maintenance.
   *
   * @private
   */
  cacheElements() {
    this.elements = {
      classTab: document.getElementById("class-tab"),
      varsTab: document.getElementById("vars-tab"),
      tagTab: document.getElementById("tag-tab"),
      pxTab: document.getElementById("px-tab"),
      remTab: document.getElementById("rem-tab"),
      tableTitle: document.getElementById("table-title"),
      selectedCodeTitle: document.getElementById("selected-code-title"),
      generatedCodeTitle: document.getElementById("generated-code-title"),
      previewMinContainer: document.getElementById("preview-min-container"),
      previewMaxContainer: document.getElementById("preview-max-container"),
      sizesTableWrapper: document.getElementById("sizes-table-wrapper"),
      classCode: document.getElementById("class-code"),
      generatedCode: document.getElementById("generated-code"),
      minViewportDisplay: document.getElementById("min-viewport-display"),
      maxViewportDisplay: document.getElementById("max-viewport-display"),
    };
  }

  /* ========================================================================
     LOADING SEQUENCE & INTERFACE REVEAL
     ======================================================================== */

  /**
   * Initialize loading sequence and interface reveal
   *
   * Tracks component readiness and reveals interface when all components
   * are ready. Includes timeout fallback to prevent indefinite loading.
   *
   * @private
   */
  initLoadingSequence() {
    this.loadingSteps = {
      coreReady: false,
      advancedReady: false,
      contentPopulated: false,
    };

    window.addEventListener("fluifofo:advanced:ready", () => {
      this.loadingSteps.advancedReady = true;
      this.checkAndRevealInterface();
    });

    window.addEventListener("fluifofo:data:updated", () => {
      this.loadingSteps.contentPopulated = true;
      this.checkAndRevealInterface();
    });

    setTimeout(() => {
      if (!this.isInterfaceRevealed()) {
        this.revealInterface();
      }
    }, 5000);
  }

  /**
   * Check if all loading steps complete and reveal interface
   *
   * @private
   */
  checkAndRevealInterface() {
    if (this.loadingSteps.advancedReady && this.loadingSteps.contentPopulated) {
      setTimeout(() => this.revealInterface(), 300);
    }
  }

  /**
   * Reveal main interface and hide loading screen
   *
   * @private
   */
  revealInterface() {
    if (this.isInterfaceRevealed()) return;

    const loadingScreen = document.getElementById("fcc-loading-screen");
    if (loadingScreen) {
      loadingScreen.classList.add("hidden");
    }

    const mainContainer = document.getElementById("fcc-main-container");
    if (mainContainer) {
      mainContainer.classList.add("ready");
    }

    const autosaveIcon = document.getElementById("autosave-icon");
    const autosaveText = document.getElementById("autosave-text");
    if (autosaveIcon && autosaveText) {
      autosaveIcon.textContent = "💾";
      autosaveText.textContent = "Ready";
    }

    const ariaRegion = document.createElement("div");
    ariaRegion.id = "fcc-announcements";
    ariaRegion.setAttribute("aria-live", "polite");
    ariaRegion.setAttribute("aria-atomic", "false");
    ariaRegion.style.cssText =
      "position: absolute; left: -10000px; width: 1px; height: 1px; overflow: hidden;";
    document.body.appendChild(ariaRegion);
  }

  /**
   * Check if interface is already revealed
   *
   * @private
   * @returns {boolean} True if interface is visible
   */
  isInterfaceRevealed() {
    const mainContainer = document.getElementById("fcc-main-container");
    return mainContainer && mainContainer.classList.contains("ready");
  }

  /* ========================================================================
     EVENT BINDING & SETUP METHODS
     ======================================================================== */

  /**
   * Bind basic event listeners for tabs and unit switching
   *
   * @private
   */
  bindBasicEvents() {
    this.elements.classTab?.addEventListener("click", () =>
      this.switchTab("class")
    );
    this.elements.varsTab?.addEventListener("click", () =>
      this.switchTab("vars")
    );
    this.elements.tagTab?.addEventListener("click", () =>
      this.switchTab("tag")
    );

    const tailwindTab = document.getElementById("tailwind-tab");
    if (tailwindTab) {
      tailwindTab.addEventListener("click", () => this.switchTab("tailwind"));
    }

    this.elements.pxTab?.addEventListener("click", () =>
      this.switchUnitType("px")
    );
    this.elements.remTab?.addEventListener("click", () =>
      this.switchUnitType("rem")
    );

    document
      .getElementById("min-root-size")
      ?.addEventListener("input", () => this.triggerCalculation());
    document
      .getElementById("max-root-size")
      ?.addEventListener("input", () => this.triggerCalculation());
    document
      .getElementById("min-viewport")
      ?.addEventListener("input", () => this.triggerCalculation());
    document
      .getElementById("max-viewport")
      ?.addEventListener("input", () => this.triggerCalculation());
    document
      .getElementById("min-scale")
      ?.addEventListener("change", () => this.triggerCalculation());
    document
      .getElementById("max-scale")
      ?.addEventListener("change", () => this.triggerCalculation());
  }

  /**
   * Bind enhanced event listeners for real-time display updates
   *
   * @private
   */
  bindEnhancedEvents() {
    document.getElementById("min-viewport")?.addEventListener("input", (e) => {
      if (this.elements.minViewportDisplay) {
        this.elements.minViewportDisplay.textContent = e.target.value + "px";
      }
    });

    document.getElementById("max-viewport")?.addEventListener("input", (e) => {
      if (this.elements.maxViewportDisplay) {
        this.elements.maxViewportDisplay.textContent = e.target.value + "px";
      }
    });

    document
      .getElementById("viewport-updateSampleText(viewportSize)")
      ?.addEventListener("input", (e) => {
        this.updateSampleText(e.target.value);
      });

    if (this.elements.baseValueSelect) {
      this.elements.baseValueSelect.addEventListener("change", () => {
        this.updateSampleTextFromSettings();
      });
    }
  }

  /**
   * Bind toggle events for expandable sections
   *
   * @private
   */
  bindToggleEvents() {
    setTimeout(() => {
      const infoToggle = document.querySelector(
        '[data-toggle-target="info-content"]'
      );
      const aboutToggle = document.querySelector(
        '[data-toggle-target="about-content"]'
      );

      if (infoToggle) {
        infoToggle.addEventListener("click", (e) => {
          e.preventDefault();
          this.handleToggle(infoToggle, "info-content");
        });
      }

      if (aboutToggle) {
        aboutToggle.addEventListener("click", (e) => {
          e.preventDefault();
          this.handleToggle(aboutToggle, "about-content");
        });
      }
    }, 1000);
  }

  /**
   * Handle toggle action for expandable sections
   *
   * @private
   * @param {HTMLElement} button - Toggle button element
   * @param {string} targetId - ID of content element to toggle
   */
  handleToggle(button, targetId) {
    const content = document.getElementById(targetId);

    if (!content || !button) {
      return;
    }

    const isExpanded = content.classList.contains("expanded");

    if (isExpanded) {
      content.classList.remove("expanded");
      button.classList.remove("expanded");
    } else {
      content.classList.add("expanded");
      button.classList.add("expanded");
    }

    const icon = button.querySelector(".fcc-toggle-icon");
    if (icon) {
      if (isExpanded) {
        icon.style.transform = "rotate(0deg)";
      } else {
        icon.style.transform = "rotate(180deg)";
      }
    }
  }

  /**
   * Trigger calculation in advanced features
   *
   * @private
   */
  triggerCalculation() {
    window.dispatchEvent(new CustomEvent(FLUIFOFO_EVENTS.SETTINGS_CHANGED));
    if (window.fontClampAdvanced && window.fontClampAdvanced.calculateSizes) {
      window.fontClampAdvanced.calculateSizes();
    }
  }

  /* ========================================================================
     TAB & UNIT MANAGEMENT METHODS
     ======================================================================== */

  /**
   * Switch active tab and update interface
   *
   * @param {string} tabName - Tab identifier (class, vars, tag, tailwind)
   */
  switchTab(tabName) {
    this.activeTab = tabName;

    document.querySelectorAll("[data-tab]").forEach((tab) => {
      tab.classList.remove("active");
    });
    document.querySelector(`[data-tab="${tabName}"]`)?.classList.add("active");

    this.elements.tableTitle.textContent = TabDataUtils.getTableTitle(tabName);
    this.elements.selectedCodeTitle.textContent =
      TabDataUtils.getSelectedCSSTitle(tabName);
    this.elements.generatedCodeTitle.textContent =
      TabDataUtils.getGeneratedCSSTitle(tabName);

    if (typeof this.updateBaseValueDropdown === "function") {
      this.updateBaseValueDropdown(tabName);
    }

    this.triggerHook("tab:changed", {
      activeTab: tabName,
    });
  }

  /**
   * Update base value dropdown options based on active tab
   *
   * @param {string} tabName - Tab identifier
   */
  updateBaseValueDropdown(tabName) {
    const baseValueSelect = document.getElementById("base-value");
    if (!baseValueSelect) {
      return;
    }

    baseValueSelect.innerHTML = "";

    const propertyName = TabDataUtils.getPropertyName(tabName);
    const defaultValue = TabDataUtils.getBaseDefaultValue(tabName);

    let currentSizes;
    if (tabName === "tailwind") {
      currentSizes = FontForgeUtils.getCurrentSizes(
        "tailwind",
        window.fontClampAdvanced
      );
    } else {
      if (window.FontForgeData) {
        const allSizes = window.FontForgeData.getSizes(tabName, {
          useDefaults: false,
        });
        currentSizes = allSizes.filter((size) => {
          const name = window.FontForgeData.getSizeDisplayName(size, tabName);
          return (
            !name.startsWith("custom-") && !name.startsWith("--fs-custom-")
          );
        });
      } else {
        const allSizes = TabDataUtils.getDataForTab(tabName, {
          classSizes: this.classSizes,
          variableSizes: this.variableSizes,
          tagSizes: this.tagSizes,
        });
        currentSizes = allSizes.filter((size) => {
          const name = TabDataUtils.getSizeDisplayName(size, tabName);
          return (
            !name.startsWith("custom-") && !name.startsWith("--fs-custom-")
          );
        });
      }
    }

    if (!currentSizes || currentSizes.length === 0) {
      return;
    }

    let defaultFound = false;
    currentSizes.forEach((size, index) => {
      const option = document.createElement("option");
      option.value = size.id;
      option.textContent = size[propertyName];

      if (size[propertyName] === defaultValue) {
        option.selected = true;
        defaultFound = true;
      }

      baseValueSelect.appendChild(option);
    });

    if (!defaultFound && baseValueSelect.options.length > 0) {
      baseValueSelect.options[0].selected = true;
    }
  }

  /**
   * Switch unit type and update interface
   *
   * @param {string} unitType - Unit type (px or rem)
   */
  switchUnitType(unitType) {
    this.unitType = unitType;

    document.querySelectorAll("[data-unit]").forEach((btn) => {
      btn.classList.remove("active");
    });
    document
      .querySelector(`[data-unit="${unitType}"]`)
      ?.classList.add("active");

    this.triggerHook("unitTypeChanged", {
      unitType: unitType,
    });
  }

  /**
   * Sync visual state of tabs and unit buttons
   *
   * @private
   */
  syncVisualState() {
    document.querySelectorAll("[data-tab]").forEach((tab) => {
      tab.classList.remove("active");
    });
    document
      .querySelector(`[data-tab="${this.activeTab}"]`)
      ?.classList.add("active");
  }

  /* ========================================================================
     CUSTOM EVENT & DATA MANAGEMENT METHODS
     ======================================================================== */

  /**
   * Trigger segment hooks for extensibility
   *
   * @private
   */
  triggerSegmentHooks() {
    window.dispatchEvent(
      new CustomEvent("fluifofo_coreReady", {
        detail: {
          coreInterface: this,
          data: {
            settings: this.settings,
            classSizes: this.classSizes,
            variableSizes: this.variableSizes,
            tagSizes: this.tagSizes,
          },
          elements: this.elements,
        },
      })
    );
  }

  /**
   * Trigger custom hooks for extensibility
   *
   * @param {string} hookName - Hook name
   * @param {Object} data - Additional data to pass with event
   */
  triggerHook(hookName, data) {
    const eventName = hookName.includes(":")
      ? `fluifofo:${hookName}`
      : `fluifofo_${hookName}`;

    window.dispatchEvent(
      new CustomEvent(eventName, {
        detail: {
          ...data,
          coreInterface: this,
        },
      })
    );
  }

  /**
   * Get current sizes for active tab
   *
   * @returns {Array} Array of size objects
   */
  getCurrentSizes() {
    return FontForgeUtils.getCurrentSizes(this.activeTab);
  }

  /**
   * Update internal data and notify other segments
   *
   * @param {Object} newData - New data to merge
   */
  updateData(newData) {
    Object.assign(this, newData);
    this.triggerHook("dataUpdated", newData);
  }
}

/* ==========================================================================
   ADVANCED FEATURES CONTROLLER
   ========================================================================== */

/**
 * Fluid Font Advanced Features Controller
 *
 * Manages drag-and-drop, modal editing, real-time preview updates, autosave,
 * and all advanced interactive features of the plugin interface.
 *
 * @class FontClampAdvanced
 * @since 1.0.0
 */
class FontClampAdvanced {
  /**
   * Initialize advanced features controller
   */
  constructor() {
    this.initialized = false;
    this.initialized = false;
    this.editingId = null;
    this.lastFontStyle = null;
    this.dataChanged = false;
    this.selectedRowId = null;
    this.autosaveTimer = null;

    this.constants = this.initializeConstants();
    this.version = window.fluifofoAjax?.version || "4.0.5";

    this.updatePreview = this.debounce(this.updatePreview.bind(this), 150);
    this.calculateSizes = this.debounce(this.calculateSizes.bind(this), 300);

    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", () => this.init());
    } else {
      this.init();
    }
  }

  /**
   * Initialize constants from backend
   *
   * @private
   * @returns {Object} Constants object with default values
   */
  initializeConstants() {
    return (
      window.fluifofoAjax?.constants || {
        DEFAULT_MIN_ROOT_SIZE: 16,
        DEFAULT_MAX_ROOT_SIZE: 20,
        DEFAULT_MIN_VIEWPORT: 375,
        DEFAULT_MAX_VIEWPORT: 1620,
        DEFAULT_BODY_LINE_HEIGHT: 1.4,
        DEFAULT_HEADING_LINE_HEIGHT: 1.2,
        BROWSER_DEFAULT_FONT_SIZE: 16,
        CSS_UNIT_CONVERSION_BASE: 16,
      }
    );
  }

  /* ========================================================================
     INITIALIZATION & SETUP METHODS
     ======================================================================== */

  /**
   * Initialize advanced features
   *
   * @private
   */
  init() {
    try {
      this.cacheElements();
      this.bindEvents();
      this.setupTableActions();
      this.setupModal();

      this.cssGenerator = new CSSGeneratorController(this);
      this.dragDrop = new DragDropController(this);

      this.initializeDisplay();

      this.initialized = true;

      this.calculateSizes();

      window.dispatchEvent(
        new CustomEvent("fluifofo:advanced:ready", {
          detail: {
            advancedFeatures: this,
            version: this.version,
          },
        })
      );
    } catch (error) {
      console.error(
        "❌ Failed to initialize Fluid Font Advanced Features:",
        error
      );
      this.showError("Failed to initialize advanced features");
    }
  }

  /**
   * Cache frequently used DOM elements
   *
   * @private
   */
  cacheElements() {
    this.elements = {
      minRootSizeInput: document.getElementById("min-root-size"),
      maxRootSizeInput: document.getElementById("max-root-size"),
      baseValueSelect: document.getElementById("base-value"),
      minViewportInput: document.getElementById("min-viewport"),
      maxViewportInput: document.getElementById("max-viewport"),
      minScaleSelect: document.getElementById("min-scale"),
      maxScaleSelect: document.getElementById("max-scale"),
      previewFontUrlInput: document.getElementById("preview-font-url"),
      fontFilenameSpan: document.getElementById("font-filename"),
      autosaveStatus: document.getElementById("autosave-status"),
      autosaveIcon: document.getElementById("autosave-icon"),
      autosaveText: document.getElementById("autosave-text"),
      autosaveToggle: document.getElementById("autosave-toggle"),
      sizesTableWrapper: document.getElementById("sizes-table-wrapper"),
      previewMinContainer: document.getElementById("preview-min-container"),
      previewMaxContainer: document.getElementById("preview-max-container"),
      tableHeader: document.getElementById("table-header"),
      tableActionButtons: document.getElementById("table-action-buttons"),
      minViewportDisplay: document.getElementById("min-viewport-display"),
      maxViewportDisplay: document.getElementById("max-viewport-display"),
    };
  }

  /**
   * Bind event listeners to elements
   *
   * @private
   */
  bindEvents() {
    const settingsInputs = [
      "minRootSizeInput",
      "maxRootSizeInput",
      "minViewportInput",
      "maxViewportInput",
    ];

    settingsInputs.forEach((elementKey) => {
      const element = this.elements[elementKey];
      if (element) {
        element.addEventListener("input", () => this.calculateSizes());
      }
    });

    const settingsSelects = [
      "baseValueSelect",
      "minScaleSelect",
      "maxScaleSelect",
    ];

    settingsSelects.forEach((elementKey) => {
      const element = this.elements[elementKey];
      if (element) {
        element.addEventListener("change", () => this.calculateSizes());
      }
    });

    if (this.elements.previewFontUrlInput) {
      this.elements.previewFontUrlInput.addEventListener(
        "input",
        this.debounce(() => this.updatePreviewFont(), 500)
      );
    }

    if (this.elements.autosaveToggle) {
      this.elements.autosaveToggle.addEventListener("change", () => {
        this.handleAutosaveToggle();
      });

      if (this.elements.autosaveToggle.checked) {
        this.startAutosaveTimer();
      }
    }

    const saveBtn = document.getElementById("save-btn");
    if (saveBtn) {
      saveBtn.addEventListener("click", () => {
        const autosaveStatus = document.getElementById("autosave-status");
        const autosaveIcon = document.getElementById("autosave-icon");
        const autosaveText = document.getElementById("autosave-text");

        if (autosaveStatus && autosaveIcon && autosaveText) {
          autosaveStatus.className = "autosave-status saving";
          autosaveIcon.textContent = "⏳";
          autosaveText.textContent = "Saving...";
        }

        saveBtn.disabled = true;
        saveBtn.textContent = "Saving...";

        const settings = {
          minRootSize: this.elements.minRootSizeInput?.value,
          maxRootSize: this.elements.maxRootSizeInput?.value,
          minViewport: this.elements.minViewportInput?.value,
          maxViewport: this.elements.maxViewportInput?.value,
          minScale: this.elements.minScaleSelect?.value,
          maxScale: this.elements.maxScaleSelect?.value,
          unitType: window.fluifofoCore?.unitType,
          activeTab: window.fluifofoCore?.activeTab,
          previewFontUrl: this.elements.previewFontUrlInput?.value,
          autosaveEnabled: this.elements.autosaveToggle?.checked,
        };

        const allSizes = {
          classSizes: window.fluifofoAjax?.data?.classSizes || [],
          variableSizes: window.fluifofoAjax?.data?.variableSizes || [],
          tagSizes: window.fluifofoAjax?.data?.tagSizes || [],
        };

        const data = {
          action: "fluifofo_save_font_clamp_settings",
          nonce: window.fluifofoAjax.nonce,
          settings: JSON.stringify(settings),
          sizes: JSON.stringify(allSizes),
        };

        fetch(window.fluifofoAjax.ajaxurl, {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: new URLSearchParams(data),
        })
          .then((response) => response.json())

          .then((result) => {
            if (result.success) {
              if (autosaveStatus && autosaveIcon && autosaveText) {
                autosaveStatus.className = "autosave-status saved";
                autosaveIcon.textContent = "✅";
                autosaveText.textContent = "Saved!";

                setTimeout(() => {
                  autosaveStatus.className = "autosave-status idle";
                  autosaveIcon.textContent = "💾";
                  autosaveText.textContent = "Ready";
                }, 2000);
              }

              saveBtn.disabled = false;
              saveBtn.textContent = "Save";
            } else {
              throw new Error(result.data?.message || "Save failed");
            }
          })
          .catch((error) => {
            console.error("Save error:", error);

            if (autosaveStatus && autosaveIcon && autosaveText) {
              autosaveStatus.className = "autosave-status error";
              autosaveIcon.textContent = "❌";
              autosaveText.textContent = "Error";

              setTimeout(() => {
                autosaveStatus.className = "autosave-status idle";
                autosaveIcon.textContent = "💾";
                autosaveText.textContent = "Ready";
              }, 3000);
            }

            saveBtn.disabled = false;
            saveBtn.textContent = "Save";

            if (!window.fluidFontNotices) {
              window.fluidFontNotices = new WordPressAdminNotices();
            }
            window.fluidFontNotices.error(
              `<strong>Save Failed:</strong> Unable to save your font settings. Please check your connection and try again.`
            );
          });
      });
    }

    window.addEventListener("fluifofo:tab:changed", (e) => {
      this.handleTabChange(e.detail);
    });

    window.addEventListener("fluifofo_unitTypeChanged", (e) => {
      this.calculateSizes();
      this.renderSizes();
      this.updatePreview();
    });

    this.bindResetFontButton();
  }

  /**
   * Handle autosave toggle changes
   *
   * @private
   */
  handleAutosaveToggle() {
    const isEnabled = this.elements.autosaveToggle?.checked;

    if (isEnabled) {
      this.startAutosaveTimer();
    } else {
      this.stopAutosaveTimer();
    }

    this.updateSettings();
  }

  /**
   * Start autosave timer
   *
   * @private
   */
  startAutosaveTimer() {
    this.stopAutosaveTimer();

    this.autosaveTimer = setInterval(() => {
      this.performSave(true);
    }, 30000);
  }

  /**
   * Stop autosave timer
   *
   * @private
   */
  stopAutosaveTimer() {
    if (this.autosaveTimer) {
      clearInterval(this.autosaveTimer);
      this.autosaveTimer = null;
    }
  }

  /**
   * Perform save action
   *
   * @private
   * @param {boolean} isAutosave - Whether this is an autosave operation
   */
  performSave(isAutosave = false) {
    const saveBtn = document.getElementById("save-btn");
    if (saveBtn) {
      saveBtn.click();
    }
  }

  /**
   * Setup action buttons above sizes table
   *
   * @private
   */
  setupTableActions() {
    const tableButtons = this.elements.tableActionButtons;
    if (!tableButtons) return;

    tableButtons.innerHTML = `
        <button id="add-size" class="fcc-btn">Add Size</button>
        <button id="reset-defaults" class="fcc-btn">Reset</button>
        <button id="clear-sizes" class="fcc-btn fcc-btn-danger">clear all</button>
    `;

    tableButtons.addEventListener("click", (e) => {
      const button = e.target.closest("button");
      if (!button) return;

      e.preventDefault();

      switch (button.id) {
        case "add-size":
          this.addNewSize();
          break;
        case "reset-defaults":
          this.resetDefaults();
          break;
        case "clear-sizes":
          this.clearSizes();
          break;
      }
    });

    const tableWrapper = this.elements.sizesTableWrapper;
    if (tableWrapper) {
      tableWrapper.addEventListener("click", (e) => {
        const button = e.target.closest("button");
        if (!button) return;

        e.preventDefault();

        switch (button.id) {
          case "add-size":
            this.addNewSize();
            break;
          case "reset-defaults":
            this.resetDefaults();
            break;
        }
      });
    }
  }

  /**
   * Initialize display with settings and data
   *
   * @private
   */
  initializeDisplay() {
    this.populateSettings();
    this.updateBaseValueOptions();
    this.updateBaseValueOptions();

    const minViewportSize =
      this.elements.minViewportInput?.value ||
      this.constants.DEFAULT_MIN_VIEWPORT;
    const maxViewportSize =
      this.elements.maxViewportInput?.value ||
      this.constants.DEFAULT_MAX_VIEWPORT;

    if (this.elements.minViewportDisplay) {
      this.elements.minViewportDisplay.textContent = minViewportSize + "px";
    }
    if (this.elements.maxViewportDisplay) {
      this.elements.maxViewportDisplay.textContent = maxViewportSize + "px";
    }

    this.calculateSizes();
    this.renderSizes();
    this.updatePreviewFont();
    this.updatePreview();

    window.dispatchEvent(new CustomEvent("fluifofo:data:updated"));
    window.dispatchEvent(
      new CustomEvent("fluifofo:advanced:ready", {
        detail: { advancedFeatures: this, version: this.version },
      })
    );

    this.samplePanel = new SamplePanelController(this);
  }

  /**
   * Populate settings from localized data
   *
   * @private
   */
  populateSettings() {
    const data = window.fluifofoAjax?.data;
    if (!data) return;

    const minRootSize =
      data.settings?.minRootSize || this.constants.DEFAULT_MIN_ROOT_SIZE;
    const maxRootSize =
      data.settings?.maxRootSize || this.constants.DEFAULT_MAX_ROOT_SIZE;
    const minViewport =
      data.settings?.minViewport || this.constants.DEFAULT_MIN_VIEWPORT;
    const maxViewport =
      data.settings?.maxViewport || this.constants.DEFAULT_MAX_VIEWPORT;

    if (this.elements.minRootSizeInput) {
      this.elements.minRootSizeInput.value = minRootSize;
    }
    if (this.elements.maxRootSizeInput) {
      this.elements.maxRootSizeInput.value = maxRootSize;
    }
    if (this.elements.minViewportInput) {
      this.elements.minViewportInput.value = minViewport;
    }
    if (this.elements.maxViewportInput) {
      this.elements.maxViewportInput.value = maxViewport;
    }

    if (this.elements.autosaveToggle) {
      this.elements.autosaveToggle.checked =
        data.settings?.autosaveEnabled !== false;
    }
  }

  /**
   * Handle tab changes from core interface
   *
   * @private
   * @param {Object} detail - Event detail with tab information
   */
  handleTabChange(detail) {
    this.updateTableHeaders();
    this.updateBaseValueOptions();

    if (
      this.samplePanel &&
      typeof this.samplePanel.refreshBaseDropdowns === "function"
    ) {
      this.samplePanel.refreshBaseDropdowns();
    }

    setTimeout(() => {
      this.calculateSizes();
      this.renderSizes();
      this.updatePreview();
    }, 50);
  }

  /**
   * Update table headers based on active tab
   *
   * @private
   */
  updateTableHeaders() {
    const headerRow = this.elements.tableHeader;
    if (!headerRow) return;

    const activeTab = window.fluifofoCore?.activeTab || "class";
    const nameHeader = headerRow.children[1];

    if (nameHeader) {
      switch (activeTab) {
        case "class":
          nameHeader.innerHTML = "Class";
          break;
        case "vars":
          nameHeader.innerHTML = "Variable";
          break;
        case "tag":
          nameHeader.innerHTML = "Tag";
          break;
      }
    }
  }

  /* ========================================================================
     BASE VALUE MANAGEMENT
     ======================================================================== */

  /**
   * Update base value dropdown options
   *
   * @private
   */
  updateBaseValueOptions() {
    const select = this.elements.baseValueSelect;
    if (!select) return;

    if (!this.initialized) {
      setTimeout(() => this.updateBaseValueOptions(), 500);
      return;
    }

    const activeTab = window.fluifofoCore?.activeTab || "class";
    const sizes = this.getCurrentSizes();

    const currentSelection = select.value;

    select.innerHTML = "";
    select.disabled = false;

    let selectionFound = false;
    sizes.forEach((size) => {
      const option = document.createElement("option");
      switch (activeTab) {
        case "class":
          option.value = size.id;
          option.textContent = size.className;
          if (
            (currentSelection && size.id == currentSelection) ||
            (!currentSelection && size.className === "medium")
          ) {
            option.selected = true;
            selectionFound = true;
          }
          break;
        case "vars":
          option.value = size.id;
          option.textContent = size.variableName;
          if (
            (currentSelection && size.id == currentSelection) ||
            (!currentSelection && size.variableName === "--fs-md")
          ) {
            option.selected = true;
            selectionFound = true;
          }
          break;
        case "tailwind":
          option.value = size.id;
          option.textContent = size.tailwindName;
          if (
            (currentSelection && size.id == currentSelection) ||
            (!currentSelection && size.tailwindName === "base")
          ) {
            option.selected = true;
            selectionFound = true;
          }
          break;
        case "tag":
          option.value = size.id;
          option.textContent = size.tagName;
          if (
            (currentSelection && size.id == currentSelection) ||
            (!currentSelection && size.tagName === "p")
          ) {
            option.selected = true;
            selectionFound = true;
          }
          break;
      }
      select.appendChild(option);
    });
    if (!selectionFound && select.options.length > 0) {
      select.options[0].selected = true;
    }
  }

  /* ========================================================================
     DATA MANAGEMENT & CALCULATION METHODS
     ======================================================================== */

  /**
   * Calculate sizes based on current settings
   *
   * Uses mathematical scaling to determine fluid font sizes across
   * the viewport range.
   */
  calculateSizes() {
    try {
      const baseValue = this.elements.baseValueSelect?.value;
      if (!baseValue) {
        this.updateBaseValueOptions();
        const retryBaseValue = this.elements.baseValueSelect?.value;
        if (!retryBaseValue) {
          return;
        }
        const sizes = this.getCurrentSizes();
        const baseSize = window.FontForgeData
          ? window.FontForgeData.getSizeById(
              retryBaseValue,
              window.fluifofoCore?.activeTab
            )
          : sizes.find((size) => size.id == retryBaseValue);
        if (!baseSize) {
          return;
        }
        const baseIndex = sizes.indexOf(baseSize);
        const minScale = parseFloat(this.elements.minScaleSelect?.value);
        const maxScale = parseFloat(this.elements.maxScaleSelect?.value);
        const minRootSize = parseFloat(this.elements.minRootSizeInput?.value);
        const maxRootSize = parseFloat(this.elements.maxRootSizeInput?.value);
        const unitType = window.fluifofoCore?.unitType || "rem";
        if (
          isNaN(minScale) ||
          isNaN(maxScale) ||
          isNaN(minRootSize) ||
          isNaN(maxRootSize)
        ) {
          return;
        }

        let minBaseSize, maxBaseSize;
        if (unitType === "rem") {
          minBaseSize = minRootSize / this.constants.BROWSER_DEFAULT_FONT_SIZE;
          maxBaseSize = maxRootSize / this.constants.BROWSER_DEFAULT_FONT_SIZE;
        } else {
          minBaseSize = minRootSize;
          maxBaseSize = maxRootSize;
        }

        if (sizes.length === 1) {
          sizes[0].min = parseFloat(minBaseSize.toFixed(3));
          sizes[0].max = parseFloat(maxBaseSize.toFixed(3));
        } else {
          sizes.forEach((size, index) => {
            const steps = baseIndex - index;
            const minMultiplier = Math.pow(minScale, steps);
            const maxMultiplier = Math.pow(maxScale, steps);
            const calculatedMin = minBaseSize * minMultiplier;
            const calculatedMax = maxBaseSize * maxMultiplier;
            size.min = parseFloat(calculatedMin.toFixed(3));
            size.max = parseFloat(calculatedMax.toFixed(3));
          });
        }

        this.dataChanged = true;
        this.renderSizes();
        this.updatePreview();
        this.cssGenerator.updateCSS();
        return;
      }

      const sizes = this.getCurrentSizes();
      const baseSize = window.FontForgeData
        ? window.FontForgeData.getSizeById(
            baseValue,
            window.fluifofoCore?.activeTab
          )
        : sizes.find((size) => size.id == baseValue);
      if (!baseSize) {
        return;
      }
      const baseIndex = sizes.indexOf(baseSize);
      const minScale = parseFloat(this.elements.minScaleSelect?.value);
      const maxScale = parseFloat(this.elements.maxScaleSelect?.value);
      const minRootSize = parseFloat(this.elements.minRootSizeInput?.value);
      const maxRootSize = parseFloat(this.elements.maxRootSizeInput?.value);
      const unitType = window.fluifofoCore?.unitType || "rem";
      if (
        isNaN(minScale) ||
        isNaN(maxScale) ||
        isNaN(minRootSize) ||
        isNaN(maxRootSize)
      ) {
        return;
      }

      let minBaseSize, maxBaseSize;
      if (unitType === "rem") {
        minBaseSize = minRootSize / this.constants.BROWSER_DEFAULT_FONT_SIZE;
        maxBaseSize = maxRootSize / this.constants.BROWSER_DEFAULT_FONT_SIZE;
      } else {
        minBaseSize = minRootSize;
        maxBaseSize = maxRootSize;
      }

      if (sizes.length === 1) {
        sizes[0].min = parseFloat(minBaseSize.toFixed(3));
        sizes[0].max = parseFloat(maxBaseSize.toFixed(3));
      } else {
        sizes.forEach((size, index) => {
          const steps = baseIndex - index;
          const minMultiplier = Math.pow(minScale, steps);
          const maxMultiplier = Math.pow(maxScale, steps);
          const calculatedMin = minBaseSize * minMultiplier;
          const calculatedMax = maxBaseSize * maxMultiplier;
          size.min = parseFloat(calculatedMin.toFixed(3));
          size.max = parseFloat(calculatedMax.toFixed(3));
        });
      }

      this.dataChanged = true;

      this.renderSizes();
      this.updatePreview();
      this.cssGenerator.updateCSS();
      setTimeout(() => {
        this.updateSampleTextFromSettings();
      }, 100);
    } catch (error) {
      console.error("Error in calculateSizes:", error);
      this.showError("Failed to calculate sizes: " + error.message);
    }
  }

  /* ========================================================================
     UI RENDERING & PREVIEW METHODS
     ======================================================================== */

  /**
   * Update preview containers with current size data
   */
  updatePreview() {
    try {
      const previewContext = this.createPreviewContext();
      if (!this.validatePreviewContext(previewContext)) return;

      this.clearPreviewContainers(previewContext);

      if (previewContext.sizes.length === 0) {
        this.renderEmptyPreview(previewContext);
        return;
      }

      this.renderPreviewRows(previewContext);
    } catch (error) {
      console.error("⚠ Preview update error:", error);
    }
  }

  /**
   * Create preview context with all needed data
   *
   * @private
   * @returns {Object} Preview context object
   */
  createPreviewContext() {
    const activeTab = window.fluifofoCore?.activeTab || "class";

    return {
      sizes: window.FontForgeData
        ? window.FontForgeData.getSizes(activeTab)
        : this.getCurrentSizes(),
      previewMin: this.elements.previewMinContainer,
      previewMax: this.elements.previewMaxContainer,
      minRootSize: parseFloat(this.elements.minRootSizeInput?.value),
      maxRootSize: parseFloat(this.elements.maxRootSizeInput?.value),
      unitType: window.fluifofoCore?.unitType || "rem",
      activeTab: activeTab,
    };
  }

  /**
   * Validate preview context data
   *
   * @private
   * @param {Object} context - Preview context
   * @returns {boolean} True if valid
   */
  validatePreviewContext(context) {
    if (!context.previewMin || !context.previewMax) {
      return false;
    }

    if (isNaN(context.minRootSize) || isNaN(context.maxRootSize)) {
      console.error("⚠ Invalid root size values in updatePreview");
      return false;
    }

    return true;
  }

  /**
   * Clear preview containers
   *
   * @private
   * @param {Object} context - Preview context
   */
  clearPreviewContainers(context) {
    context.previewMin.innerHTML = "";
    context.previewMax.innerHTML = "";
  }

  /**
   * Render empty state message
   *
   * @private
   * @param {Object} context - Preview context
   */
  renderEmptyPreview(context) {
    const emptyMessage =
      '<div style="text-align: center; color: #6b7280; font-style: italic; padding: 60px 20px;">No sizes to preview</div>';
    context.previewMin.innerHTML = emptyMessage;
    context.previewMax.innerHTML = emptyMessage;
  }

  /**
   * Render preview rows for all sizes
   *
   * @private
   * @param {Object} context - Preview context
   */
  renderPreviewRows(context) {
    context.sizes.forEach((size, index) => {
      const rowData = this.calculatePreviewRowData(size, index, context);
      const minRow = this.createPreviewRow(
        rowData.displayName,
        rowData.minSizePx,
        "px",
        rowData.lineHeight,
        rowData.unifiedRowHeight,
        size.id,
        index,
        rowData.minPadding
      );
      const maxRow = this.createPreviewRow(
        rowData.displayName,
        rowData.maxSizePx,
        "px",
        rowData.lineHeight,
        rowData.unifiedRowHeight,
        size.id,
        index,
        rowData.maxPadding
      );

      this.addSynchronizedHover(minRow, maxRow);
      context.previewMin.appendChild(minRow);
      context.previewMax.appendChild(maxRow);
    });
  }

  /**
   * Calculate data needed for a preview row
   *
   * @private
   * @param {Object} size - Size object
   * @param {number} index - Row index
   * @param {Object} context - Preview context
   * @returns {Object} Row data for rendering
   */
  calculatePreviewRowData(size, index, context) {
    const displayName = this.getSizeDisplayName(size, context.activeTab);
    const minSize = size.min || this.constants.DEFAULT_MIN_ROOT_SIZE;
    const maxSize = size.max || this.constants.DEFAULT_MAX_ROOT_SIZE;

    let minSizePx, maxSizePx;
    if (context.unitType === "rem") {
      minSizePx = minSize * context.minRootSize;
      maxSizePx = maxSize * context.maxRootSize;
    } else {
      minSizePx = minSize;
      maxSizePx = maxSize;
    }

    const lineHeight = size.lineHeight || this.constants.DEFAULT_LINE_HEIGHT;
    const minTextHeight = minSizePx * lineHeight;
    const maxTextHeight = maxSizePx * lineHeight;
    const unifiedRowHeight = Math.max(minTextHeight, maxTextHeight) + 16;
    const paddingDiff = Math.abs(maxSizePx - minSizePx);

    return {
      displayName,
      minSizePx,
      maxSizePx,
      lineHeight,
      unifiedRowHeight,
      minPadding: minSizePx < maxSizePx ? paddingDiff : 0,
      maxPadding: maxSizePx < minSizePx ? paddingDiff : 0,
    };
  }

  /**
   * Create a single preview row element
   *
   * @private
   * @param {string} displayName - Display name for size
   * @param {number} fontSize - Font size in pixels
   * @param {string} unitType - Unit type (px or rem)
   * @param {number} lineHeight - Line height value
   * @param {number} rowHeight - Row height in pixels
   * @param {number} sizeId - Size ID
   * @param {number} index - Row index
   * @param {number} topPadding - Top padding value
   * @returns {HTMLElement} Preview row element
   */
  createPreviewRow(
    displayName,
    fontSize,
    unitType,
    lineHeight,
    rowHeight,
    sizeId,
    index,
    topPadding = 0
  ) {
    const row = document.createElement("div");
    row.className = "preview-row";
    row.dataset.sizeId = sizeId;
    row.dataset.index = index;

    row.style.cssText = `
            height: ${rowHeight}px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            margin-bottom: 4px;
            overflow: hidden;
            position: relative;
            box-sizing: border-box;
            padding: 8px 8px 12px 8px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            `;

    const text = document.createElement("div");
    text.textContent = displayName;

    const fontSizeValue = `${fontSize}px`;

    text.style.cssText = `
            font-family: var(--preview-font, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif);
            font-size: ${fontSizeValue};
            line-height: ${lineHeight};
            font-weight: 500;
            color: #1f2937;
            text-align: center;
            white-space: nowrap;
            overflow: visible;
            max-width: 100%;
            box-sizing: border-box;
            margin: 0;
            width: 100%;
            padding-top:  ${4 + topPadding}px;
            `;
    row.addEventListener("click", () => {
      this.selectedRowId = sizeId;
      this.highlightDataTableRow(sizeId, index);
      this.highlightPreviewRows(sizeId);
      this.cssGenerator.updateCSS();
    });

    row.appendChild(text);
    return row;
  }

  /**
   * Highlight corresponding data table row
   *
   * @private
   * @param {number} sizeId - Size ID
   * @param {number} index - Row index
   */
  highlightDataTableRow(sizeId, index) {
    document.querySelectorAll(".size-row.selected").forEach((row) => {
      row.classList.remove("selected");
    });

    const dataTableRow = document.querySelector(
      `.size-row[data-id="${sizeId}"]`
    );
    if (dataTableRow) {
      dataTableRow.classList.add("selected");
      dataTableRow.scrollIntoView({
        behavior: "smooth",
        block: "nearest",
      });
    }
  }

  /**
   * Highlight preview rows for selected size
   *
   * @private
   * @param {number} sizeId - Size ID
   */
  highlightPreviewRows(sizeId) {
    document.querySelectorAll(".preview-row.selected").forEach((row) => {
      row.classList.remove("selected");
    });

    document
      .querySelectorAll(`.preview-row[data-size-id="${sizeId}"]`)
      .forEach((row) => {
        row.classList.add("selected");
      });
  }

  /**
   * Add synchronized hover effect between elements
   *
   * @private
   * @param {HTMLElement} element1 - First element
   * @param {HTMLElement} element2 - Second element
   */
  addSynchronizedHover(element1, element2) {
    const hoverIn = () => {
      element1.style.backgroundColor = "rgba(59, 130, 246, 0.1)";
      element2.style.backgroundColor = "rgba(59, 130, 246, 0.1)";
    };

    const hoverOut = () => {
      element1.style.backgroundColor = "transparent";
      element2.style.backgroundColor = "transparent";
    };

    element1.addEventListener("mouseenter", hoverIn);
    element1.addEventListener("mouseleave", hoverOut);
    element2.addEventListener("mouseenter", hoverIn);
    element2.addEventListener("mouseleave", hoverOut);
  }

  /**
   * Render sizes table with current data
   */
  renderSizes() {
    const wrapper = this.elements.sizesTableWrapper;
    if (!wrapper) {
      console.log("❌ No wrapper element found");
      return;
    }

    const renderContext = this.createRenderContext();
    this.createTableStructure(wrapper);
    this.populateTableRows(renderContext);
    this.finalizeTableRender();

    const loadingScreen = document.getElementById("fcc-loading-screen");
    const mainContainer = document.getElementById("fcc-main-container");
  }

  /**
   * Create rendering context
   *
   * @private
   * @returns {Object} Render context
   */
  createRenderContext() {
    const activeTab = window.fluifofoCore?.activeTab || "class";

    const sizes = window.FontForgeData
      ? window.FontForgeData.getSizes(activeTab)
      : this.getCurrentSizes();

    return {
      sizes: sizes,
      activeTab: activeTab,
      unitType: window.fluifofoCore?.unitType || "rem",
      tbody: null,
    };
  }

  /**
   * Create table HTML structure
   *
   * @private
   * @param {HTMLElement} wrapper - Table wrapper element
   */
  createTableStructure(wrapper) {
    wrapper.innerHTML = `
    <table class="font-table">
      <thead>
        <tr id="table-header">
          <th style="width: 24px;">⋮</th>
          <th style="width: 90px;">Name</th>
          <th style="width: 70px;">Min Size</th>
          <th style="width: 70px;">Max Size</th>
          <th style="width: 40px;">Line Height</th>
          <th style="width: 30px;">Action</th>
        </tr>
      </thead>
      <tbody id="sizes-table"></tbody>
    </table>
  `;
  }

  /**
   * Populate table rows with size data
   *
   * @private
   * @param {Object} context - Render context
   */
  populateTableRows(context) {
    context.tbody = document.getElementById("sizes-table");

    context.sizes.forEach((size, index) => {
      const row = this.createTableRow(size, index, context);
      this.bindRowEvents(row);
      context.tbody.appendChild(row);
    });
  }

  /**
   * Create individual table row
   *
   * @private
   * @param {Object} size - Size object
   * @param {number} index - Row index
   * @param {Object} context - Render context
   * @returns {HTMLElement} Table row element
   */
  createTableRow(size, index, context) {
    const row = document.createElement("tr");
    row.className = "size-row";
    row.draggable = true;
    row.dataset.id = size.id;
    row.dataset.index = index;

    const displayName = this.getSizeDisplayName(size, context.activeTab);

    row.innerHTML = `
    <td class="drag-handle" style="text-align: center; color: #9ca3af; cursor: grab; user-select: none;" 
      data-tooltip="Drag to reorder" data-tooltip-position="right">⋮⋮</td>
    <td style="font-weight: 500; overflow: hidden; text-overflow: ellipsis;" title="${displayName}">${displayName}</td>
    <td style="text-align: center; font-family: monospace; font-size: 10px;">${this.formatSize(
      size.min,
      context.unitType
    )}</td>
    <td style="text-align: center; font-family: monospace; font-size: 10px;">${this.formatSize(
      size.max,
      context.unitType
    )}</td>
    <td style="text-align: center; font-size: 11px;">${size.lineHeight}</td>
    <td style="text-align: center; padding: 2px;">
      <button class="edit-btn" style="color: #3b82f6; background: none; border: none; cursor: pointer; margin-right: 6px; font-size: 13px; padding: 2px;" title="Edit">✎</button>
      <button class="delete-btn" style="color: #ef4444; background: none; border: none; cursor: pointer; font-size: 12px; padding: 2px;" title="Delete">🗑️</button>
    </td>
  `;

    return row;
  }

  /**
   * Finalize table rendering
   *
   * @private
   */
  finalizeTableRender() {
    this.updateTableHeaders();
    this.cssGenerator.updateCSS();
  }

  /**
   * Bind event listeners to table row
   *
   * @private
   * @param {HTMLElement} row - Table row element
   */
  bindRowEvents(row) {
    this.bindRowButtonEvents(row);
    this.bindRowSelectionEvents(row);
    this.dragDrop.bindRowDragEvents(row);
  }

  /**
   * Bind edit and delete button events
   *
   * @private
   * @param {HTMLElement} row - Table row element
   */
  bindRowButtonEvents(row) {
    const editBtn = row.querySelector(".edit-btn");
    if (editBtn) {
      editBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        this.editSize(parseInt(row.dataset.id));
      });
    }

    const deleteBtn = row.querySelector(".delete-btn");
    if (deleteBtn) {
      deleteBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        this.deleteSize(parseInt(row.dataset.id));
      });
    }
  }

  /**
   * Bind row selection events
   *
   * @private
   * @param {HTMLElement} row - Table row element
   */
  bindRowSelectionEvents(row) {
    row.addEventListener("click", (e) => {
      if (e.target.closest("button")) return;

      const sizeId = parseInt(row.dataset.id);

      document.querySelectorAll(".size-row.selected").forEach((r) => {
        r.classList.remove("selected");
      });

      row.classList.add("selected");
      this.selectedRowId = sizeId;
      this.highlightPreviewRows(sizeId);
      this.cssGenerator.updateCSS();
    });
  }

  /* ========================================================================
     FONT PREVIEW METHODS
     ======================================================================== */

  /**
   * Update preview font from URL input
   */
  updatePreviewFont() {
    const fontUrl = this.elements.previewFontUrlInput?.value;
    const filenameSpan = this.elements.fontFilenameSpan;

    if (fontUrl && fontUrl.trim()) {
      if (fontUrl.startsWith("file://") || fontUrl.includes(":\\")) {
        if (filenameSpan) {
          filenameSpan.textContent = "Local files not allowed";
          filenameSpan.style.color = "#ef4444";
        }

        if (!window.fluidFontNotices) {
          window.fluidFontNotices = new WordPressAdminNotices();
        }
        window.fluidFontNotices.warning(
          "<strong>Local Font Files Not Supported</strong><br><br>" +
            "Browsers block local file:// URLs for security.<br><br>" +
            "<strong>Options:</strong><br>" +
            "• Use a Google Fonts URL<br>" +
            "• Upload font to WordPress Media (add MIME type support)<br>" +
            "• Host font on a web server with https:// URL"
        );
        return;
      }

      let filename;
      if (fontUrl.includes("googleapis.com/css")) {
        const familyMatch = fontUrl.match(/family=([^&:]+)/);
        filename = familyMatch
          ? familyMatch[1].replace(/\+/g, " ")
          : "Google Font";
      } else {
        filename = fontUrl.split("/").pop().split("?")[0] || "Custom Font";
      }
      if (filenameSpan) {
        filenameSpan.textContent = filename;
        filenameSpan.style.color = "";
      }

      if (this.lastFontStyle) {
        this.lastFontStyle.remove();
      }

      const fontStyle = document.createElement("style");

      if (fontUrl.includes("googleapis.com/css") || fontUrl.endsWith(".css")) {
        fontStyle.textContent = `@import url('${fontUrl}');`;

        const familyMatch = fontUrl.match(/family=([^&:]+)/);
        const fontFamily = familyMatch
          ? familyMatch[1].replace(/\+/g, " ")
          : "PreviewFont";

        document.documentElement.style.setProperty(
          "--preview-font",
          `'${fontFamily}', sans-serif`
        );
      } else {
        fontStyle.textContent = `
    @font-face {
      font-family: 'PreviewFont';
      src: url('${fontUrl}') format('woff2');
      font-display: swap;
    }
  `;
        document.documentElement.style.setProperty(
          "--preview-font",
          "'PreviewFont', sans-serif"
        );
      }
      document.head.appendChild(fontStyle);
      this.lastFontStyle = fontStyle;
    } else {
      if (filenameSpan) {
        filenameSpan.textContent = "Default";
        filenameSpan.style.color = "";
      }
      if (this.lastFontStyle) {
        this.lastFontStyle.remove();
        this.lastFontStyle = null;
      }
      document.documentElement.style.removeProperty("--preview-font");
    }
  }

  /**
   * Bind reset font button event
   */
  bindResetFontButton() {
    const resetBtn = document.getElementById("reset-font-btn");
    if (resetBtn) {
      resetBtn.addEventListener("click", () => {
        const fontInput = this.elements.previewFontUrlInput;
        if (fontInput) {
          fontInput.value = "";
          this.updatePreviewFont();
        }
      });
    }
  }

  /* ========================================================================
     MODAL & EDITING METHODS
     ======================================================================== */

  /**
   * Setup modal HTML structure and bind events
   *
   * @private
   */
  setupModal() {
    const existing = document.getElementById("edit-modal");
    if (existing) existing.remove();

    const modal = document.createElement("div");
    modal.id = "edit-modal";
    modal.className = "fcc-modal";
    modal.innerHTML = `
                        <div class="fcc-modal-dialog">
                            <div class="fcc-modal-header">
                                Edit Size
                                <button type="button" class="fcc-modal-close" aria-label="Close">&times;</button>
                            </div>
                            <div class="fcc-modal-content">
                                <div class="fcc-form-group" id="name-field">
                                    <label class="fcc-label" for="edit-name">Name</label>
                                    <input type="text" id="edit-name" class="fcc-input" required>
                                </div>
                                <div class="fcc-form-group">
                                    <label class="fcc-label" for="edit-line-height">Line Height</label>
                                    <input type="number" id="edit-line-height" class="fcc-input" 
                                           step="0.1" min="0.8" max="3.0" required>
                                </div>
<div class="fcc-btn-group">
    <button type="button" class="fcc-btn fcc-btn-ghost" id="modal-cancel">cancel</button>
    <button type="button" class="fcc-btn" id="modal-save">save</button>
</div>
                            </div>
                        </div>
                    `;

    document.body.appendChild(modal);
    this.bindModalEvents(modal);
  }

  /**
   * Bind event listeners to modal elements
   *
   * @private
   * @param {HTMLElement} modal - Modal element
   */
  bindModalEvents(modal) {
    const closeBtn = modal.querySelector(".fcc-modal-close");
    const cancelBtn = modal.querySelector("#modal-cancel");
    const saveBtn = modal.querySelector("#modal-save");

    closeBtn.addEventListener("click", () => this.closeModal());
    cancelBtn.addEventListener("click", () => this.closeModal());

    modal.addEventListener("click", (e) => {
      if (e.target === modal) this.closeModal();
    });

    saveBtn.addEventListener("click", () => {
      this.saveEdit();
    });
    modal.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        this.saveEdit();
      } else if (e.key === "Escape") {
        this.closeModal();
      }
    });
  }

  /**
   * Open modal for editing a specific size
   *
   * @param {number} id - Size ID to edit
   */
  editSize(id) {
    const sizes = this.getCurrentSizes();
    const size = sizes.find((s) => s.id == id);
    if (!size) return;

    this.editingId = id;
    this.isAddingNew = false;

    const modal = document.getElementById("edit-modal");
    const nameInput = document.getElementById("edit-name");
    const nameField = document.getElementById("name-field");
    const lineHeightInput = document.getElementById("edit-line-height");

    if (!modal || !nameInput || !lineHeightInput) return;

    const activeTab = window.fluifofoCore?.activeTab || "class";
    const displayName = this.getSizeDisplayName(size, activeTab);

    nameInput.value = displayName;
    lineHeightInput.value = size.lineHeight;

    if (activeTab === "tag") {
      nameField.style.display = "block";
      nameInput.disabled = true;
      nameInput.style.opacity = "0.6";
      nameInput.style.cursor = "not-allowed";
    } else {
      nameField.style.display = "block";
      nameInput.disabled = false;
      nameInput.style.opacity = "1";
      nameInput.style.cursor = "text";
    }

    const header = modal.querySelector(".fcc-modal-header");
    if (header) {
      header.firstChild.textContent = `Edit ${displayName}`;
    }

    modal.classList.add("show");

    setTimeout(() => {
      (activeTab === "tag" ? lineHeightInput : nameInput).focus();
    }, 100);
  }

  /**
   * Save edit from modal
   *
   * @private
   */
  saveEdit() {
    if (!this.editingId) return;

    const sizes = this.getCurrentSizes();
    const activeTab = window.fluifofoCore?.activeTab || "class";
    let size;

    if (this.isAddingNew) {
      size = {
        id: this.editingId,
        min: this.constants.DEFAULT_MIN_ROOT_SIZE,
        max: this.constants.DEFAULT_MAX_ROOT_SIZE,
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      };

      if (activeTab === "class") {
        size.className = "";
      } else if (activeTab === "vars") {
        size.variableName = "";
      } else if (activeTab === "tag") {
        size.tagName = "";
      } else if (activeTab === "tailwind") {
        size.tailwindName = "";
      }
    } else {
      size = sizes.find((s) => s.id == this.editingId);
      if (!size) {
        console.error("Cannot find size to edit with ID:", this.editingId);
        return;
      }
    }

    const nameInput = document.getElementById("edit-name");
    const lineHeightInput = document.getElementById("edit-line-height");

    const newName = nameInput?.value.trim();
    const newLineHeight = parseFloat(lineHeightInput?.value);

    if (!newName && activeTab !== "tag") {
      this.showFieldError(nameInput, "Name cannot be empty");
      return;
    }

    if (isNaN(newLineHeight) || newLineHeight < 0.8 || newLineHeight > 3.0) {
      this.showFieldError(
        lineHeightInput,
        "Line height must be between 0.8 and 3.0"
      );
      return;
    }

    if (activeTab !== "tag") {
      const isDuplicate = sizes.some((s) => {
        if (s.id === this.editingId) return false;
        const existingName = this.getSizeDisplayName(s, activeTab);
        return existingName === newName;
      });

      if (isDuplicate) {
        this.showFieldError(nameInput, "A size with this name already exists");
        return;
      }
    }

    if (activeTab === "class") {
      size.className = newName;
    } else if (activeTab === "vars") {
      size.variableName = newName;
    } else if (activeTab === "tag") {
      size.tagName = newName;
    } else if (activeTab === "tailwind") {
      size.tailwindName = newName;
    }
    size.lineHeight = newLineHeight;

    if (this.isAddingNew) {
      const activeTab = window.fluifofoCore?.activeTab || "class";

      if (window.FontForgeData) {
        window.FontForgeData.addSize(activeTab, size);
      } else {
        sizes.push(size);

        if (activeTab === "class") {
          window.fluifofoAjax.data.classSizes = sizes;
        } else if (activeTab === "vars") {
          window.fluifofoAjax.data.variableSizes = sizes;
        } else if (activeTab === "tag") {
          window.fluifofoAjax.data.tagSizes = sizes;
        } else if (activeTab === "tailwind") {
          window.fluifofoAjax.data.tailwindSizes = sizes;
        }
      }
    }

    this.updateBaseValueOptions();
    this.calculateSizes();
    this.renderSizes();
    this.updatePreview();
    this.markDataChanged();

    if (
      this.samplePanel &&
      typeof this.samplePanel.refreshBaseDropdowns === "function"
    ) {
      this.samplePanel.refreshBaseDropdowns();
    }

    this.closeModal();
  }

  /**
   * Close the modal and reset state
   *
   * @private
   */
  closeModal() {
    const modal = document.getElementById("edit-modal");
    if (modal) {
      modal.classList.remove("show");
    }
    this.editingId = null;
    this.isAddingNew = false;
  }

  /**
   * Show error state on input field
   *
   * @private
   * @param {HTMLElement} field - Input field element
   * @param {string} message - Error message
   */
  showFieldError(field, message) {
    field.classList.add("error");
    field.focus();

    const modal = document.getElementById("edit-modal");
    let errorDiv = modal.querySelector(".modal-error");

    if (!errorDiv) {
      errorDiv = document.createElement("div");
      errorDiv.className = "modal-error";
      errorDiv.style.cssText = `
        background: #dc3545;
        color: white;
        padding: 12px;
        margin: 0 0 16px 0;
        border-radius: 4px;
        font-size: 14px;
        border: 1px solid #c82333;
      `;

      const modalContent = modal.querySelector(".fcc-modal-content");
      modalContent.insertBefore(errorDiv, modalContent.firstChild);
    }

    errorDiv.innerHTML = `<strong>⚠️ Validation Error:</strong> ${message}`;
    errorDiv.style.display = "block";

    setTimeout(() => {
      if (errorDiv) {
        errorDiv.style.display = "none";
      }
      field.classList.remove("error");
    }, 5000);
  }

  /**
   * Delete size entry
   *
   * @param {number} id - Size ID to delete
   */
  deleteSize(id) {
    if (!window.fluidFontNotices) {
      window.fluidFontNotices = new WordPressAdminNotices();
    }

    window.fluidFontNotices.confirm(
      "Delete this size?<br><br>This action cannot be undone.",
      () => {
        const activeTab = window.fluifofoCore?.activeTab || "class";

        if (window.FontForgeData) {
          const success = window.FontForgeData.removeSize(activeTab, id);
          if (success) {
            this.renderSizes();
            this.updatePreview();
            this.markDataChanged();
          }
        } else {
          const sizes = this.getCurrentSizes();
          const index = sizes.findIndex((s) => s.id == id);
          if (index !== -1) {
            sizes.splice(index, 1);
            this.renderSizes();
            this.updatePreview();
            this.markDataChanged();
          }
        }
      }
    );
  }

  /* ========================================================================
     TABLE ACTIONS & CONTROLS
     ======================================================================== */

  /**
   * Open add new size modal with pre-filled data
   */
  addNewSize() {
    const activeTab = window.fluifofoCore?.activeTab || "class";

    const { nextId, customName } = this.generateNextCustomEntry(activeTab);

    this.openAddModal(activeTab, nextId, customName);
  }

  /**
   * Generate next custom entry data
   *
   * @private
   * @param {string} activeTab - Active tab identifier
   * @returns {Object} Object with nextId and customName properties
   */
  generateNextCustomEntry(activeTab) {
    const currentData = window.FontForgeData
      ? window.FontForgeData.getSizes(activeTab, { useDefaults: false })
      : this.getCurrentSizes(activeTab);

    const maxId =
      currentData.length > 0
        ? Math.max(...currentData.map((item) => item.id))
        : 0;
    const nextId = maxId + 1;

    const customEntries = currentData.filter((item) => {
      const name = this.getSizeDisplayName(item, activeTab);
      return name.includes("custom-") || name.includes("--fs-custom-");
    });

    const nextCustomNumber = customEntries.length + 1;
    let customName;

    if (activeTab === "class") {
      customName = `custom-${nextCustomNumber}`;
    } else if (activeTab === "vars") {
      customName = `--fs-custom-${nextCustomNumber}`;
    } else if (activeTab === "tag") {
      customName = "span";
    } else if (activeTab === "tailwind") {
      customName = `custom-${nextCustomNumber}`;
    }

    return {
      nextId,
      customName,
    };
  }

  /**
   * Open add modal with pre-filled data
   *
   * @private
   * @param {string} activeTab - Active tab identifier
   * @param {number} newId - New size ID
   * @param {string} defaultName - Default name for new size
   */
  openAddModal(activeTab, newId, defaultName) {
    const modal = document.getElementById("edit-modal");
    const header = modal.querySelector(".fcc-modal-header");
    const nameInput = document.getElementById("edit-name");
    const nameField = document.getElementById("name-field");
    const lineHeightInput = document.getElementById("edit-line-height");

    header.firstChild.textContent = `Add ${
      activeTab === "class"
        ? "Class"
        : activeTab === "vars"
        ? "Variable"
        : "Tag"
    }`;

    nameInput.value = defaultName;
    lineHeightInput.value = this.constants.DEFAULT_BODY_LINE_HEIGHT;

    if (activeTab === "tag") {
      nameField.style.display = "block";
      nameInput.disabled = true;
      nameInput.style.opacity = "0.6";
      nameInput.style.cursor = "not-allowed";
    } else {
      nameField.style.display = "block";
      nameInput.disabled = false;
      nameInput.style.opacity = "1";
      nameInput.style.cursor = "text";
    }

    this.editingId = newId;
    this.isAddingNew = true;

    modal.classList.add("show");

    setTimeout(() => {
      const focusInput = activeTab === "tag" ? lineHeightInput : nameInput;
      if (focusInput) {
        focusInput.focus();
        if (focusInput === nameInput) {
          focusInput.setSelectionRange(0, focusInput.value.length);
        }
      }
    }, 100);
  }

  /**
   * Reset sizes to default with confirmation
   */
  resetDefaults() {
    const activeTab = window.fluifofoCore?.activeTab || "class";
    const tabName =
      activeTab === "class"
        ? "Classes"
        : activeTab === "vars"
        ? "Variables"
        : activeTab === "tailwind"
        ? "Tailwind Sizes"
        : "Tags";
    if (!window.fluidFontNotices) {
      window.fluidFontNotices = new WordPressAdminNotices();
    }
    window.fluidFontNotices.confirm(
      `Reset ${tabName} to defaults?<br><br>This will replace all current entries with the original default sizes.<br><br><strong>Any custom entries will be lost.</strong>`,
      () => {
        if (window.FontForgeData) {
          window.FontForgeData.resetToDefaults(activeTab);
        } else {
          switch (activeTab) {
            case "class":
              const defaultClassSizes = window.FontForgeData?.getDefaultSizes(
                "class"
              ) || [
                { id: 1, className: "xxxlarge", lineHeight: 1.2 },
                { id: 2, className: "xxlarge", lineHeight: 1.2 },
                { id: 3, className: "xlarge", lineHeight: 1.2 },
                { id: 4, className: "large", lineHeight: 1.4 },
                { id: 5, className: "medium", lineHeight: 1.4 },
                { id: 6, className: "small", lineHeight: 1.4 },
                { id: 7, className: "xsmall", lineHeight: 1.4 },
                { id: 8, className: "xxsmall", lineHeight: 1.4 },
              ];
              const defaultSizes =
                window.FontForgeData?.getDefaultSizes("class");
              window.fluifofoAjax.data.classSizes = defaultClassSizes;
              break;
            case "vars":
              window.fluifofoAjax.data.variableSizes =
                window.FontForgeData.getDefaultVariableSizes("class");
              break;
            case "tailwind":
              window.fluifofoAjax.data.tailwindSizes =
                window.FontForgeData.getDefaultTailwindSizes("class");
              break;
            case "tag":
              window.fluifofoAjax.data.tagSizes =
                window.FontForgeData.getDefaultTagSizes("class");
              break;
          }
          if (window.fluifofoAjax.data.explicitlyClearedTabs) {
            delete window.fluifofoAjax.data.explicitlyClearedTabs[activeTab];
          }
        }
        this.calculateSizes();
        this.renderSizes();
        this.updatePreview();
        this.markDataChanged();

        setTimeout(() => {
          if (window.fluifofoCore) {
            const activeTab = window.fluifofoCore.activeTab;
            switch (activeTab) {
              case "class":
                window.fluifofoCore.classSizes =
                  window.fluifofoAjax.data.classSizes;
                break;
              case "vars":
                window.fluifofoCore.variableSizes =
                  window.fluifofoAjax.data.variableSizes;
                break;
              case "tailwind":
                window.fluifofoCore.tailwindSizes =
                  window.fluifofoAjax.data.tailwindSizes;
                break;
              case "tag":
                window.fluifofoCore.tagSizes =
                  window.fluifofoAjax.data.tagSizes;
                break;
            }
            window.fluifofoCore.updateBaseValueDropdown(activeTab);
          }
        }, 200);

        if (
          this.samplePanel &&
          typeof this.samplePanel.refreshBaseDropdowns === "function"
        ) {
          setTimeout(() => {
            this.samplePanel.refreshBaseDropdowns();
          }, 250);
        }

        try {
          this.showResetNotification(tabName);
        } catch (error) {
          console.error("Failed to create success notification:", error);
          alert(`${tabName} have been reset to defaults successfully.`);
        }
      }
    );
  }

  /**
   * Show reset success notification
   *
   * @private
   * @param {string} tabName - Tab display name
   */
  showResetNotification(tabName) {
    const notification = document.createElement("div");
    notification.id = "reset-notification";
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: var(--clr-success);
        color: white;
        padding: 16px 20px;
        border-radius: var(--jimr-border-radius-lg);
        box-shadow: var(--clr-shadow-xl);
        z-index: 10001;
        display: flex;
        align-items: center;
        gap: 12px;
        border: 2px solid var(--clr-success-dark);
        animation: slideInUp 0.3s ease;
    `;

    notification.innerHTML = `
        <div style="font-size: 20px;">✅</div>
        <div>
            <div style="font-weight: 600; margin-bottom: 2px;">Reset Complete</div>
            <div style="font-size: 12px; opacity: 0.9;">Restored default ${tabName.toLowerCase()}</div>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
      if (document.body.contains(notification)) {
        notification.style.transition =
          "transform 0.25s ease-out, opacity 0.25s ease-out";
        notification.style.transform = "translateY(100%)";
        notification.style.opacity = "0";

        setTimeout(() => {
          if (document.body.contains(notification)) {
            document.body.removeChild(notification);
          }
        }, 250);
      }
    }, 3000);
  }

  /**
   * Get default class sizes
   *
   * @returns {Array} Default class size configurations
   */
  getDefaultClassSizes() {
    return [
      {
        id: 1,
        className: "xxxlarge",
        lineHeight: this.constants.DEFAULT_HEADING_LINE_HEIGHT,
      },
      {
        id: 2,
        className: "xxlarge",
        lineHeight: this.constants.DEFAULT_HEADING_LINE_HEIGHT,
      },
      {
        id: 3,
        className: "xlarge",
        lineHeight: this.constants.DEFAULT_HEADING_LINE_HEIGHT,
      },
      {
        id: 4,
        className: "large",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 5,
        className: "medium",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 6,
        className: "small",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 7,
        className: "xsmall",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 8,
        className: "xxsmall",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
    ];
  }

  /**
   * Get default variable sizes
   *
   * @returns {Array} Default variable size configurations
   */
  getDefaultVariableSizes() {
    return [
      {
        id: 1,
        variableName: "--fs-xxxl",
        lineHeight: this.constants.DEFAULT_HEADING_LINE_HEIGHT,
      },
      {
        id: 2,
        variableName: "--fs-xxl",
        lineHeight: this.constants.DEFAULT_HEADING_LINE_HEIGHT,
      },
      {
        id: 3,
        variableName: "--fs-xl",
        lineHeight: this.constants.DEFAULT_HEADING_LINE_HEIGHT,
      },
      {
        id: 4,
        variableName: "--fs-lg",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 5,
        variableName: "--fs-md",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 6,
        variableName: "--fs-sm",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 7,
        variableName: "--fs-xs",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 8,
        variableName: "--fs-xxs",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
    ];
  }

  /**
   * Get default tag sizes
   *
   * @returns {Array} Default tag size configurations
   */
  getDefaultTagSizes() {
    return [
      {
        id: 1,
        tagName: "h1",
        lineHeight: this.constants.DEFAULT_HEADING_LINE_HEIGHT,
      },
      {
        id: 2,
        tagName: "h2",
        lineHeight: this.constants.DEFAULT_HEADING_LINE_HEIGHT,
      },
      {
        id: 3,
        tagName: "h3",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 4,
        tagName: "h4",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 5,
        tagName: "h5",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 6,
        tagName: "h6",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 7,
        tagName: "p",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
    ];
  }

  /**
   * Get default tailwind sizes
   *
   * @returns {Array} Default Tailwind size configurations
   */
  getDefaultTailwindSizes() {
    return [
      {
        id: 1,
        tailwindName: "4xl",
        lineHeight: this.constants.DEFAULT_HEADING_LINE_HEIGHT,
      },
      {
        id: 2,
        tailwindName: "3xl",
        lineHeight: this.constants.DEFAULT_HEADING_LINE_HEIGHT,
      },
      {
        id: 3,
        tailwindName: "2xl",
        lineHeight: this.constants.DEFAULT_HEADING_LINE_HEIGHT,
      },
      {
        id: 4,
        tailwindName: "xl",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 5,
        tailwindName: "base",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 6,
        tailwindName: "lg",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 7,
        tailwindName: "sm",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
      {
        id: 8,
        tailwindName: "xs",
        lineHeight: this.constants.DEFAULT_BODY_LINE_HEIGHT,
      },
    ];
  }

  /**
   * Clear all sizes with confirmation
   */
  clearSizes() {
    const clearContext = this.createClearContext();
    if (!this.validateClearContext(clearContext)) return;

    if (!this.confirmClear(clearContext)) return;

    this.performClear(clearContext);
    this.showUndoNotification(
      clearContext.tabName,
      clearContext.currentData,
      clearContext.dataArrayRef,
      clearContext.activeTab
    );
  }

  /**
   * Create context for clear operation
   *
   * @private
   * @returns {Object} Clear context object
   */
  createClearContext() {
    const activeTab = window.fluifofoCore?.activeTab || "class";
    const tabName = this.getTabDisplayName(activeTab);
    const { currentData, dataArrayRef } = this.getTabDataForClear(activeTab);

    return {
      activeTab,
      tabName,
      currentData,
      dataArrayRef,
    };
  }

  /**
   * Get display name for tab
   *
   * @private
   * @param {string} activeTab - Tab identifier
   * @returns {string} Display name
   */
  getTabDisplayName(activeTab) {
    switch (activeTab) {
      case "class":
        return "Classes";
      case "vars":
        return "Variables";
      case "tailwind":
        return "Tailwind Sizes";
      case "tag":
        return "Tags";
      default:
        return "Items";
    }
  }

  /**
   * Get current data and reference for clear operation
   *
   * @private
   * @param {string} activeTab - Tab identifier
   * @returns {Object} Object with currentData and dataArrayRef
   */
  getTabDataForClear(activeTab) {
    const currentData = window.FontForgeData
      ? [...window.FontForgeData.getSizes(activeTab, { useDefaults: false })]
      : [
          ...(TabDataUtils.getDataForTab(
            activeTab,
            window.fluifofoAjax?.data
          ) || []),
        ];

    const dataArrayRef = TabDataMap[activeTab]?.dataKey || null;

    return { currentData, dataArrayRef };
  }

  /**
   * Validate clear context
   *
   * @private
   * @param {Object} context - Clear context
   * @returns {boolean} True if valid
   */
  validateClearContext(context) {
    if (!context.currentData || !context.dataArrayRef) {
      console.error(
        "Unable to clear sizes - invalid tab or missing data:",
        context.activeTab
      );
      return false;
    }
    return true;
  }

  /**
   * Show confirmation dialog for clear operation
   *
   * @private
   * @param {Object} context - Clear context
   * @returns {boolean} Always returns false (async operation)
   */
  confirmClear(context) {
    if (!window.fluidFontNotices) {
      window.fluidFontNotices = new WordPressAdminNotices();
    }

    window.fluidFontNotices.confirm(
      `Are you sure you want to clear all ${context.tabName}?<br><br>This will remove all ${context.currentData.length} entries from the current tab.<br><br>You can undo this action immediately after.`,
      () => {
        this.performClear(context);
        this.showUndoNotification(
          context.tabName,
          context.currentData,
          context.dataArrayRef,
          context.activeTab
        );
      }
    );

    return false;
  }

  /**
   * Perform the clear operation
   *
   * @private
   * @param {Object} context - Clear context
   */
  performClear(context) {
    this.clearDataSources(context);
    this.updateCoreInterfaceData(context.activeTab);
    this.renderClearedState(context.activeTab);
    this.updateAfterClear();
  }

  /**
   * Clear data sources
   *
   * @private
   * @param {Object} context - Clear context
   */
  clearDataSources(context) {
    if (window.FontForgeData) {
      window.FontForgeData.clearSizes(context.activeTab);
    } else {
      if (window.fluifofoAjax?.data) {
        window.fluifofoAjax.data[context.dataArrayRef] = [];
        window.fluifofoAjax.data.explicitlyClearedTabs =
          window.fluifofoAjax.data.explicitlyClearedTabs || {};
        window.fluifofoAjax.data.explicitlyClearedTabs[
          context.activeTab
        ] = true;
      }
    }
  }

  /**
   * Update core interface data
   *
   * @private
   * @param {string} activeTab - Tab identifier
   */
  updateCoreInterfaceData(activeTab) {
    if (!window.fluifofoCore) return;

    switch (activeTab) {
      case "class":
        window.fluifofoCore.classSizes = [];
        break;
      case "vars":
        window.fluifofoCore.variableSizes = [];
        break;
      case "tag":
        window.fluifofoCore.tagSizes = [];
        break;
      case "tailwind":
        window.fluifofoCore.tailwindSizes = [];
        break;
    }
  }

  /**
   * Render cleared state UI
   *
   * @private
   * @param {string} activeTab - Tab identifier
   */
  renderClearedState(activeTab) {
    this.renderEmptyTable();
    this.updateBaseDropdownForClear(activeTab);
  }

  /**
   * Update base dropdown for cleared state
   *
   * @private
   * @param {string} activeTab - Tab identifier
   */
  updateBaseDropdownForClear(activeTab) {
    const baseSelect = document.getElementById("base-value");
    if (!baseSelect) return;

    const emptyOptionText = this.getEmptyOptionText(activeTab);
    baseSelect.innerHTML = `<option>${emptyOptionText}</option>`;
    baseSelect.disabled = true;
  }

  /**
   * Get empty option text for base dropdown
   *
   * @private
   * @param {string} activeTab - Tab identifier
   * @returns {string} Empty option text
   */
  getEmptyOptionText(activeTab) {
    switch (activeTab) {
      case "class":
        return "No classes";
      case "vars":
        return "No variables";
      case "tailwind":
        return "No sizes";
      case "tag":
        return "No tags";
      default:
        return "No items";
    }
  }

  /**
   * Update UI after clear operation
   *
   * @private
   */
  updateAfterClear() {
    this.updatePreview();
    this.cssGenerator.updateCSS();
    this.markDataChanged();

    if (
      this.samplePanel &&
      typeof this.samplePanel.refreshBaseDropdowns === "function"
    ) {
      this.samplePanel.refreshBaseDropdowns();
    }
  }

  /**
   * Show undo notification after clearing sizes
   *
   * @private
   * @param {string} tabName - Tab display name
   * @param {Array} backupData - Backup of cleared data
   * @param {string} dataArrayRef - Data array reference key
   * @param {string} tabType - Tab type identifier
   */
  showUndoNotification(tabName, backupData, dataArrayRef, tabType) {
    const notification = document.createElement("div");
    notification.id = "clear-undo-notification";
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: var(--clr-secondary);
        color: #FAF9F6;
        padding: 16px 20px;
        border-radius: var(--jimr-border-radius-lg);
        box-shadow: var(--clr-shadow-xl);
        z-index: 10001;
        display: flex;
        align-items: center;
        gap: 16px;
        border: 2px solid var(--clr-primary);
        max-width: 400px;
        animation: slideInUp 0.3s ease;
    `;

    notification.innerHTML = `
        <div style="flex-grow: 1;">
            <div style="font-weight: 600; margin-bottom: 4px;">Cleared ${backupData.length} ${tabName}</div>
            <div style="font-size: 12px; opacity: 0.9;">This action can be undone</div>
        </div>
<button id="undo-clear-btn" style="
            background: var(--clr-accent);
            color: var(--clr-btn-txt);
            border: 1px solid var(--clr-btn-bdr);
            padding: 8px 16px;
            border-radius: var(--jimr-border-radius);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--jimr-transition);
        " onmouseover="this.style.background='var(--clr-btn-hover)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--clr-shadow-lg)';" onmouseout="this.style.background='var(--clr-accent)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">UNDO</button>
        <button id="dismiss-clear-btn" style="
            background: none;
            border: none;
            color: #FAF9F6;
            font-size: 18px;
            cursor: pointer;
            padding: 4px;
            opacity: 0.7;
            transition: opacity 0.2s ease;
        ">×</button>
    `;

    if (!document.getElementById("notification-animations")) {
      const style = document.createElement("style");
      style.id = "notification-animations";
      style.textContent = `
            @keyframes slideInUp {
                from { transform: translateY(100%); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            @keyframes slideOutDown {
                from { transform: translateY(0); opacity: 1; }
                to { transform: translateY(100%); opacity: 0; }
            }
        `;
      document.head.appendChild(style);
    }

    document.body.appendChild(notification);

    document.getElementById("undo-clear-btn").addEventListener("click", () => {
      if (window.FontForgeData) {
        window.FontForgeData.setSizes(tabType, backupData);
        if (window.fluifofoAjax?.data?.explicitlyClearedTabs) {
          delete window.fluifofoAjax.data.explicitlyClearedTabs[tabType];
        }
      } else {
        if (window.fluifofoAjax?.data) {
          window.fluifofoAjax.data[dataArrayRef] = backupData;
          if (window.fluifofoAjax.data.explicitlyClearedTabs) {
            delete window.fluifofoAjax.data.explicitlyClearedTabs[tabType];
          }
        }
      }

      this.renderSizes();
      this.updateBaseValueOptions();
      this.updatePreview();
      this.cssGenerator.updateCSS();

      this.removeNotification(notification);
    });

    document
      .getElementById("dismiss-clear-btn")
      .addEventListener("click", () => {
        this.removeNotification(notification);
      });

    const handleUndoKeydown = (event) => {
      if (event.key === "Enter" && document.body.contains(notification)) {
        event.preventDefault();
        event.stopPropagation();
        this.removeNotification(notification);
        document.removeEventListener("keydown", handleUndoKeydown);
      }
    };
    document.addEventListener("keydown", handleUndoKeydown);

    const timeoutId = setTimeout(() => {
      if (document.body.contains(notification)) {
        this.removeNotification(notification);
        document.removeEventListener("keydown", handleUndoKeydown);
      }
    }, 10000);

    notification.dataset.timeoutId = timeoutId;
  }

  /**
   * Remove notification with animation
   *
   * @private
   * @param {HTMLElement} notification - Notification element
   */
  removeNotification(notification) {
    if (notification.dataset.removing === "true") return;
    notification.dataset.removing = "true";

    notification.style.transition =
      "transform 0.25s ease-out, opacity 0.25s ease-out";
    notification.style.transform = "translateY(100%)";
    notification.style.opacity = "0";

    setTimeout(() => {
      if (document.body.contains(notification)) {
        document.body.removeChild(notification);
      }
    }, 250);
  }

  /**
   * Render empty table state
   *
   * @private
   */
  renderEmptyTable() {
    const wrapper = this.elements.sizesTableWrapper;
    if (!wrapper) return;

    const activeTab = window.fluifofoCore?.activeTab || "class";
    const tabDisplayName =
      activeTab === "class"
        ? "Font Size Classes"
        : activeTab === "vars"
        ? "CSS Variables"
        : "HTML Tag Styles";
    const addButtonText =
      activeTab === "class"
        ? "add first class"
        : activeTab === "vars"
        ? "add first variable"
        : "add first tag";

    wrapper.innerHTML = `
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: var(--jimr-border-radius-lg); border: 2px dashed var(--jimr-gray-300); margin-top: 20px;">
            <div style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;">📭</div>
            <h3 style="color: var(--jimr-gray-600); margin: 0 0 8px 0; font-size: 18px;">No ${tabDisplayName}</h3>
            <p style="color: var(--jimr-gray-500); margin: 0 0 20px 0; font-size: 14px;">Get started by adding your first size or reset to defaults.</p>
<button id="add-size" class="fcc-btn" style="margin-right: 12px;">${addButtonText}</button>
            <button id="reset-defaults" class="fcc-btn">reset to defaults</button>
        </div>
    `;
  }

  /**
   * Update settings and recalculate sizes
   */
  updateSettings() {
    this.calculateSizes();
    this.renderSizes();
    this.updatePreview();
    this.markDataChanged();
  }

  /**
   * Mark data as changed for save tracking
   */
  markDataChanged() {
    this.dataChanged = true;
  }

  /* ========================================================================
     DATA MANAGEMENT & UTILITY METHODS
     ======================================================================== */

  /**
   * Get sizes for currently active tab
   *
   * @returns {Array} Array of size objects
   */
  getCurrentSizes() {
    const activeTab = window.fluifofoCore?.activeTab || "class";
    const sizes = FontForgeUtils.getCurrentSizes(activeTab, this);
    return sizes;
  }

  /**
   * Get display name for a size based on active tab
   *
   * @param {Object} size - Size object
   * @param {string} activeTab - Tab identifier
   * @returns {string} Display name
   */
  getSizeDisplayName(size, activeTab) {
    if (window.FontForgeData) {
      return window.FontForgeData.getSizeDisplayName(size, activeTab);
    }

    switch (activeTab) {
      case "class":
        return size.className || "";
      case "vars":
        return size.variableName || "";
      case "tailwind":
        return size.tailwindName || "";
      case "tag":
        return size.tagName || "";
      default:
        return "";
    }
  }

  /**
   * Format size value with appropriate unit
   *
   * @param {number} value - Size value
   * @param {string} unitType - Unit type (px or rem)
   * @returns {string} Formatted size string
   */
  formatSize(value, unitType) {
    if (!value) return "—";
    if (unitType === "px") {
      return `${Math.round(value)} ${unitType}`;
    }
    return `${value.toFixed(3)} ${unitType}`;
  }

  /**
   * Debounce function to limit function call frequency
   *
   * @param {Function} func - Function to debounce
   * @param {number} wait - Wait time in milliseconds
   * @returns {Function} Debounced function
   */
  debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func.apply(this, args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  /**
   * Show error message in console
   *
   * @param {string} message - Error message
   */
  showError(message) {
    console.error(message);
  }
}

/* ==========================================================================
   INITIALIZATION
   ========================================================================== */

new SimpleTooltips();

window.fontClampAdvanced = new FontClampAdvanced();

document.addEventListener("DOMContentLoaded", () => {
  window.fluifofoCore = new FontClampEnhancedCoreInterface();
});
