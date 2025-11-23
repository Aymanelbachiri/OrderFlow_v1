<?php
/**
 * Plugin Name: IPTV Integration
 * Plugin URI: https://your-domain.com
 * Description: Integrate IPTV pricing plans, credit packs, and custom products with WordPress. Automatically creates pages with iframes for each product. Single-user version.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://your-domain.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: iptv-integration
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('IPTV_INTEGRATION_VERSION', '1.0.0');
define('IPTV_INTEGRATION_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('IPTV_INTEGRATION_PLUGIN_URL', plugin_dir_url(__FILE__));

class IPTV_Integration {
    
    private $api_url;
    private $api_token;
    private $option_name = 'iptv_integration_settings';
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_iptv_sync_products', array($this, 'sync_products'));
        add_action('wp_ajax_iptv_test_connection', array($this, 'test_connection'));
        add_action('wp_ajax_iptv_clear_data', array($this, 'clear_data'));
        
        // Prevent WordPress from filtering our iframe content
        add_filter('the_content', array($this, 'preserve_iframe_content'), 999);
        
        // Load settings
        $this->load_settings();
    }
    
    /**
     * Preserve iframe content from WordPress filters
     */
    public function preserve_iframe_content($content) {
        // Check if this is one of our product pages
        global $post;
        if (!$post) {
            return $content;
        }
        
        $product_pages = get_option('iptv_product_pages', array());
        $is_our_page = false;
        
        foreach ($product_pages as $page_data) {
            if (isset($page_data['page_id']) && $page_data['page_id'] == $post->ID) {
                $is_our_page = true;
                break;
            }
        }
        
        if ($is_our_page) {
            // Remove WordPress auto-paragraph and other filters for our pages
            remove_filter('the_content', 'wpautop');
            remove_filter('the_content', 'wptexturize');
            
            // Return raw content without filtering
            return $post->post_content;
        }
        
        return $content;
    }
    
    /**
     * Load plugin settings
     */
    private function load_settings() {
        $settings = get_option($this->option_name, array());
        $this->api_url = isset($settings['api_url']) ? $settings['api_url'] : '';
        $this->api_token = isset($settings['api_token']) ? $settings['api_token'] : '';
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            'IPTV Integration',
            'IPTV Integration',
            'manage_options',
            'iptv-integration',
            array($this, 'render_settings_page'),
            'dashicons-admin-plugins',
            30
        );
    }
    
    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('iptv_integration_settings', $this->option_name);
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_iptv-integration') {
            return;
        }
        
        wp_enqueue_script('iptv-integration-admin', IPTV_INTEGRATION_PLUGIN_URL . 'assets/admin.js', array('jquery'), IPTV_INTEGRATION_VERSION, true);
        wp_enqueue_style('iptv-integration-admin', IPTV_INTEGRATION_PLUGIN_URL . 'assets/admin.css', array(), IPTV_INTEGRATION_VERSION);
        
        wp_localize_script('iptv-integration-admin', 'iptvIntegration', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('iptv_integration_nonce')
        ));
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        if (isset($_POST['submit'])) {
            $settings = array(
                'api_url' => sanitize_text_field($_POST['api_url']),
                'api_token' => sanitize_text_field($_POST['api_token']),
            );
            update_option($this->option_name, $settings);
            $this->load_settings();
            echo '<div class="notice notice-success"><p>Settings saved successfully!</p></div>';
        }
        
        ?>
        <div class="wrap">
            <h1>IPTV Integration Settings</h1>
            
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="api_url">API URL</label>
                        </th>
                        <td>
                            <input type="url" id="api_url" name="api_url" value="<?php echo esc_attr($this->api_url); ?>" class="regular-text" placeholder="https://your-domain.com/api" />
                            <p class="description">Enter your Laravel API base URL (e.g., https://your-domain.com/api)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="api_token">API Token</label>
                        </th>
                        <td>
                            <input type="text" id="api_token" name="api_token" value="<?php echo esc_attr($this->api_token); ?>" class="regular-text" />
                            <p class="description">Enter your API token from the Laravel admin panel</p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Settings">
                    <button type="button" id="test-connection" class="button">Test Connection</button>
                    <button type="button" id="sync-products" class="button button-secondary">Sync Products</button>
                    <button type="button" id="clear-data" class="button button-secondary" style="color: #dc3232; border-color: #dc3232;">Clear All Data</button>
                </p>
            </form>
            
            <div id="iptv-messages"></div>
            
            <hr>
            
            <h2>Product Pages</h2>
            <p>Click "Sync Products" to automatically create or update WordPress pages for all your products.</p>
            <?php $this->render_product_pages_list(); ?>
        </div>
        <?php
    }
    
    /**
     * Render list of created product pages
     */
    private function render_product_pages_list() {
        $product_pages = get_option('iptv_product_pages', array());
        
        if (empty($product_pages)) {
            echo '<p>No product pages created yet. Click "Sync Products" to create them.</p>';
            return;
        }
        
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>Product Name</th><th>Type</th><th>Page</th><th>Actions</th></tr></thead>';
        echo '<tbody>';
        
        foreach ($product_pages as $product_id => $page_data) {
            $page = get_post($page_data['page_id']);
            if ($page) {
                echo '<tr>';
                echo '<td>' . esc_html($page_data['name']) . '</td>';
                echo '<td>' . esc_html($page_data['type']) . '</td>';
                echo '<td><a href="' . get_permalink($page->ID) . '" target="_blank">' . esc_html($page->post_title) . '</a></td>';
                echo '<td><a href="' . get_edit_post_link($page->ID) . '">Edit</a> | <a href="' . get_permalink($page->ID) . '" target="_blank">View</a></td>';
                echo '</tr>';
            }
        }
        
        echo '</tbody></table>';
    }
    
    /**
     * Test API connection
     */
    public function test_connection() {
        check_ajax_referer('iptv_integration_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $settings = get_option($this->option_name, array());
        $api_url = isset($settings['api_url']) ? $settings['api_url'] : '';
        $api_token = isset($settings['api_token']) ? $settings['api_token'] : '';
        
        if (empty($api_url) || empty($api_token)) {
            wp_send_json_error('API URL and Token are required');
        }
        
        $response = wp_remote_get($api_url . '/wordpress/products', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_token,
                'Accept' => 'application/json',
            ),
            'timeout' => 15,
        ));
        
        if (is_wp_error($response)) {
            wp_send_json_error('Connection failed: ' . $response->get_error_message());
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($status_code === 200 && isset($body['success']) && $body['success']) {
            wp_send_json_success('Connection successful! Found ' . 
                (count($body['data']['pricing_plans']) + count($body['data']['credit_packs']) + count($body['data']['custom_products'])) . 
                ' products.');
        } else {
            $message = isset($body['message']) ? $body['message'] : 'Connection failed';
            wp_send_json_error($message);
        }
    }
    
    /**
     * Sync products from API and create WordPress pages
     */
    public function sync_products() {
        check_ajax_referer('iptv_integration_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $settings = get_option($this->option_name, array());
        $api_url = isset($settings['api_url']) ? $settings['api_url'] : '';
        $api_token = isset($settings['api_token']) ? $settings['api_token'] : '';
        
        if (empty($api_url) || empty($api_token)) {
            wp_send_json_error('API URL and Token are required');
        }
        
        // Fetch products from API
        $response = wp_remote_get($api_url . '/wordpress/products', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_token,
                'Accept' => 'application/json',
            ),
            'timeout' => 15,
        ));
        
        if (is_wp_error($response)) {
            wp_send_json_error('Failed to fetch products: ' . $response->get_error_message());
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($status_code !== 200 || !isset($body['success']) || !$body['success']) {
            $message = isset($body['message']) ? $body['message'] : 'Failed to fetch products';
            wp_send_json_error($message);
        }
        
        $products = $body['data'];
        $created_pages = array();
        $updated_pages = array();
        $product_pages = get_option('iptv_product_pages', array());
        
        // Process pricing plans
        foreach ($products['pricing_plans'] as $plan) {
            $page_data = $this->create_or_update_product_page($plan, 'pricing_plan', $product_pages);
            if ($page_data['created']) {
                $created_pages[] = $page_data['title'];
            } else {
                $updated_pages[] = $page_data['title'];
            }
            $product_pages[$plan['id'] . '_plan'] = $page_data;
        }
        
        // Process credit packs
        foreach ($products['credit_packs'] as $pack) {
            $page_data = $this->create_or_update_product_page($pack, 'credit_pack', $product_pages);
            if ($page_data['created']) {
                $created_pages[] = $page_data['title'];
            } else {
                $updated_pages[] = $page_data['title'];
            }
            $product_pages[$pack['id'] . '_pack'] = $page_data;
        }
        
        // Process custom products
        foreach ($products['custom_products'] as $product) {
            $page_data = $this->create_or_update_product_page($product, 'custom_product', $product_pages);
            if ($page_data['created']) {
                $created_pages[] = $page_data['title'];
            } else {
                $updated_pages[] = $page_data['title'];
            }
            $product_pages[$product['id'] . '_product'] = $page_data;
        }
        
        // Save product pages mapping
        update_option('iptv_product_pages', $product_pages);
        
        $message = 'Sync completed! ';
        if (!empty($created_pages)) {
            $message .= 'Created ' . count($created_pages) . ' page(s). ';
        }
        if (!empty($updated_pages)) {
            $message .= 'Updated ' . count($updated_pages) . ' page(s).';
        }
        
        wp_send_json_success($message);
    }
    
    /**
     * Create or update a WordPress page for a product
     */
    private function create_or_update_product_page($product, $type, $existing_pages) {
        $product_key = $product['id'] . '_' . ($type === 'pricing_plan' ? 'plan' : ($type === 'credit_pack' ? 'pack' : 'product'));
        $page_id = null;
        $created = false;
        
        // Check if page already exists
        if (isset($existing_pages[$product_key]) && isset($existing_pages[$product_key]['page_id'])) {
            $page_id = $existing_pages[$product_key]['page_id'];
            $page = get_post($page_id);
            if (!$page || $page->post_status === 'trash') {
                $page_id = null;
            }
        }
        
        // Generate page title
        $page_title = $product['name'] . ' - Checkout';
        
        // Generate iframe content using checkout_url
        $checkout_url = $product['checkout_url'];
        $iframe_content = $this->generate_iframe_content($checkout_url, $product);
        
        // Create or update page
        $page_data = array(
            'post_title' => $page_title,
            'post_content' => $iframe_content,
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_author' => get_current_user_id(),
            'post_content_filtered' => '',
        );
        
        if ($page_id) {
            $page_data['ID'] = $page_id;
            wp_update_post($page_data);
        } else {
            $page_id = wp_insert_post($page_data);
            $created = true;
        }
        
        if (is_wp_error($page_id)) {
            // Error handling - page creation failed
        }
        
        return array(
            'page_id' => $page_id,
            'title' => $page_title,
            'name' => $product['name'],
            'type' => $type,
            'created' => $created,
        );
    }
    
    /**
     * Generate iframe content for product page (full-page iframe only)
     */
    private function generate_iframe_content($checkout_url, $product) {
        $content = '<!-- IPTV Integration - Product: ' . esc_html($product['name']) . ' -->';
        $content .= '<!-- Checkout URL: ' . esc_html($checkout_url) . ' -->';
        $content .= '<style>
            * {
                box-sizing: border-box;
            }
            
            html, body {
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
                width: 100% !important;
                height: 100% !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
            }
            
            #wpadminbar {
                display: none !important;
            }
            
            .iptv-checkout-container {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                min-width: 100vw !important;
                min-height: 100vh !important;
                max-width: 100vw !important;
                max-height: 100vh !important;
                background-color: #111827 !important;
                z-index: 999999 !important;
                display: block !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
            }
            
            .iptv-checkout-iframe-wrapper {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                width: 100% !important;
                height: 100% !important;
                min-width: 100% !important;
                min-height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .iptv-checkout-iframe {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                width: 100% !important;
                height: 100% !important;
                min-width: 100% !important;
                min-height: 100% !important;
                border: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            .site-content,
            .content-area,
            .entry-content,
            .page-content,
            .wp-block-group,
            .wp-block-columns {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: 100% !important;
            }
            
            .iptv-blocked-message {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                width: 100% !important;
                height: 100% !important;
                background-color: #1f2937 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                z-index: 1000000 !important;
                padding: 2rem !important;
                color: #ffffff !important;
            }
            
            .iptv-blocked-content {
                max-width: 600px !important;
                background-color: #374151 !important;
                padding: 2rem !important;
                border-radius: 8px !important;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3) !important;
            }
            
            .iptv-blocked-content h2 {
                margin: 0 0 1rem 0 !important;
                color: #fbbf24 !important;
                font-size: 1.5rem !important;
            }
            
            .iptv-blocked-content p {
                margin: 1rem 0 !important;
                line-height: 1.6 !important;
            }
            
            .iptv-blocked-content ul {
                margin: 1rem 0 !important;
                padding-left: 1.5rem !important;
            }
            
            .iptv-blocked-content li {
                margin: 0.5rem 0 !important;
            }
            
            .iptv-checkout-link {
                display: inline-block !important;
                margin-top: 1.5rem !important;
                padding: 0.75rem 1.5rem !important;
                background-color: #3b82f6 !important;
                color: #ffffff !important;
                text-decoration: none !important;
                border-radius: 6px !important;
                font-weight: 600 !important;
                transition: background-color 0.2s !important;
            }
            
            .iptv-checkout-link:hover {
                background-color: #2563eb !important;
            }
        </style>';
        $content .= '<div class="iptv-checkout-container">';
        $content .= '<div class="iptv-checkout-iframe-wrapper">';
        $content .= '<iframe src="' . esc_url($checkout_url) . '" ';
        $content .= 'class="iptv-checkout-iframe" ';
        $content .= 'id="iptv-checkout-iframe" ';
        $content .= 'title="Checkout" ';
        $content .= 'allow="payment; fullscreen" ';
        $content .= 'sandbox="allow-forms allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox allow-top-navigation">';
        $content .= '</iframe>';
        $content .= '<div id="iptv-blocked-message" class="iptv-blocked-message" style="display: none;">';
        $content .= '<div class="iptv-blocked-content">';
        $content .= '<h2>⚠️ Checkout Blocked by Browser</h2>';
        $content .= '<p>Your browser (or an extension) is blocking the checkout page from loading.</p>';
        $content .= '<p><strong>To fix this:</strong></p>';
        $content .= '<ul>';
        $content .= '<li><strong>Brave Browser:</strong> Click the Brave Shields icon in the address bar and disable shields for this site</li>';
        $content .= '<li><strong>Ad Blockers:</strong> Disable your ad blocker for this website</li>';
        $content .= '<li><strong>Other Extensions:</strong> Check if any privacy/security extensions are blocking iframes</li>';
        $content .= '</ul>';
        $content .= '<p><a href="' . esc_url($checkout_url) . '" target="_blank" class="iptv-checkout-link">Open Checkout in New Window →</a></p>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
        
        $content .= '<script>
            (function() {
                var iframe = document.getElementById("iptv-checkout-iframe");
                var container = document.querySelector(".iptv-checkout-container");
                var wrapper = document.querySelector(".iptv-checkout-iframe-wrapper");
                var blockedMessage = document.getElementById("iptv-blocked-message");
                
                if (!iframe) return;
                
                function forceDimensions() {
                    var viewportWidth = window.innerWidth || document.documentElement.clientWidth;
                    var viewportHeight = window.innerHeight || document.documentElement.clientHeight;
                    var px = "px";
                    
                    if (container) {
                        container.style.width = viewportWidth + px;
                        container.style.height = viewportHeight + px;
                    }
                    
                    if (wrapper) {
                        wrapper.style.width = viewportWidth + px;
                        wrapper.style.height = viewportHeight + px;
                    }
                    
                    if (iframe) {
                        iframe.style.width = viewportWidth + px;
                        iframe.style.height = viewportHeight + px;
                    }
                }
                
                forceDimensions();
                window.addEventListener("resize", forceDimensions);
                
                iframe.onload = function() {
                    setTimeout(function() {
                        try {
                            var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                            if (iframeDoc && iframeDoc.body) {
                                var bodyText = iframeDoc.body.innerText || iframeDoc.body.textContent || "";
                                if (bodyText.includes("ERR_BLOCKED_BY_CLIENT") || 
                                    bodyText.includes("blocked") || 
                                    bodyText.includes("Brave") ||
                                    iframeDoc.body.classList.contains("neterror")) {
                                    if (blockedMessage) {
                                        blockedMessage.style.display = "flex";
                                    }
                                }
                            }
                        } catch (e) {
                            // Cross-origin - expected
                        }
                    }, 2000);
                };
                
                iframe.onerror = function() {
                    if (blockedMessage) {
                        blockedMessage.style.display = "flex";
                    }
                };
                
                setTimeout(function() {
                    var rect = iframe.getBoundingClientRect();
                    if (rect.width === 0 || rect.height === 0) {
                        forceDimensions();
                        setTimeout(function() {
                            var newRect = iframe.getBoundingClientRect();
                            if (newRect.width === 0 || newRect.height === 0) {
                                if (blockedMessage) {
                                    blockedMessage.style.display = "flex";
                                }
                            }
                        }, 100);
                    }
                }, 1000);
            })();
        </script>';
        
        return $content;
    }
    
    /**
     * Clear all plugin data and delete created pages
     */
    public function clear_data() {
        check_ajax_referer('iptv_integration_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $product_pages = get_option('iptv_product_pages', array());
        $deleted_count = 0;
        $error_count = 0;
        
        foreach ($product_pages as $product_id => $page_data) {
            if (isset($page_data['page_id'])) {
                $page_id = $page_data['page_id'];
                $result = wp_delete_post($page_id, true);
                
                if ($result) {
                    $deleted_count++;
                } else {
                    $error_count++;
                }
            }
        }
        
        delete_option('iptv_product_pages');
        
        $message = 'Cleared successfully! ';
        if ($deleted_count > 0) {
            $message .= 'Deleted ' . $deleted_count . ' page(s). ';
        }
        if ($error_count > 0) {
            $message .= 'Failed to delete ' . $error_count . ' page(s).';
        }
        if ($deleted_count === 0 && $error_count === 0) {
            $message = 'No pages to delete. Plugin data cleared.';
        }
        
        wp_send_json_success($message);
    }
}

// Initialize plugin
new IPTV_Integration();

