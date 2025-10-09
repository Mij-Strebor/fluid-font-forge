# Fluid Font Forge Changelog

## [4.3.0] - 2025-10-08

### Changed - Architecture Modernization
- **BREAKING**: Implemented PHP namespaces (`JimRWeb\FluidFontForge`) for professional code organization
- Renamed `FluidFontForgeDefaultData` class to `DefaultDataFactory` for consistency with filename conventions
- Updated all class references throughout codebase to use fully qualified namespaces
- Enhanced file header documentation in main plugin class with comprehensive PHPDoc blocks
- Improved code maintainability and prevented potential naming conflicts with other plugins

### Technical Details
- Added namespace declarations to all PHP class files
- Updated class instantiation in bootstrap file to use namespaced class names
- Fixed template file constant references to use fully qualified class names
- Added proper `use` statements for global classes (Exception)
- All classes now follow WordPress coding standards for modern PHP development

### Developer Notes
- No database changes required - this is a code-only refactoring
- Plugin functionality remains identical - all features work as before
- Existing saved settings and data are fully compatible
- This change prepares the plugin for WordPress Plugin Directory submission

---

## [4.2.0] - Previous Release

### Added
- Sample Text panel where users can set scaling on displays for titles and text
- Interactive slider to preview font choices across all viewport range devices

### Enhanced
- Revised project folder structure for better source management
- Improved code documentation of CSS with KSS format
- Cleaned up code documentation of main PHP module
- Restructured to move HTML panels to templates for better code organization
- Extracted Sample Panel functionality into separate module 
- New SamplePanelController class for modular sample text preview management
- Extracted CSS Generator functionality into separate module 
- New CSSGeneratorController class for modular CSS generation and copy operations
- Added Reset button for Preview Font to clear custom font and return to default
- Save button resized to match Reset button for consistent UI
- Improved error handling for invalid font URLs

### Fixed
- Removed "settings" option from plugin page
- Corrected About panel template include path
- Preview Font feature now works with Google Fonts CSS API URLs
- Font loading handles both direct .woff2 files and Google Fonts CSS imports
- CSS variable --preview-font properly set on document root element using setProperty
- Font name display now shows actual font family name (e.g., "Monoton") instead of filename
- Local file:// URLs properly blocked with helpful error message

---

## Version History

For detailed version history prior to 4.2.0, see Git commit history.