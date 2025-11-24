# Fluid Font Forge

![Fluid Font Forge banner](docs/screenshots/banner.png)

**Professional responsive design tools for WordPress developers**

A WordPress plugin for creating responsive typography with CSS clamp() functions. Generate fluid font scaling that adapts smoothly across all viewport sizes.

[![Version](https://img.shields.io/badge/version-5.1.2-blue.svg)](https://github.com/Mij-Strebor/fluid-font-forge)
[![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)](https://wordpress.org/)
[![License](https://img.shields.io/badge/license-GPL%20v2%2B-green.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net/)

## What is Fluid Font Forge?

Fluid Font Forge simplifies responsive typography by generating CSS clamp() functions that scale fonts smoothly between minimum and maximum viewport widths. Instead of creating breakpoints for different screen sizes, fonts scale continuously using mathematical precision.

### Key Features

- **Mathematical Typography Scaling** - Uses musical harmony ratios (Minor Second to Golden Ratio) for natural size progression
- **Multiple Output Formats** - CSS classes, custom properties, HTML tags, and Tailwind configuration
- **Real-time Preview** - See how fonts appear at different screen sizes instantly
- **Preview Font Loading** - Test with Google Fonts or custom web fonts
- **Drag & Drop Management** - Reorder font sizes with intuitive interface
- **Copy-to-Clipboard** - One-click CSS copying for immediate implementation
- **Autosave System** - Automatic saving with visual status indicators

## How It Works

The plugin calculates CSS clamp() values using linear interpolation between viewport sizes:

```css
font-size: clamp(min_size, preferred_size, max_size);
```

Where `preferred_size` scales linearly based on viewport width, creating smooth transitions without media queries.

## Installation

1. Download or clone this repository to `/wp-content/plugins/fluid-font-forge/`
2. Activate the plugin through the WordPress admin
3. Navigate to **Tools → Fluid Font Forge** in your admin menu

## Basic Usage

### 1. Configure Settings
Set your viewport range (default: 375px to 1620px) and font size range (default: 16px to 20px). Choose scaling ratios for small and large screens.

### 2. Manage Font Sizes
Add, edit, or reorder font sizes using the interactive table. Each size automatically calculates responsive values based on your settings.

### 3. Preview Results
View real-time previews showing how fonts appear at minimum and maximum viewport sizes.

### 4. Copy Generated CSS
Choose from four output formats and copy the generated CSS directly to your project.

## Output Formats

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

## Typography Scaling Options

| Scale Name | Ratio | Best For |
|------------|-------|----------|
| Minor Second | 1.067 | Subtle, tight spacing |
| Major Second | 1.125 | Clean, modern designs |
| Minor Third | 1.200 | Balanced hierarchy |
| Major Third | 1.250 | Strong contrast |
| Perfect Fourth | 1.333 | Bold, dramatic scaling |
| Golden Ratio | 1.618 | Harmonious, classical proportions |

## Browser Support

CSS clamp() is supported in:
- Chrome 79+ (March 2020)
- Firefox 75+ (April 2020)
- Safari 13.1+ (March 2020)
- Edge 79+ (January 2020)

For older browsers, consider providing fallback font sizes.

## Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **Browser**: Modern browser with CSS clamp() support

## Plugin Architecture

```
fluid-font-forge/
├── fluid-font-forge.php              # Main plugin bootstrap
├── includes/
│   ├── class-fluid-font-forge.php    # Core functionality
│   └── class-default-data-factory.php # Default configurations
├── assets/
│   ├── css/
│   │   ├── admin-styles.css          # Admin interface styling
│   │   ├── forge-header.css          # Forge header system
│   │   ├── typography-fix.css        # Typography standardization
│   │   └── panel-fixes.css           # Panel layout fixes
│   ├── js/admin-script.js            # Main admin controller
│   ├── fonts/                        # Self-hosted Inter fonts
│   └── images/                       # Forge banner assets
├── templates/admin/                  # PHP template partials
└── uninstall.php                     # Clean removal
```

## Development

### Local Setup
```bash
git clone https://github.com/jimrweb/fluid-font-forge.git
cd fluid-font-forge
```

### Contributing
1. Fork the repository
2. Create a feature branch
3. Follow WordPress coding standards
4. Test thoroughly across different screen sizes
5. Submit a pull request

## License

This project is licensed under GPL v2 or later. See the [LICENSE](LICENSE) file for details.

## Credits

**Development**
- [Jim R.](https://jimrweb.com) - Creator and lead developer
- [Claude AI](https://anthropic.com) - Development assistance and architecture guidance

**Inspiration**
- [Imran Siddiq](https://websquadron.co.uk) - Original Font Clamp Calculator concept


## Documentation

- 📘 **[User Manual](USER-MANUAL.md)** - Comprehensive guide with real-world use cases- 
- 📖 **[Quick Start Guide](docs/quick-start.md)** - Get started in 5 minutes 
- 💬 **[GitHub Discussions](https://github.com/Mij-Strebor/fluid-font-forge/discussions)** - Ask questions



## Support

- **Issues**: [GitHub Issues](https://github.com/Mij-Strebor/fluid-font-forge/issues)
- **Documentation**: [GitHub Wiki](https://github.com/Mij-Strebor/fluid-font-forge/wiki) 
- **Documentation**: [User Manual](USER-MANUAL.md)
- **Email**: jim@jimrforge.com

---

<div align="center">

**🚀 Made with ❤️ for the WordPress Community**

[⭐ Star this repository](https://github.com/Mij-Strebor/fluid-font-forge) • [🐛 Report Issues](https://github.com/Mij-Strebor/fluid-font-forge/issues) • [💬 Join Discussion](https://github.com/Mij-Strebor/fluid-font-forge/discussions)

**Professional WordPress Plugin Development** | **[JimRForge.com](https://jimrforge.com)**

</div>
