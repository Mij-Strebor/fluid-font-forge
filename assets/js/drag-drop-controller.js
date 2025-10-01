/**
 * Fluid Font Forge - Drag and Drop Controller
 *
 * Manages drag-and-drop functionality for reordering font sizes in the data table.
 * Provides visual feedback during drag operations and handles the reordering logic
 * for size arrays across different tab types.
 *
 * @package FluidFontForge
 * @version 4.2.0
 * @author Jim R (JimRWeb)
 * @link https://jimrweb.com
 * @since 4.2.0
 *
 * Dependencies:
 * - FontClampAdvanced instance (for size data and rendering)
 * - FontForgeData or FontForgeUtils (for unified size access)
 *
 * Features:
 * - Drag handle interaction with grab/grabbing cursor states
 * - Visual insertion indicators showing drop target
 * - Automatic cleanup of drag state after operations
 * - Support for all tab types (classes, variables, tags, Tailwind)
 * - Touch-friendly drag operations
 */

/**
 * Drag and Drop Controller Class
 *
 * Encapsulates all drag-and-drop functionality for table row reordering.
 * Manages drag state, visual feedback, and size array reordering operations.
 *
 * @class DragDropController
 * @param {FontClampAdvanced} fontClampAdvanced - Reference to main advanced features instance
 *
 * @example
 * const dragDrop = new DragDropController(fontClampAdvanced);
 * dragDrop.bindRowDragEvents(tableRow);
 */
class DragDropController {
  /**
   * Initialize drag and drop controller
   *
   * @param {FontClampAdvanced} fontClampAdvanced - Main advanced features instance
   */
  constructor(fontClampAdvanced) {
    this.advanced = fontClampAdvanced;
    this.dragState = this.initDragState();
  }

  // ========================================================================
  // INITIALIZATION
  // ========================================================================

  /**
   * Initialize drag state object
   *
   * Creates initial state object for tracking drag operations. Maintains
   * state throughout drag lifecycle including drag start, move, and end events.
   *
   * @returns {Object} Initial drag state object
   * @returns {boolean} state.isDragging - Whether drag operation is active
   * @returns {HTMLElement|null} state.draggedRow - Currently dragged row element
   * @returns {number} state.startY - Initial Y coordinate of drag start
   * @returns {number} state.currentY - Current Y coordinate during drag
   * @returns {number} state.offset - Offset between cursor and element
   */
  initDragState() {
    return {
      isDragging: false,
      draggedRow: null,
      startY: 0,
      currentY: 0,
      offset: 0,
    };
  }

  // ========================================================================
  // EVENT BINDING
  // ========================================================================

  /**
   * Bind drag and drop events to a table row
   *
   * Main entry point for enabling drag-and-drop on a table row element.
   * Should be called for each row when creating or updating the table.
   *
   * @param {HTMLElement} row - Table row element to make draggable
   * @returns {void}
   */
  bindRowDragEvents(row) {
    const dragHandle = row.querySelector(".drag-handle");
    if (!dragHandle) return;

    this.bindDragInitiation(row, dragHandle);
    this.bindDragEvents(row);
  }

  /**
   * Bind drag initiation events
   *
   * Sets up mousedown and dragstart events that begin the drag operation.
   * Enables draggable attribute and provides visual feedback when drag starts.
   *
   * @param {HTMLElement} row - Table row element
   * @param {HTMLElement} dragHandle - Drag handle element within row
   * @returns {void}
   */
  bindDragInitiation(row, dragHandle) {
    dragHandle.addEventListener("mousedown", (e) => {
      row.draggable = true;
    });

    row.addEventListener("dragstart", (e) => {
      this.dragState.draggedRow = row;
      e.dataTransfer.effectAllowed = "move";
      e.dataTransfer.setData("text/plain", row.dataset.id);
      row.style.opacity = "0.5";
      row.classList.add("dragging");
    });
  }

  /**
   * Bind drag interaction events
   *
   * Sets up dragenter, dragover, drop, dragend, and dragleave events
   * that handle the drag operation lifecycle and visual feedback.
   *
   * @param {HTMLElement} row - Table row element
   * @returns {void}
   */
  bindDragEvents(row) {
    row.addEventListener("dragenter", (e) => {
      if (this.dragState.draggedRow && this.dragState.draggedRow !== row) {
        this.showDragInsertionIndicator(row);
      }
    });

    row.addEventListener("dragend", (e) => {
      this.cleanupDragState(row);
    });

    row.addEventListener("dragover", (e) => {
      e.preventDefault();
      e.dataTransfer.dropEffect = "move";
    });

    row.addEventListener("drop", (e) => {
      e.preventDefault();
      this.handleRowDrop(row);
    });

    row.addEventListener("dragleave", (e) => {
      this.handleDragLeave();
    });
  }

  // ========================================================================
  // VISUAL FEEDBACK
  // ========================================================================

