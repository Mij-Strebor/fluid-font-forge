# Fluid Font Forge - Complete Test Plan

**Plugin:** Fluid Font Forge (FFF)
**Purpose:** Comprehensive testing checklist for all releases
**Last Updated:** 2025-11-04 (v5.1.1-rc)

---

## Pre-Test Setup

**Environment:**
- WordPress version: ___________
- PHP version: ___________
- Browser(s): Chrome, Firefox, Safari, Edge
- Test site URL: ___________

**Pre-test Checklist:**
- [ ] Clear browser cache
- [ ] Deactivate all other plugins (test in isolation first)
- [ ] Use default WordPress theme (Twenty Twenty-Four)
- [ ] Check WordPress debug.log is empty

---

## Section 1: Initial Load & Version Verification

### 1.1 Plugin Activation
- [ ] **Test:** Activate Fluid Font Forge from Plugins page
- [ ] **Expected:** No PHP errors in debug.log
- [ ] **Expected:** Success message displayed
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Notes:** ___________

### 1.2 Admin Page Access
- [ ] **Test:** Navigate to Tools → Fluid Font Forge
- [ ] **Expected:** Admin page loads without errors
- [ ] **Expected:** No JavaScript console errors (F12)
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Notes:** ___________

### 1.3 Version Number Display
- [ ] **Test:** Check version number in page header
- [ ] **Expected:** Displays correct version (e.g., "Version 5.1.1")
- [ ] **Expected:** No debug text (no "TESTING", "CACHE TEST", etc.)
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Version Shown:** ___________

### 1.4 Page Title
- [ ] **Test:** Check h1 title in forge header section
- [ ] **Expected:** Shows "Fluid Font Forge" (no debug text)
- [ ] **Expected:** Title positioned in top third of forge header
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Notes:** ___________

---

## Section 2: Collapsible Panels

### 2.1 About Panel - Initial State
- [ ] **Test:** Check About FFF panel on page load
- [ ] **Expected:** Panel is initially expanded
- [ ] **Result:** ✅ Pass / ❌ Fail

### 2.2 About Panel - Content
- [ ] **Test:** Read About Fluid Font Forge text
- [ ] **Expected:** Clear, professional description
- [ ] **Expected:** No spelling or grammar errors
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Issues Found:** ___________

### 2.3 About Panel - Toggle Functionality
- [ ] **Test:** Click to collapse About panel, then click to reopen
- [ ] **Expected:** Panel smoothly collapses and expands
- [ ] **Expected:** Icon changes (+ to - or arrow rotation)
- [ ] **Result:** ✅ Pass / ❌ Fail

### 2.4 About Panel - Persistence
- [ ] **Test:** Collapse About panel, then refresh browser (F5)
- [ ] **Expected:** About panel remains collapsed after refresh
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Notes:** ___________

### 2.5 How To Use Panel - Initial State
- [ ] **Test:** Check How To Use FFF panel on page load
- [ ] **Expected:** Panel is initially expanded
- [ ] **Result:** ✅ Pass / ❌ Fail

### 2.6 How To Use Panel - Content
- [ ] **Test:** Read How To Use Fluid Font Forge text
- [ ] **Expected:** Clear, accurate instructions
- [ ] **Expected:** No spelling or grammar errors
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Issues Found:** ___________

### 2.7 How To Use Panel - Toggle Functionality
- [ ] **Test:** Click to collapse How To Use panel, then reopen
- [ ] **Expected:** Panel smoothly collapses and expands
- [ ] **Result:** ✅ Pass / ❌ Fail

### 2.8 How To Use Panel - Persistence
- [ ] **Test:** Collapse How To Use panel, then refresh browser
- [ ] **Expected:** How To Use panel remains collapsed after refresh
- [ ] **Result:** ✅ Pass / ❌ Fail

---

## Section 3: Font Preview Functionality

### 3.1 Default Font Display
- [ ] **Test:** Use WhatFont extension on Sample Text displays and Font Scale columns
- [ ] **Expected:** Default font is "Segoe UI" (or system default)
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Font Detected:** ___________

### 3.2 Preview Font - Labels & Tooltips
- [ ] **Test:** Hover over "Preview Font" label
- [ ] **Expected:** Clear tooltip explaining Google Fonts CSS URL format
- [ ] **Result:** ✅ Pass / ❌ Fail

