# Articulate Gutenberg Block

This directory contains the Gutenberg block for embedding Articulate content.

## Requirements

- **Node.js**: 16.0.0 or higher (tested with 16.14.2)
- **npm**: 8.0.0 or higher

## Build Tools

- **React**: 18.2.0
- **Webpack**: 5.88.2
- **Babel**: 7.22.0
- **Sass**: 1.64.1

## Development

1. Install dependencies:
   ```bash
   npm install
   ```

2. Start development mode with hot reloading:
   ```bash
   npm run dev
   ```

3. Build for production:
   ```bash
   npm run build
   ```

## File Structure

- `src/` - Source files
  - `index.js` - Main entry point
  - `articulate-block.js` - Main block component
  - `file-uploader.js` - File upload component
  - `style.scss` - Block styles
- `build/` - Compiled files (generated)
  - `block.js` - Compiled JavaScript
  - `block.css` - Compiled CSS

## Notes

- Uses WordPress's `wp.element` for React compatibility
- Compatible with React 18
- Modern Webpack 5 configuration
- Sass instead of node-sass for better performance and compatibility
- ESLint configuration uses @wordpress/eslint-plugin