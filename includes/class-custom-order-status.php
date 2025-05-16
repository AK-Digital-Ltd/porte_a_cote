<?php
/**
 * Class to manage custom WooCommerce order statuses
 *
 * @package ChildTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class Custom_Order_Status
 */
class Custom_Order_Status
{

    /**
     * List of custom order statuses
     *
     * @var array
     */
    private $custom_statuses = array();

    /**
     * Constructor
     * 
     * @param array $statuses Custom statuses array
     */
    public function __construct($statuses = array())
    {
        // Set custom statuses
        $this->custom_statuses = $statuses;

        // Hooks
        add_action('init', array($this, 'register_custom_order_statuses'));
        add_filter('wc_order_statuses', array($this, 'add_custom_order_statuses'));
        add_filter('woocommerce_admin_order_actions', array($this, 'add_custom_order_status_actions'), 10, 2);
        add_filter('woocommerce_email_classes', array($this, 'register_custom_email_classes'));
        add_filter('woocommerce_email_actions', array($this, 'register_custom_email_actions'));
    }

    /**
     * Register custom order statuses
     */
    public function register_custom_order_statuses()
    {
        foreach ($this->custom_statuses as $status_slug => $status_data) {
            register_post_status($status_slug, array(
                'label' => $status_data['label'],
                'public' => true,
                'exclude_from_search' => false,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop($status_data['label'] . ' <span class="count">(%s)</span>', $status_data['label'] . ' <span class="count">(%s)</span>'),
            ));
        }
    }

    /**
     * Add custom statuses to WooCommerce order status list
     * Position them right after "Completed" status
     *
     * @param array $order_statuses Existing order statuses
     * @return array Updated order statuses
     */
    public function add_custom_order_statuses($order_statuses)
    {
        // Create a new array to store the reordered statuses
        $new_order_statuses = array();
        
        // Loop through the original statuses
        foreach ($order_statuses as $key => $status) {
            // Add the original status
            $new_order_statuses[$key] = $status;
            
            // If this is the "completed" status, add our custom statuses right after it
            if ($key === 'wc-completed') {
                foreach ($this->custom_statuses as $status_slug => $status_data) {
                    $new_order_statuses[$status_slug] = $status_data['public_name'];
                }
            }
        }
        
        return $new_order_statuses;
    }

    /**
     * Add actions for custom statuses in admin
     *
     * @param array $actions Existing actions
     * @param WC_Order $order Order object
     * @return array Updated actions
     */
    public function add_custom_order_status_actions($actions, $order)
    {
        // Logic to add action buttons in admin if needed
        return $actions;
    }

    /**
     * Register custom email classes
     *
     * @param array $email_classes Existing email classes
     * @return array Updated email classes
     */
    public function register_custom_email_classes($email_classes)
    {
        // Register custom email classes for each status
        foreach ($this->custom_statuses as $status_slug => $status_data) {
            $status_key = str_replace('wc-', '', $status_slug);

            // Skip default WooCommerce statuses that already have email templates
            if (in_array($status_key, array('processing', 'completed', 'cancelled', 'refunded', 'on-hold'))) {
                continue;
            }

            // Define class name
            $class_name = 'WC_Email_Customer_' . str_replace('-', '_', $status_key) . '_Order';

            // Create email class if it doesn't exist yet
            if (!class_exists($class_name)) {
                // Define the email class on-the-fly
                $email_classes[$class_name] = new class ($status_key, $status_data) extends WC_Email {
                    private $status_key;
                    private $status_data;

                    public function __construct($status_key, $status_data)
                    {
                        $this->status_key = $status_key;
                        $this->status_data = $status_data;

                        // Standard WC_Email constructor elements
                        $this->id = 'customer_' . $status_key . '_order';
                        $this->customer_email = true;
                        $this->title = sprintf(__('Customer %s Order', 'child-theme'), $status_data['label']);
                        $this->description = sprintf(
                            __('Order %s email is sent when an order status is changed to %s.', 'child-theme'),
                            $status_data['label'],
                            $status_data['label']
                        );
                        $this->template_html = 'emails/customer-' . $status_key . '-order.php';
                        $this->template_plain = 'emails/plain/customer-' . $status_key . '-order.php';
                        $this->placeholders = array(
                            '{order_date}' => '',
                            '{order_number}' => '',
                        );

                        // Triggers for this email
                        add_action('woocommerce_customer_' . $status_key . '_order_email', array($this, 'trigger'), 10, 1);

                        // Call parent constructor
                        parent::__construct();
                        $this->template_base = get_stylesheet_directory() . '/woocommerce/';
                    }

                    /**
                     * Trigger the email
                     */
                    public function trigger($order)
                    {
                        // Make sure we have an order
                        if (!$order) {
                            return;
                        }

                        // Get order ID
                        $this->object = wc_get_order($order);

                        if ($this->object instanceof WC_Order) {
                            $this->placeholders['{order_date}'] = wc_format_datetime($this->object->get_date_created());
                            $this->placeholders['{order_number}'] = $this->object->get_order_number();
                            $this->recipient = $this->object->get_billing_email();
                        }

                        // Do not send if no recipient
                        if (!$this->get_recipient()) {
                            return;
                        }

                        // Send email
                        $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
                    }

                    /**
                     * Get content HTML
                     */
                    public function get_content_html()
                    {
                        return wc_get_template_html(
                            $this->template_html,
                            array(
                                'order' => $this->object,
                                'email_heading' => $this->get_heading(),
                                'sent_to_admin' => false,
                                'plain_text' => false,
                                'email' => $this,
                                'status_data' => $this->status_data,
                            ),
                            '',
                            $this->template_base
                        );
                    }

                    /**
                     * Get content plain
                     */
                    public function get_content_plain()
                    {
                        return wc_get_template_html(
                            $this->template_plain,
                            array(
                                'order' => $this->object,
                                'email_heading' => $this->get_heading(),
                                'sent_to_admin' => false,
                                'plain_text' => true,
                                'email' => $this,
                                'status_data' => $this->status_data,
                            ),
                            '',
                            $this->template_base
                        );
                    }

                    /**
                     * Get default subject
                     */
                    public function get_default_subject()
                    {
                        return sprintf(
                            __('Your {site_title} order has been %s - #{order_number}', 'child-theme'),
                            strtolower($this->status_data['label'])
                        );
                    }

                    /**
                     * Get default heading
                     */
                    public function get_default_heading()
                    {
                        return sprintf(__('Your order has been %s', 'child-theme'), strtolower($this->status_data['label']));
                    }
                };
            }
        }

        return $email_classes;
    }

    /**
     * Register custom email actions
     *
     * @param array $email_actions Existing email actions
     * @return array Updated email actions
     */
    public function register_custom_email_actions($email_actions)
    {
        // Add actions to trigger emails
        foreach ($this->custom_statuses as $status_slug => $status_data) {
            $status_key = str_replace('wc-', '', $status_slug);
            $email_actions[] = 'woocommerce_order_status_' . $status_key;

            // Add transition actions
            foreach ($this->custom_statuses as $target_slug => $target_data) {
                $target_key = str_replace('wc-', '', $target_slug);
                if ($status_key !== $target_key) {
                    $email_actions[] = 'woocommerce_order_status_' . $status_key . '_to_' . $target_key;
                }
            }
        }

        return $email_actions;
    }

    /**
     * Get list of custom statuses
     *
     * @return array List of custom statuses
     */
    public function get_custom_statuses()
    {
        return $this->custom_statuses;
    }
}