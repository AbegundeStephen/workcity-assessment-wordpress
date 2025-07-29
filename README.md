# Client Project Manager - WordPress Plugin

A lightweight WordPress plugin for managing client projects with custom post types, meta fields, and frontend display capabilities.

## 📋 Features

- **Custom Post Type**: Client Projects with full WordPress integration
- **Custom Meta Fields**: Client name, project status, and deadline
- **Admin Interface**: Enhanced admin columns and dashboard widget
- **Frontend Display**: Shortcode system with filtering options
- **Security**: Proper nonce verification and data sanitization
- **Responsive Design**: Mobile-friendly frontend display

## 🚀 Installation

### Method 1: Manual Installation
1. Download the plugin files
2. Upload the `client-project-manager` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' screen in WordPress
4. Navigate to 'Client Projects' in your admin menu

### Method 2: Upload via Admin
1. Go to Plugins → Add New → Upload Plugin
2. Choose the plugin zip file
3. Click Install Now
4. Activate the plugin

## 📁 Plugin Structure

```
client-project-manager/
├── client-project-manager.php    # Main plugin file
├── assets/
│   ├── style.css                 # Frontend styles
│   └── admin-style.css           # Admin styles
├── templates/
│   └── single-client_project.php # Single project template
└── README.md                     # This file
```

## 🔧 Usage

### Adding Projects

1. Go to **Client Projects → Add New Project**
2. Fill in the project details:
   - **Title**: Project name
   - **Description**: Project details in the editor
   - **Client Name**: Name of the client
   - **Status**: Pending, In Progress, or Completed
   - **Deadline**: Project deadline date
3. Click **Publish**

### Admin Features

- **Custom Columns**: View client name, status, and deadline in the projects list
- **Dashboard Widget**: Quick overview of project statistics
- **Status Badges**: Color-coded status indicators

### Frontend Display

Use the `[client_projects]` shortcode to display projects on any page or post.

#### Basic Usage
```
[client_projects]
```

#### With Attributes
```
[client_projects status="in-progress" limit="5"]
[client_projects client_name="ABC Company"]
[client_projects status="completed" limit="10"]
```

#### Available Attributes
- `status`: Filter by project status (pending, in-progress, completed)
- `limit`: Number of projects to display (default: 10)
- `client_name`: Filter by client name (partial match)

## 🎨 Customization

### CSS Classes

The plugin includes CSS classes for easy customization:

- `.client-projects-grid`: Main container
- `.project-card`: Individual project cards
- `.status-badge`: Status indicators
- `.status-pending`, `.status-in-progress`, `.status-completed`: Status-specific styling

### Template Customization

Copy `single-client_project.php` to your active theme directory to customize the single project display.

### Styling

Override plugin styles by adding CSS to your theme:

```css
.project-card {
    border: 2px solid #your-color;
    border-radius: 10px;
}

.status-badge.status-completed {
    background-color: #your-success-color;
}
```

## 🔒 Security Features

- **Nonce Verification**: All form submissions verified
- **Data Sanitization**: User input properly sanitized
- **Capability Checks**: Proper permission checking
- **SQL Injection Prevention**: Using WordPress APIs
- **XSS Protection**: Output escaping

## 🌐 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## 📱 Responsive Design

The plugin is fully responsive and works on:
- Desktop computers
- Tablets
- Mobile phones

## 🐛 Troubleshooting

### Projects Not Displaying
- Check if the shortcode is correct: `[client_projects]`
- Ensure projects are published, not in draft
- Verify theme compatibility

### Admin Columns Not Showing
- Deactivate and reactivate the plugin
- Check if other plugins conflict
- Clear any caching

### Styles Not Loading
- Check if theme supports WordPress standards
- Verify plugin files are uploaded correctly
- Clear browser cache

## 🔄 Uninstallation

1. Deactivate the plugin from the Plugins screen
2. Delete the plugin files
3. **Note**: Project data will remain in the database. Use a database cleanup plugin if needed.

## 🛠️ Development

### Requirements
- WordPress 5.0+
- PHP 7.4+
- MySQL 5.6+

### Code Standards
- Follows WordPress Coding Standards
- PSR-4 autoloading structure
- Proper documentation and comments

### Hooks Available

The plugin provides hooks for developers:

```php
// Filter project query args
add_filter('cpm_project_query_args', 'your_function');

// Modify shortcode output
add_filter('cpm_shortcode_output', 'your_function');
```

## 📝 Changelog

### Version 1.0.0
- Initial release
- Custom post type implementation
- Meta fields and admin interface
- Shortcode functionality
- Dashboard widget
- Responsive frontend display

## 📞 Support

For support and questions:
- Check the troubleshooting section above
- Review WordPress plugin development documentation
- Test with default WordPress themes

## 📄 License

This plugin is licensed under the GPL v2 or later.

## 🙏 Credits

Built following WordPress plugin development best practices and security guidelines.

---

**Note**: This plugin is designed for educational and portfolio purposes. For production use, consider additional features like user permissions, advanced filtering, and database optimization.