- [ ] **Test:** Hover over font URL input box
- [ ] **Expected:** Helpful tooltip with example
- [ ] **Result:** ✅ Pass / ❌ Fail

- [ ] **Test:** Hover over Reset button
- [ ] **Expected:** Tooltip explains reset functionality
- [ ] **Result:** ✅ Pass / ❌ Fail

### 3.3 Google Font Loading
- [ ] **Test:** Enter this URL in Preview Font input:
  ```
  https://fonts.googleapis.com/css2?family=Poppins:ital,wght@1,900&display=swap
  ```
- [ ] **Expected:** Font name displays as "Poppins"
- [ ] **Expected:** WhatFont shows "Poppins" on all 4 preview displays:
  - Sample Text: Titles
  - Sample Text: Text
  - Font Scale: Title column
  - Font Scale: Text column
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Font Detected:** ___________

### 3.4 Preview Font Reset
- [ ] **Test:** Click Reset button after loading custom font
- [ ] **Expected:** Input box shows placeholder hint text
- [ ] **Expected:** Font name shows "Segoe UI" (or system default)
- [ ] **Expected:** WhatFont shows default font on all 4 preview displays
- [ ] **Result:** ✅ Pass / ❌ Fail

---

## Section 4: Autosave & Save Functionality

### 4.1 Autosave Initial State
- [ ] **Test:** Check autosave checkbox on page load
- [ ] **Expected:** Checkbox is initially unchecked
- [ ] **Result:** ✅ Pass / ❌ Fail

### 4.2 Save Required Without Autosave
- [ ] **Test:** Change "Min Viewport Font Size" value, then refresh browser
- [ ] **Expected:** Min Viewport Font Size is restored to original value (change lost)
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Original Value:** ___________

### 4.3 Save Button Tooltip
- [ ] **Test:** Hover over Save button
- [ ] **Expected:** Clear tooltip explaining save functionality
- [ ] **Expected:** No spelling or grammar errors
- [ ] **Result:** ✅ Pass / ❌ Fail

### 4.4 Save Button Functionality
- [ ] **Test:** Change Min Viewport Font Size, click Save button
- [ ] **Expected:** Ready indicator flashes green briefly
- [ ] **Expected:** No JavaScript console errors
- [ ] **Result:** ✅ Pass / ❌ Fail

### 4.5 Save Persistence
- [ ] **Test:** Change Min Viewport Font Size, click Save, refresh browser
- [ ] **Expected:** Min Viewport Font Size retains the new value
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **New Value:** ___________

