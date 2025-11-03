# Fluid Font Forge v5.1.1-rc - Release Candidate

**Release Type:** Release Candidate (Bug Fixes & Content Updates)  
**Release Date:** 2025-11-03  
**Previous Version:** v5.1.0 (2025-10-24)

---

## Overview

Version 5.1.1 is a maintenance release addressing critical tab system bugs discovered during testing, improving user experience, and updating community panel content. All changes are internal improvements with no breaking changes.

---

## 🐛 Critical Bug Fixes

### Tab System Issues

**1. Fixed Tailwind Tab Persistence**
- **Issue:** Tailwind tab selection would reset to "Class" tab on page refresh
- **Root Cause:** PHP `VALID_TABS` constant was missing 'tailwind' entry, causing validation to fail
- **Fix:** Added 'tailwind' to VALID_TABS array (`includes/class-fluid-font-forge.php:92`)
- **Impact:** HIGH - All four tabs now persist correctly across page refreshes

**2. Fixed Tab Switching Crash**
- **Issue:** Tab switching would throw null reference errors in browser console
- **Root Cause:** Missing ID attributes on template elements (`selected-code-title`, `generated-code-title`)
- **Fix:** Added missing ID attributes to template files
  - `templates/admin/selected-css-panel.php`
  - `templates/admin/generated-css-panel.php`
- **Impact:** HIGH - Tab switching now works without errors

**3. Fixed Tab State Persistence**
- **Issue:** Tab selection wasn't saved immediately like other controls (autosave toggle, REM/PX switch)
- **Root Cause:** `switchTab()` function wasn't calling state persistence method
- **Fix:** Added `savePanelStates()` call to `switchTab()` (`assets/js/admin-script.js:493-518`)
- **Impact:** MEDIUM - Tab state now persists immediately on selection

---

## ✨ User Experience Improvements

**1. Fixed Scroll Jump Behavior**
- **Issue:** Page would jump/scroll unexpectedly when selecting preview rows
- **Root Cause:** `scrollIntoView()` call in row highlight function
- **Fix:** Removed `scrollIntoView()` from `highlightDataTableRow()` (`assets/js/admin-script.js:1688-1699`)
- **Impact:** MEDIUM - Smoother interaction without unwanted page scrolling

**2. Fixed REM Unit Display in Sample Text Panel**
- **Issue:** When REM units selected, Sample Text panel showed "9 px" instead of "0.75 rem"
- **Root Cause:** Hardcoded "px" suffix regardless of unit type setting
- **Fix:** Implemented unit-aware display logic with proper REM calculation (`assets/js/sample-panel.js:412-436`)
  - Calculates current root size at viewport position
  - Converts pixel values to REM with 3 decimal precision
  - Displays correct unit suffix based on settings
- **Impact:** MEDIUM - Accurate visual feedback for REM-based typography systems

---

## 📝 Content Updates

### Community Panel Enhancements

Updated community panel content to reflect current project status:

1. **Section Title:** "Community & Support" → "Community & Tools"
2. **FSF Status:** "Submitted to WordPress.org" → "In review at WordPress.org"
3. **Project Hub:** Added jimrforge.com link with "Coming soon" messaging
4. **Feedback Button:** Shortened text from "Suggestions & Feedback" → "Feedback"
5. **New Rate Button:** Added "⭐ Rate" button linking to WordPress.org reviews

All changes in `templates/admin/community-panel.php`

---

## 📋 Technical Details

### Files Modified (11 total)

**PHP Backend:**
- `includes/class-fluid-font-forge.php` - VALID_TABS array update

**Templates:**
- `templates/admin/selected-css-panel.php` - Added ID attribute
- `templates/admin/generated-css-panel.php` - Added ID attribute
- `templates/admin/community-panel.php` - Content updates

**JavaScript:**
- `assets/js/admin-script.js` - Tab persistence and scroll behavior fixes
- `assets/js/sample-panel.js` - Unit-aware display implementation

**Documentation:**
- `CHANGELOG.md` - Comprehensive v5.1.1 entry

### Testing Performed

✅ All four tabs (Class, Variables, Tags, Tailwind) persist correctly across page refreshes  
✅ Tab switching updates all UI elements without crashes or console errors  
✅ Sample Text panel displays correct units (REM/PX) based on settings  
✅ No unwanted scroll jumps when selecting preview items  
✅ Community panel displays updated content with all links functional  
✅ No breaking changes - full backward compatibility maintained

---

## 🔄 Upgrade Notes

**From v5.1.0:**
- Direct upgrade with no special considerations
- All changes are internal improvements
- No settings migration required
- No user action needed

**Breaking Changes:** None

---

## 📦 Installation

1. Download `fluid-font-forge.zip` from this release
2. In WordPress admin, go to Plugins → Add New → Upload Plugin
3. Upload the zip file and click "Install Now"
4. Activate the plugin

**For existing users:** Simply update the plugin normally through WordPress admin.

---

## 🧪 Release Candidate Testing

This is a **Release Candidate** build. Please test thoroughly before using in production:

1. **Tab Switching:** Verify all four tabs work correctly
2. **Tab Persistence:** Refresh page and confirm tab selection persists
3. **Sample Text:** Check REM/PX display updates correctly with viewport slider
4. **Preview Selection:** Confirm no unwanted scroll jumps
5. **Community Panel:** Verify all links work

Please report any issues on [GitHub Issues](https://github.com/Mij-Strebor/fluid-font-forge/issues).

---

## 🤝 Support & Community

- **Documentation:** [jimrforge.com](https://jimrforge.com) (coming soon)
- **Support:** [WordPress.org Support Forum](https://wordpress.org/support/plugin/fluid-font-forge/)
- **GitHub:** [Mij-Strebor/fluid-font-forge](https://github.com/Mij-Strebor/fluid-font-forge)
- **Rate & Review:** [WordPress.org Reviews](https://wordpress.org/support/plugin/fluid-font-forge/reviews/#new-post)

---

## 👤 Credits

**Author:** Jim Roberts (Jim R Forge)  
**Generated with:** [Claude Code](https://claude.com/claude-code)  
**License:** GPL-2.0-or-later

---

**Full Changelog:** [CHANGELOG.md](CHANGELOG.md#511---2025-11-03)
