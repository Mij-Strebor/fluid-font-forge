'HEADER'
---
layout: default
title: Quick Start
---# Quick Start Guide

Get started with Fluid Font Forge in 5 minutes and generate your first responsive typography system.

## Installation

1. **Activate the Plugin**
   - Navigate to **Plugins** in WordPress admin
   - Find "Fluid Font Forge" and click **Activate**

2. **Access the Interface**
   - Go to **Tools → Fluid Font Forge**
   - The admin interface will load with default settings

## Your First Font Scale
---
layout: default
title: Quick Start Guide
---

[← Back to Documentation](index)

# Quick Start Guide

Get started with Fluid Font Forge in 5 minutes and generate your first responsive typography system.

## Installation

1. **Activate the Plugin**
   - Navigate to **Plugins** in WordPress admin
   - Find "Fluid Font Forge" and click **Activate**

2. **Access the Interface**
   - Go to **Tools → Fluid Font Forge**
   - The admin interface will load with default settings

## Your First Font Scale

### Step 1: Review Default Settings (30 seconds)

The plugin starts with sensible defaults:
- **Viewport Range**: 375px (mobile) to 1620px (desktop)
- **Font Units**: REM (accessible, respects user preferences)
- **Base Sizes**: 16px minimum, 20px maximum
- **Scaling**: Major Second (1.125) for both viewports

**Action**: Leave these defaults for now, or adjust if you have specific requirements.

### Step 2: Add Your First Font Sizes (2 minutes)

The data table comes pre-populated with sample sizes. Let's create a simple 3-size system:

1. **Clear the existing data** (optional) or work with samples
2. **Add three sizes** using the table:

| Name | Class/Variable | Min Size | Max Size | Line Height |
|------|---------------|----------|----------|-------------|
| Large | large | 1.500rem | 2.000rem | 1.3 |
| Base | base | 1.000rem | 1.000rem | 1.6 |
| Small | small | 0.875rem | 0.875rem | 1.5 |

**Tips:**
- Name: Descriptive identifier (e.g., "heading-1", "body-text")
- Min/Max Size: Set equal values (e.g., 1rem = 1rem) for static sizing
- Line Height: 1.2-1.3 for headings, 1.5-1.6 for body text

### Step 3: Preview Your Fonts (1 minute)

1. **Expand "Font Scale" section**
   - See side-by-side comparison at min/max viewports
   - Fonts display at actual sizes

2. **Try the "Sample Text" section**
   - Use the viewport slider to see real-time scaling
   - Select different sizes from dropdowns
   - Watch fonts interpolate smoothly

3. **Optional: Load a Custom Font**
   - Paste a Google Fonts URL in "Preview Font" field
   - Example: `https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap`
   - See your actual font in all previews

### Step 4: Generate CSS (1 minute)

1. **Choose Your Output Format** (tabs at top):
   - **Class**: CSS classes like `.large`, `.base`, `.small`
   - **Variables**: CSS custom properties like `--fs-large`
   - **Tailwind**: Direct Tailwind config object
   - **Tags**: Styles for HTML tags like `h1`, `p`

2. **Copy the Generated CSS**
   - Scroll to "Generated CSS (All Classes)"
   - Click the **Copy CSS** button
   - Paste into your stylesheet

3. **Save Your Settings** (optional)
   - Click **save** button or enable **Autosave**
   - Settings persist between sessions

## Example Output

### CSS Classes (Class Tab)
```css
.large {
  font-size: clamp(1.500rem, 1.219rem + 1.41vw, 2.000rem);
  line-height: 1.3;
}

.base {
  font-size: clamp(1.000rem, 1.000rem + 0vw, 1.000rem);
  line-height: 1.6;
}

.small {
  font-size: clamp(0.875rem, 0.875rem + 0vw, 0.875rem);
  line-height: 1.5;
}
```

### CSS Variables (Variables Tab)
```css
:root {
  --fs-large: clamp(1.500rem, 1.219rem + 1.41vw, 2.000rem);
  --fs-base: clamp(1.000rem, 1.000rem + 0vw, 1.000rem);
  --fs-small: clamp(0.875rem, 0.875rem + 0vw, 0.875rem);
}
```

