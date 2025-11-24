---
layout: default
title: Fluid Font Forge
---

<div align="center">
  <img src="screenshots/banner.png" alt="Fluid Font Forge" style="max-width: 100%; height: auto; margin: 2rem 0;">
</div>

# Fluid Font Forge

**Professional responsive typography for WordPress**

Generate fluid font scaling with CSS clamp() functions that adapt smoothly across all viewport sizes.

---

## 📚 Documentation

### Getting Started
- **[Quick Start Guide](quick-start)** - Get up and running in 5 minutes
- **[Complete User Manual](user-manual)** - Comprehensive guide with real-world use cases

### User Manual Use Cases
1. **Client Font Consultation** - Interactive client presentations
2. **CSS Classes** - Component library development
3. **CSS Variables** - Design system architecture
4. **Tailwind Integration** - Utility-first frameworks
5. **Semantic HTML** - Automatic tag styling

---

## 🚀 Key Features

- **Mathematical Typography Scaling** - Musical harmony ratios (Minor Second to Golden Ratio)
- **Multiple Output Formats** - CSS classes, custom properties, HTML tags, Tailwind config
- **Real-time Preview** - See fonts at different screen sizes instantly
- **Preview Font Loading** - Test with Google Fonts or custom web fonts
- **Drag & Drop Management** - Reorder font sizes intuitively
- **Copy-to-Clipboard** - One-click CSS copying
- **Autosave System** - Automatic saving with visual feedback

---

## 💡 Quick Example

```css
/* Generate fluid typography in seconds */
.heading {
  font-size: clamp(1.5rem, 1.219rem + 1.41vw, 2rem);
  line-height: 1.3;
}
```

This single line creates a font that:
- Starts at 1.5rem on mobile (375px)
- Scales smoothly to 2rem on desktop (1620px)
- No media queries needed!

---

## 📦 Installation

1. Download or clone to `/wp-content/plugins/fluid-font-forge/`
2. Activate through WordPress admin
3. Navigate to **Tools → Fluid Font Forge**

---

## 🎯 Output Formats

### CSS Classes
```css
.large {
  font-size: clamp(1.25rem, 0.89rem + 1.8vw, 2.67rem);
  line-height: 1.4;
}
```

### CSS Custom Properties
```css
:root {
  --fs-lg: clamp(1.25rem, 0.89rem + 1.8vw, 2.67rem);
}
```

### HTML Tag Styling
```css
h1 {
  font-size: clamp(2.07rem, 1.33rem + 3.7vw, 4.77rem);
  line-height: 1.2;
}
```

### Tailwind Configuration
```javascript
module.exports = {
  theme: {
    extend: {
      fontSize: {
        'base': 'clamp(1.000rem, 0.901rem + 0.49vw, 1.266rem)',
        'lg': 'clamp(1.125rem, 1.001rem + 0.62vw, 1.424rem)'
      }
    }
  }
}
```

---

## 📏 Typography Scaling Options

| Scale Name | Ratio | Best For |
|------------|-------|----------|
| Minor Second | 1.067 | Subtle, tight spacing |
| Major Second | 1.125 | Clean, modern designs |
| Minor Third | 1.200 | Balanced hierarchy |
| Major Third | 1.250 | Strong contrast |
| Perfect Fourth | 1.333 | Bold, dramatic scaling |
| Golden Ratio | 1.618 | Harmonious, classical proportions |

---

## 🌐 Browser Support

CSS clamp() is supported in:
- Chrome 79+ (March 2020)
- Firefox 75+ (April 2020)
- Safari 13.1+ (March 2020)
- Edge 79+ (January 2020)

For older browsers, consider providing fallback font sizes.

---

## 📋 Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **Browser**: Modern browser with CSS clamp() support

---

## 🔗 Quick Links

- [GitHub Repository](https://github.com/jimrweb/fluid-font-forge)
- [Report Issues](https://github.com/jimrweb/fluid-font-forge/issues)
- [Discussions](https://github.com/jimrweb/fluid-font-forge/discussions)
- [Changelog](https://github.com/jimrweb/fluid-font-forge/blob/main/CHANGELOG.md)

---

## 🤝 Contributing

We welcome contributions! Please:
1. Fork the repository
2. Create a feature branch
3. Follow WordPress coding standards
4. Test thoroughly across different screen sizes
5. Submit a pull request

---

## 📄 License

GPL v2 or later. See [LICENSE](https://github.com/jimrweb/fluid-font-forge/blob/main/LICENSE) for details.

---

## 🙏 Credits

**Development**
- [Jim R.](https://jimrweb.com) - Creator and lead developer
- [Claude AI](https://anthropic.com) - Development assistance and architecture guidance

**Inspiration**
- [Imran Siddiq](https://websquadron.co.uk) - Original Font Clamp Calculator concept

---

<div align="center">

**Made with ❤️ for the WordPress Community**

[⭐ Star this repository](https://github.com/jimrweb/fluid-font-forge) • [🐛 Report Issues](https://github.com/jimrweb/fluid-font-forge/issues) • [💬 Join Discussion](https://github.com/jimrweb/fluid-font-forge/discussions)

**Professional WordPress Plugin Development** | **[JimRForge.com](https://jimrforge.com)**

</div>