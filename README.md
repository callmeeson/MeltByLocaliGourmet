# Melt Custom WordPress Theme

A fully custom WordPress theme for **Melt by Locali Gourmet** - an artisan patisserie with elegant design and modern features.

## Features

- ✅ **100% Custom Design** - No default WordPress templates used
- ✅ **Fully Responsive** - Mobile-first design approach
- ✅ **Hero Slideshow** - Dynamic slideshow with custom post type
- ✅ **WooCommerce Ready** - Complete e-commerce integration
- ✅ **Modern Animations** - Smooth fade-in effects and transitions
- ✅ **SEO Optimized** - Semantic HTML5 structure
- ✅ **Performance Optimized** - Fast loading times
- ✅ **Accessibility** - WCAG compliant
- ✅ **Custom Post Types** - Hero slides management
- ✅ **AJAX Cart** - Seamless shopping experience

## Design System

### Color Palette
- **Primary Gold**: #B8860B
- **Accent Gold**: #DAA520
- **Background**: #FFFFFF
- **Secondary Background**: #F8F8F8
- **Text**: #1A1A1A
- **Muted Text**: #666666
- **Border**: #E0E0E0

### Typography
- **Headings**: Lora (Serif) - Elegant and sophisticated
- **Body**: Inter (Sans-serif) - Clean and modern
- **Accent**: Lora (Serif) - For special text elements

## Theme Structure

```
melt-custom/
├── style.css                 # Main stylesheet with theme header
├── functions.php             # Theme functions and WordPress hooks
├── header.php               # Header template
├── footer.php               # Footer template
├── front-page.php           # Homepage template
├── index.php                # Blog/archive template
├── single.php               # Single post template
├── page.php                 # Page template
├── 404.php                  # 404 error page
├── screenshot.png           # Theme screenshot
├── css/
│   └── responsive.css       # Responsive & additional styles
└── js/
    └── main.js              # JavaScript functionality
```

## Installation

1. **Upload the theme:**
   - Upload the `melt-custom` folder to `/wp-content/themes/`

2. **Activate the theme:**
   - Go to WordPress Admin → Appearance → Themes
   - Find "Melt Custom Theme" and click "Activate"

3. **Configure menus:**
   - Go to Appearance → Menus
   - Create a new menu and assign it to "Primary Menu"

4. **Set up hero slides:**
   - Go to Hero Slides in the admin menu
   - Add new slides with featured images and subtitles
   - Drag to reorder slides

5. **Install WooCommerce (optional):**
   - For e-commerce functionality, install and activate WooCommerce
   - Add your products to display in the shop

## Customization

### Hero Slideshow
The hero slideshow is managed through a custom post type:
- Add slides via **Hero Slides** in the WordPress admin
- Set featured image as the slide background
- Add title and subtitle for each slide
- Order slides by drag-and-drop

### Color Scheme
To customize colors, edit the CSS variables in `style.css`:
```css
:root {
  --primary: #B8860B;
  --accent: #DAA520;
  /* Add your custom colors */
}
```

### Fonts
To change fonts, update the Google Fonts import in `style.css`:
```css
@import url('https://fonts.googleapis.com/css2?family=YourFont...');
```

## Dependencies

- **WordPress**: 6.0+
- **PHP**: 7.4+
- **Lucide Icons**: Loaded via CDN for icon support
- **WooCommerce**: Optional, for e-commerce features

## Browser Support

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## Performance

- Optimized images with lazy loading
- Minified and deferred JavaScript
- CSS organized for efficient rendering
- Reduced HTTP requests
- Mobile-first responsive design

## Accessibility

- Semantic HTML5 structure
- ARIA labels on interactive elements
- Keyboard navigation support
- High contrast mode support
- Screen reader friendly
- Focus indicators on all interactive elements

## React Design Source

This theme is a pixel-perfect recreation of the React design found in the `/design` folder. Key components translated:
- Header with transparent/scrolled states
- Hero slideshow with navigation
- Cake Collections grid
- Artisan Videos section
- Seasonal Products showcase
- Footer with multiple columns

## Support

For support or customization requests, please contact:
- **Email**: hello@meltbylg.com
- **Website**: https://meltbylg.com

## Credits

- **Theme Development**: Custom WordPress Theme
- **Design Inspiration**: React app in `/design` folder
- **Fonts**: Google Fonts (Lora, Inter)
- **Icons**: Lucide Icons
- **Images**: Unsplash (placeholder images)

## Changelog

### Version 1.0.0 (January 2026)
- Initial release
- Complete custom theme based on React design
- Hero slideshow with custom post type
- WooCommerce integration
- Responsive design
- Accessibility improvements
- Performance optimizations

## License

This theme is licensed under the GNU General Public License v2 or later.

---

**Melt by Locali Gourmet** - Crafting Exquisite Artisanal Cakes Since 2015