### Usage in HTML
```html
<!-- Using Classes -->
<h1 class="large">Welcome to Our Site</h1>
<p class="base">This text scales responsively across all devices.</p>
<small class="small">Fine print text</small>

<!-- Using Variables -->
<h1 style="font-size: var(--fs-large);">Welcome to Our Site</h1>
<p style="font-size: var(--fs-base);">Responsive paragraph text.</p>
```

## Understanding the Output

### What is `clamp()`?

The CSS `clamp()` function has three values:
```css
font-size: clamp(min, preferred, max);
```

- **min**: Smallest size (at minimum viewport width)
- **preferred**: Fluid calculation that scales with viewport
- **max**: Largest size (at maximum viewport width)

### Static vs. Fluid Sizing

**Static** (min = max):
```css
/* Stays 1rem at all viewport sizes */
font-size: clamp(1.000rem, 1.000rem + 0vw, 1.000rem);
```

**Fluid** (min ≠ max):
```css
/* Scales from 1.5rem to 2rem */
font-size: clamp(1.500rem, 1.219rem + 1.41vw, 2.000rem);
```

## Next Steps

### Beginner: Keep It Simple
1. Use the default settings
2. Create 3-5 font sizes for your project
3. Use CSS Classes output
4. Copy and paste into your stylesheet
5. Apply classes in HTML

### Intermediate: Customize Everything
1. Adjust viewport range to match your design
2. Try different scaling ratios for visual hierarchy
3. Use CSS Variables for a design system
4. Create separate groups for headings vs. body text (generate separately)

### Advanced: Multi-Scale Systems
1. Generate heading sizes with dramatic scaling (Major Third → Perfect Fourth)
2. Clear table, generate body text with subtle scaling (Minor Second)
3. Clear table, generate UI elements with minimal scaling
4. Combine all CSS output into one stylesheet
5. Use Tailwind integration for utility-first approach

## Common Workflows

### For Designers
1. Load client's Google Font in "Preview Font"
2. Adjust viewport sizes to match target devices
3. Use Sample Text slider for live client presentations
4. Screenshot different viewport sizes for approval

### For Developers
1. Create font sizes matching your component needs
2. Choose output format matching your CSS methodology
3. Integrate into build process (copy CSS to source)
4. Use Tailwind tab for utility-first frameworks

### For Content Sites
1. Use Tags tab to style semantic HTML automatically
2. Configure once, all `<h1>`, `<h2>`, `<p>` tags styled
3. Content editors use standard HTML, no classes needed
4. Consistent typography across all posts

## Tips for Success

**Do:**
- ✓ Start with defaults and adjust incrementally
- ✓ Use REM units for accessibility
- ✓ Set min = max for sizes that shouldn't scale (body text)
- ✓ Preview at multiple viewport sizes
- ✓ Save your settings before generating new scales

**Don't:**
- ✗ Make too many sizes (5-8 is usually enough)
- ✗ Use extreme viewport ranges (200px to 5000px)
- ✗ Set min root size below 14px (accessibility)
- ✗ Forget to click Save if autosave is disabled
- ✗ Mix incompatible scaling ratios in one generation

## Troubleshooting

**Fonts look too small/large?**
- Adjust **Min Root Size** and **Max Root Size** in Settings
- These control your base font size at viewport limits

**Want more dramatic size differences?**
- Increase **Max Scale** (try Perfect Fourth 1.333 or Golden Ratio 1.618)
- This affects the ratio between adjacent font sizes

**Body text scaling too much?**
- Set Min Size = Max Size for body text elements
- This keeps them static while headings scale

