# Magento 2 - PagelayoutToLayout Module

A Magento 2 module that enables automatic mapping from page layouts to specific layout handles. This module helps apply layout XML dynamically based on the page layout selected in the admin panel.

## Description

The `Makin_PagelayoutToLayout` module solves the problem of applying specific layout XML based on the selected page layout. Typically, to apply CSS, JavaScript, or custom blocks to specific pages, you must hardcode product SKUs or category IDs. With this module, you can:

- Automatically apply layout XML based on the page layout selected in admin
- Avoid hardcoding SKU/IDs in Custom Layout Updates
- Add CSS and JavaScript to headers or other blocks based on page layout
- Use a softcoded approach that is more flexible and maintainable

## How It Works

This module works by listening to the `layout_load_before` event and checking the active page layout. If the page layout matches the mapping defined in `layout_mapping.json`, the corresponding layout handle will be applied automatically.

## Installation

### Manual Installation

1. Create the module directory at `app/code/Makin/PagelayoutToLayout/`
2. Copy all module files to that directory
3. Run the following commands:

```bash
php bin/magento module:enable Makin_PagelayoutToLayout
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:flush
```

## Configuration

### 1. Define Page Layout to Layout Handle Mapping

Edit the file `PagelayoutToLayout/etc/layout_mapping.json`:

```json
{
    "page_layout_name": "layout_handle_name",
    "custom_layout_1": "pagelayout_to_layout_custom_1"
}
```

**Key:** The page layout name to be detected
**Value:** The layout handle name to be applied

### 2. Create Page Layout in Theme

Add a new page layout in `app/design/frontend/Vendor/Name/Magento_Theme/layouts.xml`:

```xml
<?xml version="1.0"?>
<page_layouts xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
              xsi:noNamespaceSchemaLocation="urn:magento:framework:View/PageLayout/etc/layouts.xsd">
    <layout id="custom_layout_1">
        <label translate="true">Custom Layout 1</label>
    </layout>
</page_layouts>
```

### 3. Create Page Layout File

Create the file `app/design/frontend/Vendor/Name/Magento_Theme/page_layout/custom_layout_1.xml`:

```xml
<?xml version="1.0"?>
<layout xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_layout.xsd">
    <update handle="1column"/>
</layout>
```

### 4. Create the Layout Handle to Be Applied

Create the file `app/design/frontend/Vendor/Name/Magento_Theme/layout/pagelayout_to_layout_custom_1.xml`:

```xml
<?xml version="1.0"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
      xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <head>
        <css src="css/custom-style.css"/>
        <script src="js/custom-script.js"/>
    </head>
    <body>
        <!-- Add blocks or layout modifications here -->
    </body>
</page>
```

## Usage Example

### Scenario: Adding Custom CSS for Specific Product Pages

1. Create a new page layout named `product_special` in `layouts.xml`
2. Select the `product_special` page layout in admin for specific products (Catalog > Products > Edit Product > Design > Layout)
3. Add mapping in `layout_mapping.json`:
   ```json
   {
       "product_special": "pagelayout_to_layout_product_special"
   }
   ```
4. Create layout file `pagelayout_to_layout_product_special.xml` with desired CSS and blocks
5. Whenever a product page with the `product_special` page layout is loaded, the layout handle will be applied automatically

## File Structure

```
PagelayoutToLayout/
├── registration.php              # Module registration
├── etc/
│   ├── module.xml               # Module configuration
│   ├── layout_mapping.json      # Page layout to layout handle mapping
│   └── frontend/
│       └── events.xml           # Event observer configuration
└── Observer/
    └── SetLayout.php            # Observer to apply layout
```

## Benefits

- **Flexible:** No need to hardcode SKUs or IDs in layout XML
- **Maintainable:** Mapping changes can be done in a single JSON file
- **Scalable:** Easy to add new mappings without changing code
- **Reusable:** Page layouts can be reused across multiple pages
- **Admin-Friendly:** Admin can select page layouts through the interface without editing XML

## Requirements

- Magento 2.x
- PHP 7.x or higher

## License

See the `LICENSE` file for license information.

## Author

Created by Izzulmakin (2024)

## Contributing

Contributions, issues, and feature requests are welcome.