### 4.6 Autosave Enabled
- [ ] **Test:** Enable autosave checkbox
- [ ] **Test:** Change a setting (don't click Save)
- [ ] **Test:** Wait 3-5 seconds
- [ ] **Expected:** Ready indicator auto-saves (brief green flash)
- [ ] **Result:** ✅ Pass / ❌ Fail

---

## Section 5: Settings Panel

### 5.1 Settings Tooltips
- [ ] **Test:** Hover over each of the 10 setting labels/inputs:
  - Min Root Size
  - Max Root Size
  - Min Viewport
  - Max Viewport
  - Min Scale
  - Max Scale
  - Heading Line Height
  - Body Line Height
  - Unit Type (REM/PX)
  - Base Size selector
- [ ] **Expected:** Each has clear, helpful tooltip
- [ ] **Expected:** No spelling or grammar errors in tooltips
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Issues Found:** ___________

### 5.2 Settings Input Validation
- [ ] **Test:** Change each numeric input to valid values
- [ ] **Expected:** Values accept valid input
- [ ] **Result:** ✅ Pass / ❌ Fail

- [ ] **Test:** Try entering invalid values (text, negative numbers)
- [ ] **Expected:** Validation prevents or corrects invalid input
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Notes:** ___________

### 5.3 Settings Reset Button
- [ ] **Test:** Change multiple setting values, then click Reset button
- [ ] **Expected:** All settings restore to defaults:
  - Min Root Size: 12px
  - Max Root Size: 20px
  - Min Viewport: 375px
  - Max Viewport: 1620px
  - Min Scale: 1.125
  - Max Scale: 1.333
  - Heading Line Height: 1.2
  - Body Line Height: 1.4
- [ ] **Result:** ✅ Pass / ❌ Fail

---

## Section 6: Unit Switching (REM/PX)

### 6.1 Switch to REM Units
- [ ] **Test:** Select one entry in data table, then click REM button
- [ ] **Expected:** Data table shows REM values (e.g., "0.750 rem", "1.250 rem")
- [ ] **Expected:** Selected Class CSS shows REM units
- [ ] **Expected:** Generated CSS shows REM units
- [ ] **Result:** ✅ Pass / ❌ Fail

### 6.2 Switch to PX Units
- [ ] **Test:** Select one entry in data table, then click PX button
- [ ] **Expected:** Data table shows PX values (e.g., "12 px", "20 px")
- [ ] **Expected:** Selected Class CSS shows PX units
- [ ] **Expected:** Generated CSS shows PX units
- [ ] **Result:** ✅ Pass / ❌ Fail

### 6.3 Sample Text Unit Display (REM)
- [ ] **Test:** Select REM units, move viewport slider
- [ ] **Expected:** Titles size shows correct REM value (e.g., "2.441 rem")
- [ ] **Expected:** Text size shows correct REM value (e.g., "0.750 rem")
- [ ] **Expected:** NO "px" suffix when REM is selected
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Bug:** v5.1.0 showed "9 px" instead of "0.75 rem" - FIXED in v5.1.1

### 6.4 Sample Text Unit Display (PX)
- [ ] **Test:** Select PX units, move viewport slider
- [ ] **Expected:** Titles size shows PX value (e.g., "48 px")
- [ ] **Expected:** Text size shows PX value (e.g., "15 px")
- [ ] **Result:** ✅ Pass / ❌ Fail

---

## Section 7: Data Table & Calculations

### 7.1 Default Values - PX Units
- [ ] **Test:** With default settings, select PX units
- [ ] **Expected:** "medium" row (or equivalent) shows:
  - MIN SIZE: 12 px
  - MAX SIZE: 20 px
- [ ] **Result:** ✅ Pass / ❌ Fail

### 7.2 Default Values - REM Units
- [ ] **Test:** With default settings, select REM units
- [ ] **Expected:** "medium" row (or equivalent) shows:
  - MIN SIZE: 0.750 rem
  - MAX SIZE: 1.250 rem
- [ ] **Result:** ✅ Pass / ❌ Fail

### 7.3 Calculation Independence
- [ ] **Test:** Change Min Viewport Font Size to 8px (from default 12px)
- [ ] **Expected:** Data table calculations DO NOT change
- [ ] **Expected:** "medium" row still shows same MIN/MAX values
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Notes:** Viewport font size affects root size for REM conversion, not the table calculations

### 7.4 Data Table Row Selection
- [ ] **Test:** Click on different rows in data table
- [ ] **Expected:** Selected row highlights
- [ ] **Expected:** Selected Class CSS updates with that row's values
- [ ] **Expected:** NO unwanted page scroll/jump
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Bug:** v5.1.0 had scroll jump - FIXED in v5.1.1 (removed scrollIntoView)

---

## Section 8: Tab Functionality (CRITICAL)

### 8.1 All Four Tabs Present
- [ ] **Test:** Check tab buttons at top of data section
- [ ] **Expected:** 4 tabs visible: Classes, Variables, Tags, Tailwind
- [ ] **Result:** ✅ Pass / ❌ Fail

### 8.2 Classes Tab
- [ ] **Test:** Click Classes tab
- [ ] **Expected:** Tab activates without errors
- [ ] **Expected:** Data table NAME column shows class names (e.g., ".medium", ".large")
- [ ] **Expected:** Selected CSS shows CSS class selectors
- [ ] **Expected:** Generated CSS shows CSS class format
- [ ] **Result:** ✅ Pass / ❌ Fail

### 8.3 Variables Tab
- [ ] **Test:** Click Variables tab
- [ ] **Expected:** Tab activates without errors
- [ ] **Expected:** Data table NAME column shows CSS variable names (e.g., "--fs-md", "--fs-lg")
- [ ] **Expected:** Selected CSS shows :root with custom properties
- [ ] **Expected:** Generated CSS shows CSS variable format
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Bug:** v5.1.0 showed class names instead of variable names - FIXED in v5.1.1

### 8.4 Tags Tab
- [ ] **Test:** Click Tags tab
- [ ] **Expected:** Tab activates without errors
- [ ] **Expected:** Data table NAME column shows HTML tags (e.g., "p", "h3", "h2")
- [ ] **Expected:** Selected CSS shows tag selectors
- [ ] **Expected:** Generated CSS shows tag format
- [ ] **Result:** ✅ Pass / ❌ Fail

### 8.5 Tailwind Tab
- [ ] **Test:** Click Tailwind tab
- [ ] **Expected:** Tab activates without errors
- [ ] **Expected:** Data table NAME column shows Tailwind classes (e.g., "text-md", "text-lg")
- [ ] **Expected:** Selected CSS shows Tailwind config format
- [ ] **Result:** ✅ Pass / ❌ Fail

### 8.6 Tab Persistence - Classes
- [ ] **Test:** Select Classes tab, refresh browser (F5)
- [ ] **Expected:** Classes tab remains selected after refresh
- [ ] **Result:** ✅ Pass / ❌ Fail

### 8.7 Tab Persistence - Variables
- [ ] **Test:** Select Variables tab, refresh browser
- [ ] **Expected:** Variables tab remains selected after refresh
- [ ] **Result:** ✅ Pass / ❌ Fail

### 8.8 Tab Persistence - Tags
- [ ] **Test:** Select Tags tab, refresh browser
- [ ] **Expected:** Tags tab remains selected after refresh
- [ ] **Result:** ✅ Pass / ❌ Fail

### 8.9 Tab Persistence - Tailwind (CRITICAL)
- [ ] **Test:** Select Tailwind tab, refresh browser
- [ ] **Expected:** Tailwind tab remains selected after refresh
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Bug:** v5.1.0 reset Tailwind to Classes tab - FIXED in v5.1.1 (added 'tailwind' to VALID_TABS)

### 8.10 Tab Title Updates
- [ ] **Test:** Switch between tabs, observe Selected CSS and Generated CSS panel titles
- [ ] **Expected:** Titles update to reflect current tab:
  - Classes: "Selected Class CSS" / "Generated CSS (All Classes)"
  - Variables: "Selected Variable CSS" / "Generated CSS (All Variables)"
  - Tags: "Selected Tag CSS" / "Generated CSS (All Tags)"
  - Tailwind: "Selected Tailwind Config" / "Generated Tailwind Config"
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Bug:** v5.1.0 crashed due to missing IDs - FIXED in v5.1.1 (added id attributes)

---

## Section 9: Sample Text Panel

### 9.1 Panel Visibility
- [ ] **Test:** Scroll to Sample Text panel
- [ ] **Expected:** Panel displays with brown header "Sample Text"
- [ ] **Expected:** Viewport slider visible
- [ ] **Result:** ✅ Pass / ❌ Fail

### 9.2 Titles and Text Dropdowns
- [ ] **Test:** Check "Titles" dropdown menu
- [ ] **Expected:** Shows all font sizes from current tab
- [ ] **Expected:** Default selection is largest size (e.g., "xxl" or "h1")
- [ ] **Result:** ✅ Pass / ❌ Fail

- [ ] **Test:** Check "Text" dropdown menu
- [ ] **Expected:** Shows all font sizes from current tab
- [ ] **Expected:** Default selection is medium size (e.g., "medium" or "p")
- [ ] **Result:** ✅ Pass / ❌ Fail

### 9.3 Viewport Slider - Basic Function
- [ ] **Test:** Move viewport slider left to right
- [ ] **Expected:** Viewport display updates (e.g., "375px" → "1620px")
- [ ] **Expected:** Device type updates (e.g., "Mobile (portrait)" → "Desktop")
- [ ] **Expected:** Sample text size changes smoothly
- [ ] **Result:** ✅ Pass / ❌ Fail

### 9.4 Viewport Slider - Size Calculation
- [ ] **Test:** Move slider to minimum position
- [ ] **Expected:** Titles and Text display minimum font sizes
- [ ] **Expected:** Sizes match data table MIN values for selected sizes
- [ ] **Result:** ✅ Pass / ❌ Fail

- [ ] **Test:** Move slider to maximum position
- [ ] **Expected:** Titles and Text display maximum font sizes
- [ ] **Expected:** Sizes match data table MAX values for selected sizes
- [ ] **Result:** ✅ Pass / ❌ Fail

### 9.5 Sample Text Updates on Dropdown Change
- [ ] **Test:** Change "Titles" dropdown selection
- [ ] **Expected:** Sample text titles immediately resize
- [ ] **Expected:** Titles size display updates
- [ ] **Result:** ✅ Pass / ❌ Fail

- [ ] **Test:** Change "Text" dropdown selection
- [ ] **Expected:** Sample body text immediately resizes
- [ ] **Expected:** Text size display updates
- [ ] **Result:** ✅ Pass / ❌ Fail

### 9.6 Device Type Display
- [ ] **Test:** Move slider to each range, verify device type label:
  - < 576px: "Mobile (portrait)"
  - 576-767px: "Mobile (landscape)"
  - 768-991px: "Tablet (portrait)"
  - 992-1199px: "Tablet (landscape)"
  - 1200-1919px: "Desktop"
  - ≥ 1920px: "Big Screen"
- [ ] **Result:** ✅ Pass / ❌ Fail

---

## Section 10: Drag & Drop Functionality

### 10.1 Row Reordering
- [ ] **Test:** Drag a data table row to a different position
- [ ] **Expected:** Row visually moves during drag
- [ ] **Expected:** Drop zones highlight when hovering
- [ ] **Result:** ✅ Pass / ❌ Fail

### 10.2 Reorder Persistence
- [ ] **Test:** Reorder rows, click Save, refresh browser
- [ ] **Expected:** New order persists after refresh
- [ ] **Result:** ✅ Pass / ❌ Fail

### 10.3 Drag Visual Feedback
- [ ] **Test:** During drag, observe visual states
- [ ] **Expected:** Dragged row has opacity change or highlight
- [ ] **Expected:** Valid drop zones show highlight
- [ ] **Expected:** Invalid areas don't accept drop
- [ ] **Result:** ✅ Pass / ❌ Fail

---

## Section 11: Add/Delete Size Entries

### 11.1 Add New Size
- [ ] **Test:** Click "Add Size" button (or equivalent)
- [ ] **Expected:** New row appears in data table
- [ ] **Expected:** Row is editable with default values
- [ ] **Result:** ✅ Pass / ❌ Fail

### 11.2 Delete Size Entry
- [ ] **Test:** Click delete/trash icon on a row
- [ ] **Expected:** Confirmation prompt appears (if implemented)
- [ ] **Expected:** Row is removed from table
- [ ] **Result:** ✅ Pass / ❌ Fail

### 11.3 Add/Delete Persistence
- [ ] **Test:** Add new row, delete old row, save, refresh
- [ ] **Expected:** Changes persist after refresh
- [ ] **Result:** ✅ Pass / ❌ Fail

---

## Section 12: CSS Output & Copy-to-Clipboard

### 12.1 Selected CSS Display
- [ ] **Test:** Select a row in data table
- [ ] **Expected:** "Selected [Type] CSS" panel updates immediately
- [ ] **Expected:** Shows correct CSS for selected size
- [ ] **Expected:** CSS is properly formatted
- [ ] **Result:** ✅ Pass / ❌ Fail

### 12.2 Generated CSS Display
- [ ] **Test:** Check "Generated CSS (All [Type])" panel
- [ ] **Expected:** Shows CSS for ALL sizes in current tab
- [ ] **Expected:** CSS is properly formatted
- [ ] **Expected:** All sizes from data table are included
- [ ] **Result:** ✅ Pass / ❌ Fail

### 12.3 Copy-to-Clipboard - Selected
- [ ] **Test:** Click copy button on Selected CSS panel
- [ ] **Expected:** Success message appears briefly
- [ ] **Expected:** Paste (Ctrl+V) in text editor shows CSS
- [ ] **Result:** ✅ Pass / ❌ Fail

### 12.4 Copy-to-Clipboard - Generated
- [ ] **Test:** Click copy button on Generated CSS panel
- [ ] **Expected:** Success message appears briefly
- [ ] **Expected:** Paste (Ctrl+V) shows complete CSS for all sizes
- [ ] **Result:** ✅ Pass / ❌ Fail

---

## Section 13: Browser Compatibility

### 13.1 Chrome
- [ ] **Test:** Run full test suite in Google Chrome
- [ ] **Browser Version:** ___________
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Issues:** ___________

### 13.2 Firefox
- [ ] **Test:** Run full test suite in Mozilla Firefox
- [ ] **Browser Version:** ___________
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Issues:** ___________

### 13.3 Safari (if available)
- [ ] **Test:** Run full test suite in Safari
- [ ] **Browser Version:** ___________
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Issues:** ___________

### 13.4 Edge
- [ ] **Test:** Run full test suite in Microsoft Edge
- [ ] **Browser Version:** ___________
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Issues:** ___________

---

## Section 14: Console & Error Checking

### 14.1 JavaScript Console
- [ ] **Test:** Open browser DevTools (F12), check Console tab
- [ ] **Expected:** No JavaScript errors
- [ ] **Expected:** No warnings related to FFF code
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Errors Found:** ___________

### 14.2 Network Tab
- [ ] **Test:** Check Network tab for AJAX requests
- [ ] **Expected:** Save operations return 200 OK status
- [ ] **Expected:** No 404 errors for assets (CSS, JS, images)
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Issues:** ___________

### 14.3 WordPress debug.log
- [ ] **Test:** Check `wp-content/debug.log` file
- [ ] **Expected:** No PHP errors from FFF
- [ ] **Expected:** No warnings from FFF
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Errors Found:** ___________

---

## Section 15: Performance & Responsiveness

### 15.1 Page Load Time
- [ ] **Test:** Measure initial page load time
- [ ] **Expected:** Page loads in < 3 seconds
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Load Time:** ___________ seconds

### 15.2 Responsive Design
- [ ] **Test:** Resize browser window to mobile size (< 768px)
- [ ] **Expected:** Interface adapts to small screens
- [ ] **Expected:** All controls remain usable
- [ ] **Result:** ✅ Pass / ❌ Fail

### 15.3 Large Data Sets
- [ ] **Test:** Add 20+ size entries
- [ ] **Expected:** Interface remains responsive
- [ ] **Expected:** No lag when scrolling or selecting
- [ ] **Result:** ✅ Pass / ❌ Fail

---

## Section 16: Plugin Conflicts

### 16.1 Common Plugin Conflicts
- [ ] **Test:** Activate alongside these common plugins:
  - Elementor
  - WooCommerce
  - Yoast SEO
  - Contact Form 7
- [ ] **Expected:** No JavaScript conflicts
- [ ] **Expected:** FFF remains fully functional
- [ ] **Result:** ✅ Pass / ❌ Fail
- [ ] **Conflicts Found:** ___________

---

## Test Summary

**Test Date:** ___________
**Tested By:** ___________
**Version Tested:** ___________
**Environment:** ___________

**Overall Result:**
- [ ] ✅ All tests passed - Ready for release
- [ ] ⚠️ Minor issues found - Document and decide
- [ ] ❌ Critical issues found - DO NOT release

**Critical Issues Found:**
1. ___________
2. ___________
3. ___________

**Minor Issues Found:**
1. ___________
2. ___________
3. ___________

**Tester Notes:**
___________________________________________________________
___________________________________________________________
___________________________________________________________

**Recommendation:**
- [ ] Approve for release
- [ ] Require fixes before release
- [ ] Require additional testing

---

## Known Issues (Document Only)

### v5.1.1-rc Known Issues
- None at release time

### Issues Fixed in v5.1.1 (from v5.1.0)
1. ✅ Tailwind tab persistence (added to VALID_TABS)
2. ✅ Tab switching crash (added missing ID attributes)
3. ✅ Tab state persistence (added savePanelStates call)
4. ✅ Scroll jump behavior (removed scrollIntoView)
5. ✅ REM unit display (implemented unit-aware logic)

---

## Regression Testing Checklist

**After each bug fix, verify these were NOT broken:**

- [ ] All 4 tabs still work
- [ ] Tab persistence for all tabs
- [ ] REM/PX switching still works
- [ ] Sample Text panel still functions
- [ ] Viewport slider still works
- [ ] Save/Autosave still works
- [ ] Drag & drop still works
- [ ] Copy-to-clipboard still works
- [ ] No new console errors

---

**End of Test Plan**