**Preview font not loading?**
- Ensure URL is publicly accessible (https://)
- Google Fonts links work best
- Upload WOFF2 to WordPress Media Library and use that URL

**Copy button not working?**
- Try manually selecting text and copying
- Ensure you're on HTTPS (clipboard API requirement)
- Try different browser

## Get Help

- **📘 [Full User Manual](user-manual)** - Detailed use cases and examples
- **🐛 [Report Issues](https://github.com/Mij-Strebor/fluid-font-forge/issues)** - Found a bug?
- **💬 [Discussions](https://github.com/Mij-Strebor/fluid-font-forge/discussions)** - Ask questions

---

**You're ready!** Generate your first fluid font scale and experience responsive typography that "just works" across all devices.

---

[← Back to Documentation](index) | [User Manual](user-manual) | [Report Issues](https://github.com/Mij-Strebor/fluid-font-forge/issues)
### Step 1: Review Default Settings (30 seconds)

The plugin starts with sensible defaults:
- **Viewport Range**: 375px (mobile) to 1620px (desktop)
- **Font Units**: REM (accessible, respects user preferences)
- **Base Sizes**: 16px minimum, 20px maximum
- **Scaling**: Major Second (1.125) for both viewports

**Action**: Leave these defaults for now, or adjust if you have specific requirements.

### Step 2: Add Your First Font Sizes (2 minutes)

The data table comes pre-populated with sample sizes. Let's create a simple 3-size system:

1. **Clear the existing data** (optional) or work with samples
2. **Add three sizes** using the table:

| Name | Class/Variable | Min Size | Max Size | Line Height |
|------|---------------|----------|----------|-------------|
| Large | large | 1.500rem | 2.000rem | 1.3 |
| Base | base | 1.000rem | 1.000rem | 1.6 |
| Small | small | 0.875rem | 0.875rem | 1.5 |

**Tips:**
- Name: Descriptive identifier (e.g., "heading-1", "body-text")
- Min/Max Size: Set equal values (e.g., 1rem = 1rem) for static sizing
- Line Height: 1.2-1.3 for headings, 1.5-1.6 for body text

### Step 3: Preview Your Fonts (1 minute)

1. **Expand "Font Scale" section**
   - See side-by-side comparison at min/max viewports
   - Fonts display at actual sizes

2. **Try the "Sample Text" section**
   - Use the viewport slider to see real-time scaling
   - Select different sizes from dropdowns
   - Watch fonts interpolate smoothly

3. **Optional: Load a Custom Font**
   - Paste a Google Fonts URL in "Preview Font" field
   - Example: `https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap`
   - See your actual font in all previews

### Step 4: Generate CSS (1 minute)

1. **Choose Your Output Format** (tabs at top):
   - **Class**: CSS classes like `.large`, `.base`, `.small`
   - **Variables**: CSS custom properties like `--fs-large`
   - **Tailwind**: Direct Tailwind config object
   - **Tags**: Styles for HTML tags like `h1`, `p`

2. **Copy the Generated CSS**
   - Scroll to "Generated CSS (All Classes)"
   - Click the **Copy CSS** button
   - Paste into your stylesheet

3. **Save Your Settings** (optional)
   - Click **save** button or enable **Autosave**
   - Settings persist between sessions

## Example Output

### CSS Classes (Class Tab)
```css
.large {
  font-size: clamp(1.500rem, 1.219rem + 1.41vw, 2.000rem);
  line-height: 1.3;
}

.base {
  font-size: clamp(1.000rem, 1.000rem + 0vw, 1.000rem);
  line-height: 1.6;
}

.small {
  font-size: clamp(0.875rem, 0.875rem + 0vw, 0.875rem);
  line-height: 1.5;
}
```

### CSS Variables (Variables Tab)
```css
:root {
  --fs-large: clamp(1.500rem, 1.219rem + 1.41vw, 2.000rem);
  --fs-base: clamp(1.000rem, 1.000rem + 0vw, 1.000rem);
  --fs-small: clamp(0.875rem, 0.875rem + 0vw, 0.875rem);
}
```

### Usage in HTML
```html
<!-- Using Classes -->
<h1 class="large">Welcome to Our Site</h1>
<p class="base">This text scales responsively across all devices.</p>
<small class="small">Fine print text</small>

<!-- Using Variables -->
<h1 style="font-size: var(--fs-large);">Welcome to Our Site</h1>
<p style="font-size: var(--fs-base);">Responsive paragraph text.</p>
```

## Understanding the Output

### What is `clamp()`?

The CSS `clamp()` function has three values:
```css
font-size: clamp(min, preferred, max);
```

- **min**: Smallest size (at minimum viewport width)
- **preferred**: Fluid calculation that scales with viewport
- **max**: Largest size (at maximum viewport width)

### Static vs. Fluid Sizing

**Static** (min = max):
```css
/* Stays 1rem at all viewport sizes */
font-size: clamp(1.000rem, 1.000rem + 0vw, 1.000rem);
```

**Fluid** (min ≠ max):
```css
/* Scales from 1.5rem to 2rem */
font-size: clamp(1.500rem, 1.219rem + 1.41vw, 2.000rem);
```

## Next Steps

### Beginner: Keep It Simple
1. Use the default settings
2. Create 3-5 font sizes for your project
3. Use CSS Classes output
4. Copy and paste into your stylesheet
5. Apply classes in HTML

### Intermediate: Customize Everything
1. Adjust viewport range to match your design
2. Try different scaling ratios for visual hierarchy
3. Use CSS Variables for a design system
4. Create separate groups for headings vs. body text (generate separately)

### Advanced: Multi-Scale Systems
1. Generate heading sizes with dramatic scaling (Major Third → Perfect Fourth)
2. Clear table, generate body text with subtle scaling (Minor Second)
3. Clear table, generate UI elements with minimal scaling
4. Combine all CSS output into one stylesheet
5. Use Tailwind integration for utility-first approach

## Common Workflows

### For Designers
1. Load client's Google Font in "Preview Font"
2. Adjust viewport sizes to match target devices
3. Use Sample Text slider for live client presentations
4. Screenshot different viewport sizes for approval

### For Developers
1. Create font sizes matching your component needs
2. Choose output format matching your CSS methodology
3. Integrate into build process (copy CSS to source)
4. Use Tailwind tab for utility-first frameworks

### For Content Sites
1. Use Tags tab to style semantic HTML automatically
2. Configure once, all `<h1>`, `<h2>`, `<p>` tags styled
3. Content editors use standard HTML, no classes needed
4. Consistent typography across all posts

## Tips for Success

**Do:**
- ✓ Start with defaults and adjust incrementally
- ✓ Use REM units for accessibility
- ✓ Set min = max for sizes that shouldn't scale (body text)
- ✓ Preview at multiple viewport sizes
- ✓ Save your settings before generating new scales

**Don't:**
- ✗ Make too many sizes (5-8 is usually enough)
- ✗ Use extreme viewport ranges (200px to 5000px)
- ✗ Set min root size below 14px (accessibility)
- ✗ Forget to click Save if autosave is disabled
- ✗ Mix incompatible scaling ratios in one generation

## Troubleshooting

**Fonts look too small/large?**
- Adjust **Min Root Size** and **Max Root Size** in Settings
- These control your base font size at viewport limits

**Want more dramatic size differences?**
- Increase **Max Scale** (try Perfect Fourth 1.333 or Golden Ratio 1.618)
- This affects the ratio between adjacent font sizes

**Body text scaling too much?**
- Set Min Size = Max Size for body text elements
- This keeps them static while headings scale

**Preview font not loading?**
- Ensure URL is publicly accessible (https://)
- Google Fonts links work best
- Upload WOFF2 to WordPress Media Library and use that URL

**Copy button not working?**
- Try manually selecting text and copying
- Ensure you're on HTTPS (clipboard API requirement)
- Try different browser

## Get Help

- **📘 [Full User Manual](USER-MANUAL.md)** - Detailed use cases and examples
- **🐛 [Report Issues](https://github.com/jimrweb/fluid-font-forge/issues)** - Found a bug?
- **💬 [Discussions](https://github.com/jimrweb/fluid-font-forge/discussions)** - Ask questions

---

**You're ready!** Generate your first fluid font scale and experience responsive typography that "just works" across all devices.


*Generated for Fluid Font Forge v5.1.2*  
*© 2025 Jim R Forge, JimRForge.com - All rights reserved*