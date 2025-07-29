<?php
/**
 * Plugin Name: Client Project Manager
 * Plugin URI: https://yourwebsite.com/client-project-manager
 * Description: A simple client project management system for WordPress
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * Text Domain: client-project-manager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CPM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CPM_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('CPM_VERSION', '1.0.0');

class ClientProjectManager {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        
        // Activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        $this->create_post_type();
        $this->add_meta_boxes();
        $this->add_shortcode();
        $this->add_admin_columns();
        $this->add_dashboard_widget();
        
        // Save post meta
        add_action('save_post', array($this, 'save_project_meta'));
        
        // Admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }
    
    public function create_post_type() {
        $labels = array(
            'name' => 'Client Projects',
            'singular_name' => 'Client Project',
            'menu_name' => 'Client Projects',
            'add_new' => 'Add New Project',
            'add_new_item' => 'Add New Client Project',
            'edit_item' => 'Edit Client Project',
            'new_item' => 'New Client Project',
            'view_item' => 'View Client Project',
            'search_items' => 'Search Client Projects',
            'not_found' => 'No client projects found',
            'not_found_in_trash' => 'No client projects found in trash'
        );
        
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'client-projects'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-portfolio',
            'supports' => array('title', 'editor', 'thumbnail'),
            'show_in_rest' => true
        );
        
        register_post_type('client_project', $args);
    }
    
    public function add_meta_boxes() {
        add_action('add_meta_boxes', array($this, 'register_meta_boxes'));
    }
    
    public function register_meta_boxes() {
        add_meta_box(
            'client_project_details',
            'Project Details',
            array($this, 'project_details_callback'),
            'client_project',
            'normal',
            'high'
        );
    }
    
    public function project_details_callback($post) {
        // Add nonce for security
        wp_nonce_field(basename(__FILE__), 'client_project_nonce');
        
        // Get current values
        $client_name = get_post_meta($post->ID, '_client_name', true);
        $project_status = get_post_meta($post->ID, '_project_status', true);
        $project_deadline = get_post_meta($post->ID, '_project_deadline', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="client_name">Client Name</label></th>
                <td>
                    <input type="text" id="client_name" name="client_name" 
                           value="<?php echo esc_attr($client_name); ?>" 
                           class="regular-text" required />
                </td>
            </tr>
            <tr>
                <th><label for="project_status">Project Status</label></th>
                <td>
                    <select id="project_status" name="project_status">
                        <option value="pending" <?php selected($project_status, 'pending'); ?>>Pending</option>
                        <option value="in-progress" <?php selected($project_status, 'in-progress'); ?>>In Progress</option>
                        <option value="completed" <?php selected($project_status, 'completed'); ?>>Completed</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="project_deadline">Deadline</label></th>
                <td>
                    <input type="date" id="project_deadline" name="project_deadline" 
                           value="<?php echo esc_attr($project_deadline); ?>" />
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function save_project_meta($post_id) {
        // Verify nonce
        if (!isset($_POST['client_project_nonce']) || 
            !wp_verify_nonce($_POST['client_project_nonce'], basename(__FILE__))) {
            return $post_id;
        }
        
        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
        
        // Avoid auto-save
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        
        // Save meta fields
        $fields = array('client_name', 'project_status', 'project_deadline');
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
    
    public function add_admin_columns() {
        add_filter('manage_client_project_posts_columns', array($this, 'custom_columns'));
        add_action('manage_client_project_posts_custom_column', array($this, 'custom_column_data'), 10, 2);
    }
    
    public function custom_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['client_name'] = 'Client Name';
        $new_columns['project_status'] = 'Status';
        $new_columns['project_deadline'] = 'Deadline';
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }
    
    public function custom_column_data($column, $post_id) {
        switch ($column) {
            case 'client_name':
                echo esc_html(get_post_meta($post_id, '_client_name', true));
                break;
            case 'project_status':
                $status = get_post_meta($post_id, '_project_status', true);
                $status_class = 'status-' . $status;
                echo '<span class="' . esc_attr($status_class) . '">' . esc_html(ucfirst(str_replace('-', ' ', $status))) . '</span>';
                break;
            case 'project_deadline':
                $deadline = get_post_meta($post_id, '_project_deadline', true);
                if ($deadline) {
                    echo esc_html(date('M j, Y', strtotime($deadline)));
                } else {
                    echo '—';
                }
                break;
        }
    }
    
    public function add_shortcode() {
        add_shortcode('client_projects', array($this, 'shortcode_callback'));
    }
    
    public function shortcode_callback($atts) {
        $atts = shortcode_atts(array(
            'status' => '',
            'limit' => 10,
            'client_name' => ''
        ), $atts);
        
        $args = array(
            'post_type' => 'client_project',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish'
        );
        
        // Add meta query for status
        if (!empty($atts['status'])) {
            $args['meta_query'] = array(
                array(
                    'key' => '_project_status',
                    'value' => sanitize_text_field($atts['status']),
                    'compare' => '='
                )
            );
        }
        
        // Add meta query for client name
        if (!empty($atts['client_name'])) {
            if (!isset($args['meta_query'])) {
                $args['meta_query'] = array();
            }
            $args['meta_query'][] = array(
                'key' => '_client_name',
                'value' => sanitize_text_field($atts['client_name']),
                'compare' => 'LIKE'
            );
        }
        
        $query = new WP_Query($args);
        
        ob_start();
        
        if ($query->have_posts()) {
            echo '<div class="client-projects-grid">';
            
            while ($query->have_posts()) {
                $query->the_post();
                $client_name = get_post_meta(get_the_ID(), '_client_name', true);
                $status = get_post_meta(get_the_ID(), '_project_status', true);
                $deadline = get_post_meta(get_the_ID(), '_project_deadline', true);
                
                ?>
                <div class="project-card status-<?php echo esc_attr($status); ?>">
                    <h3 class="project-title"><?php the_title(); ?></h3>
                    <div class="project-meta">
                        <p><strong>Client:</strong> <?php echo esc_html($client_name); ?></p>
                        <p><strong>Status:</strong> 
                            <span class="status-badge status-<?php echo esc_attr($status); ?>">
                                <?php echo esc_html(ucfirst(str_replace('-', ' ', $status))); ?>
                            </span>
                        </p>
                        <?php if ($deadline): ?>
                            <p><strong>Deadline:</strong> <?php echo esc_html(date('M j, Y', strtotime($deadline))); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="project-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="project-link">View Details</a>
                </div>
                <?php
            }
            
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p>No projects found.</p>';
        }
        
        return ob_get_clean();
    }
    
    public function add_dashboard_widget() {
        add_action('wp_dashboard_setup', array($this, 'register_dashboard_widget'));
    }
    
    public function register_dashboard_widget() {
        wp_add_dashboard_widget(
            'client_projects_widget',
            'Client Projects Overview',
            array($this, 'dashboard_widget_callback')
        );
    }
    
    public function dashboard_widget_callback() {
        $total = wp_count_posts('client_project');
        $pending = $this->count_projects_by_status('pending');
        $in_progress = $this->count_projects_by_status('in-progress');
        $completed = $this->count_projects_by_status('completed');
        
        ?>
        <div class="client-projects-stats">
            <div class="stat-item">
                <span class="stat-number"><?php echo $total->publish; ?></span>
                <span class="stat-label">Total Projects</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo $pending; ?></span>
                <span class="stat-label">Pending</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo $in_progress; ?></span>
                <span class="stat-label">In Progress</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo $completed; ?></span>
                <span class="stat-label">Completed</span>
            </div>
        </div>
        <p><a href="<?php echo admin_url('edit.php?post_type=client_project'); ?>">View All Projects</a></p>
        <?php
    }
    
    private function count_projects_by_status($status) {
        $args = array(
            'post_type' => 'client_project',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_project_status',
                    'value' => $status,
                    'compare' => '='
                )
            ),
            'fields' => 'ids'
        );
        
        $query = new WP_Query($args);
        return $query->found_posts;
    }
    
    public function add_admin_menu() {
        // This is handled by the custom post type registration
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style('client-project-manager', CPM_PLUGIN_URL . 'assets/style.css', array(), CPM_VERSION);
    }
    
    public function admin_enqueue_scripts() {
        wp_enqueue_style('client-project-manager-admin', CPM_PLUGIN_URL . 'assets/admin-style.css', array(), CPM_VERSION);
    }
    
    public function activate() {
        $this->create_post_type();
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
}

// Initialize the plugin
new ClientProjectManager();