# Changelog

### Added
- A Sample Text panel has been added where the usr can set the scaling on displays for titles and text and use a slider to see what their font choices would look like on all size devices covered by the veiwport range.

### Enhanced
- Revised project folder structure for better source management
- Revise code documentation of CSS
- Cleanup code documentation of main php module
- Revise structure to move HTML panels to templates for better code orginization
- Extracted Sample Panel functionality into separate module 
- New SamplePanelController class for modular sample text preview management
- Extracted CSS Generator functionality into separate module 
- New CSSGeneratorController class for modular CSS generation and copy operations
- Added Reset button for Preview Font to clear custom font and return to default
- Save button resized to match Reset button for consistent UI
- Improved error handling for invalid font URLs

### Fixed
- Remove "settings" option from plugin page
- About panel template include path corrected
- Preview Font feature now works with Google Fonts CSS API URLs
- Font loading handles both direct .woff2 files and Google Fonts CSS imports
- CSS variable --preview-font properly set on document root element using setProperty
- Font name display now shows actual font family name (e.g., "Monoton") instead of filename
- Local file:// URLs properly blocked with helpful error message