  /**
   * Show drag insertion indicator
   *
   * Displays visual feedback showing where the dragged row will be inserted.
   * Uses blue border and shadow to indicate drop target location.
   *
   * @param {HTMLElement} row - Target row where dragged item will be inserted
   * @returns {void}
   */
  showDragInsertionIndicator(row) {
    document.querySelectorAll(".size-row").forEach((r) => {
      r.style.borderTop = "";
      r.style.boxShadow = "";
    });

    row.style.borderTop = "4px solid #3b82f6";
    row.style.boxShadow = "0 -2px 8px rgba(59, 130, 246, 0.5)";
  }

  /**
   * Clean up drag state after operation
   *
   * Resets visual feedback and drag state after drag operation completes.
   * Removes opacity, classes, borders, and shadows applied during drag.
   *
   * @param {HTMLElement} row - Row element being dragged
   * @returns {void}
   */
  cleanupDragState(row) {
    row.style.opacity = "1";
    row.classList.remove("dragging");
    row.draggable = false;
    this.dragState.draggedRow = null;

    document.querySelectorAll(".size-row").forEach((r) => {
      r.classList.remove("drag-over");
      r.style.borderTop = "";
      r.style.boxShadow = "";
    });

    const insertionLine = document.getElementById("drag-insertion-line");
    if (insertionLine) insertionLine.remove();
  }

  /**
   * Handle drag leave event
   *
   * Cleans up visual feedback when dragging leaves a row.
   * Uses timeout to prevent flickering during rapid hover changes.
   *
   * @returns {void}
   */
  handleDragLeave() {
    setTimeout(() => {
      if (
        this.dragState.draggedRow &&
        !document.querySelector(".size-row:hover")
      ) {
        document.querySelectorAll(".size-row").forEach((r) => {
          r.style.borderTop = "";
          r.style.boxShadow = "";
        });
      }
    }, 100);
  }

  // ========================================================================
  // DROP HANDLING & REORDERING
  // ========================================================================

  /**
   * Handle row drop operation
   *
   * Processes drop event when dragged row is released over a target row.
   * Validates drop target and triggers reordering if valid.
   *
   * @param {HTMLElement} row - Target row where item was dropped
   * @returns {void}
   */
  handleRowDrop(row) {
    if (!this.dragState.draggedRow || this.dragState.draggedRow === row) return;

    const insertionLine = document.getElementById("drag-insertion-line");
    if (insertionLine) insertionLine.remove();

    this.reorderSizes(this.dragState.draggedRow, row);
  }

  /**
   * Reorder sizes based on drag and drop
   *
   * Updates the underlying size data array to reflect the new order after
   * drag-and-drop operation. Handles reordering for all tab types and
   * triggers UI updates.
   *
   * Process:
   * 1. Get active tab and current sizes array
   * 2. Find indices of dragged and target items
   * 3. Create new array with reordered items
   * 4. Update data source (unified system or direct manipulation)
   * 5. Re-render table and preview
   * 6. Update base value dropdown
   * 7. Mark data as changed
   *
   * @param {HTMLElement} draggedRow - Row element that was dragged
   * @param {HTMLElement} targetRow - Row element where item was dropped
   * @returns {void}
   */
  reorderSizes(draggedRow, targetRow) {
    const activeTab = window.fluifofoCore?.activeTab || "class";
    const sizes = this.advanced.getCurrentSizes();
    const draggedId = parseInt(draggedRow.dataset.id);
    const targetId = parseInt(targetRow.dataset.id);

    const draggedIndex = sizes.findIndex((s) => s.id === draggedId);
    const targetIndex = sizes.findIndex((s) => s.id === targetId);

    if (draggedIndex !== -1 && targetIndex !== -1) {
      const newSizes = [...sizes];
      const [draggedItem] = newSizes.splice(draggedIndex, 1);
      newSizes.splice(targetIndex, 0, draggedItem);

      if (window.FontForgeData) {
        window.FontForgeData.setSizes(activeTab, newSizes);
      } else {
        const [originalDraggedItem] = sizes.splice(draggedIndex, 1);
        sizes.splice(targetIndex, 0, originalDraggedItem);
      }

      this.advanced.renderSizes();
      this.advanced.updatePreview();
      this.advanced.updateBaseValueOptions();
      this.advanced.markDataChanged();

      // Refresh sample panel dropdowns after reordering
      if (
        this.advanced.samplePanel &&
        typeof this.advanced.samplePanel.refreshBaseDropdowns === "function"
      ) {
        this.advanced.samplePanel.refreshBaseDropdowns();
      }
    }
  }

  // ========================================================================
  // CLEANUP
  // ========================================================================

  /**
   * Destroy the drag-drop controller
   *
   * Cleanup method for removing references and resetting state.
   * Call this if the controller needs to be removed from the page.
   *
   * @returns {void}
   */
  destroy() {
    this.dragState = this.initDragState();
    this.advanced = null;
  }
}

// Make available globally for WordPress environment
window.DragDropController = DragDropController;
