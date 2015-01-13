<?php

/**
* Plugin Name: MotoPress and WooCommerce Integration
* Plugin URI: http://www.getmotopress.com/
* Description: Extend MotoPress Content Editor plugin with WooCommerce shortcodes and styles.
* Version: 1.0
* Author: MotoPress
* Author URI: http://www.getmotopress.com/
* License: GPL2 or later
*/

if (!defined('ABSPATH')) die();

if (is_admin()) {
    // add 'product' class to product item
    add_filter( 'post_class', 'motopress_woocommerce_product_post_class', 10, 3 );
    
    // integrate WooCommerce shortcodes to MotoPress Content Editor
    add_action('mp_library', 'motopress_woocommerce_mp_library_action', 10, 1);
}    

function motopress_woocommerce_product_post_class( $classes, $class = '', $post_id = '' ) {
    if ( ! $post_id || 'product' !== get_post_type( $post_id ) ) {
        return $classes;
    }
    if ( false === ( $key = array_search( 'product', $classes ) ) ) {
		$classes[] = 'product';
	}

    return $classes;
}

function motopress_woocommerce_mp_library_action($motopressCELibrary) {

    $widdgetIcon = ltrim( (str_replace( content_url(), '' , plugin_dir_url( __FILE__ ) ) . 'widget-icon.png'), '/');
    $leftPanelIcon = ltrim( (str_replace( content_url(), '' , plugin_dir_url( __FILE__ ) ) . 'left-panel-icon.png'), '/');
	
    $orderby = array(
        'type' => 'radio-buttons',
        'label' => _('Order by'),
        'default' => 'date',
        'list' => array(
            'menu_order' => _('Menu'),
            'title' => _('Title'),
            'date' => _('Date'),
            'rand' => _('Random'),
            'ID' => _('Id'),
        ),
    );
    
    $order = array(
        'type' => 'radio-buttons',
        'label' => _('Order'),
        'default' => 'DESC',
        'list' => array(
            'ASC' => _('Ascending'),
            'DESC' => _('Descending'),
        ),
    );

    $mpCart = new MPCEObject('woocommerce_cart', _('Cart'), $widdgetIcon, array(), 0);
    
    $mpCheckout = new MPCEObject('woocommerce_checkout', _('Checkout'), $widdgetIcon, array(), 0);
    
    $mpOrderTrackingForm = new MPCEObject('woocommerce_order_tracking', _('Order Tracking Form'), $widdgetIcon, array(), 0);
    
    $mpWoocommerceMyAccount = new MPCEObject('woocommerce_my_account', _('My Account'), $widdgetIcon,
        array(
            'order_count' => array(
                'type' => 'text',
                'label' => _('Number or order to show'),
                'description' => _('Set by default to 15 (use -1 to display all orders'),
                'default' => '15',
            ),
        ),
        0
	);
    
	$mpRecentProducts = new MPCEObject('recent_products', _('Recent Products'), $widdgetIcon,
        array(
            'per_page' => array(
                'type' => 'text',
                'label' => _('Products'),
                'description' => _('How many products to show on the page'),
                'default' => '12',
            ),
            'columns' => array(
                'type' => 'text',
                'label' => _('Columns'),
                'description' => _('How many columns wide the products should be'),
                'default' => '4',
            ),
            'orderby' => $orderby,
            'order' => $order,
        ),
        0
	);

    $mpFeaturedProducts = new MPCEObject('featured_products', _('Featured Products'), $widdgetIcon,
        array(
            'per_page' => array(
                'type' => 'text',
                'label' => _('Products'),
                'description' => _('How many products to show which have been set as <a target="_blank" href="http://docs.woothemes.com/document/managing-products/">featured</a>'),
                'default' => '12',
            ),
            'columns' => array(
                'type' => 'text',
                'label' => _('Columns'),
                'description' => _('How many columns wide the products should be'),
                'default' => '4',
            ),
            'orderby' => $orderby,
            'order' => $order,
        ),
        0
	);
    
    $mpProduct = new MPCEObject('product', _('Product'), $widdgetIcon,
        array(
            'id' => array(
                'type' => 'text',
                'label' => _('Show a single product by ID'),
                'description' => _('To find the Product ID, go to the Product > Edit screen and look in the URL for the post= in the address bar'),
            ),
            'sku' => array(
                'type' => 'text',
                'label' => _('Show a single product by SKU'),
            ),
        ),
        0
	);
    
    $mpProducts = new MPCEObject('products', _('Products'), $widdgetIcon,
        array(
            'ids' => array(
                'type' => 'text',
                'label' => _('Show multiple products by ID'),
                'description' => _('example: 1, 2, 3, 4, 5'),
            ),
            'skus' => array(
                'type' => 'text',
                'label' => _('Show multiple products by SKU'),
                'description' => _('example: foo, bar, baz'),
            ),
            'columns' => array(
                'type' => 'text',
                'label' => _('Columns'),
                'description' => _('How many columns wide the products should be'),
                'default' => '4',
            ),
            'orderby' => $orderby,
            'order' => $order,
        ),
        0
	);
    
    $mpAddToCart = new MPCEObject('add_to_cart', _('Add to cart'), $widdgetIcon,
        array(
            'id' => array(
                'type' => 'text',
                'label' => _('Product ID'),
                'description' => _('Show the price and add to cart button of a single product by ID'),
            ),
            'sku' => array(
                'type' => 'text',
                'label' => _('Product SKU'),
                'description' => _('Show the price and add to cart button of a single product by SKU'),
            ),
            'style' => array(
                'type' => 'text',
                'label' => _('Style'),
            ),
            'show_price' => array(
                'type' => 'checkbox',
                'label' => _('Show Price'),
                'default' => 'true',
            ),
        ),
        0
	);
    
    $mpProductPage = new MPCEObject('product_page', _('Product page'), $widdgetIcon,
        array(
            'id' => array(
                'type' => 'text',
                'label' => _('Show a full single product page by ID'),
            ),
            'sku' => array(
                'type' => 'text',
                'label' => _('Show a full single product page by SKU'),
            ),
        ),
        0
	);
    
    $mpProductCategory = new MPCEObject('product_category', _('Product category'), $widdgetIcon,
        array(
            'per_page' => array(
                'type' => 'text',
                'label' => _('Products'),
                'description' => _('How many products to show on the page'),
                'default' => '12',
            ),
            'columns' => array(
                'type' => 'text',
                'label' => _('Columns'),
                'description' => _('How many columns wide the products should be'),
                'default' => '4',
            ),
            'orderby' => $orderby,
            'order' => $order,
            'category' => array(
                'type' => 'text',
                'label' => _('Show multiple products in a category by slug'),
                'description' => _('Go to: WooCommerce > Products > Categories to find the slug column'),
                'default' => '',
            ),
        ),
        0
	);
    
    $mpProductCategories = new MPCEObject('product_categories', _('Product Categories'), $widdgetIcon,
        array(
            'number' => array(
                'type' => 'text',
                'label' => _('Number'),
                'description' => _('The "number" field is used to display the number of products and the "ids" field is to tell the shortcode which categories to display'),
                'default' => 'null',
            ),
            'columns' => array(
                'type' => 'text',
                'label' => _('Columns'),
                'description' => _('How many columns wide the products should be'),
                'default' => '4',
            ),
            'orderby' => $orderby,
            'order' => $order,
            'hide_empty' => array(
                'type' => 'checkbox',
                'label' => _('Hide empty'),
                'default' => 'true',
            ),
            'parent' => array(
                'type' => 'text',
                'label' => _('Parent'),
                'description' => _('Set the parent parameter to 0 to only display top level categories'),
                'default' => '',
            ),
            'ids' => array(
                'type' => 'text',
                'label' => _('ids'),
                'description' => _('Set ids to a comma separated list of category ids to only show those'),
                'default' => '',
            ),
        ),
        0
	);
    
    $mpSaleProducts = new MPCEObject('sale_products', _('Sale Products'), $widdgetIcon,
        array(
            'per_page' => array(
                'type' => 'text',
                'label' => _('Products'),
                'description' => _('How many products to show on the page'),
                'default' => '12',
            ),
            'columns' => array(
                'type' => 'text',
                'label' => _('Columns'),
                'description' => _('How many columns wide the products should be'),
                'default' => '4',
            ),
            'orderby' => $orderby,
            'order' => $order,
        ),
        0
	);
    
    $mpBestSellingProducts = new MPCEObject('best_selling_products', _('Best Selling Products'), $widdgetIcon,
        array(
            'per_page' => array(
                'type' => 'text',
                'label' => _('Products'),
                'description' => _('How many products to show on the page'),
                'default' => '12',
            ),
            'columns' => array(
                'type' => 'text',
                'label' => _('Columns'),
                'description' => _('How many columns wide the products should be'),
                'default' => '4',
            ),
        ),
        0
	);
    
    $mpTopRatedProducts = new MPCEObject('top_rated_products', _('Top Rated Products'), $widdgetIcon,
        array(
            'per_page' => array(
                'type' => 'text',
                'label' => _('Products'),
                'description' => _('How many products to show on the page'),
                'default' => '12',
            ),
            'columns' => array(
                'type' => 'text',
                'label' => _('Columns'),
                'description' => _('How many columns wide the products should be'),
                'default' => '4',
            ),
            'orderby' => $orderby,
            'order' => $order,
        ),
        0
	);
    
    $mpProductAttribute = new MPCEObject('product_attribute', _('Top Rated Products'), $widdgetIcon,
        array(
            'per_page' => array(
                'type' => 'text',
                'label' => _('Products'),
                'description' => _('How many products to show on the page'),
                'default' => '12',
            ),
            'columns' => array(
                'type' => 'text',
                'label' => _('Columns'),
                'description' => _('How many columns wide the products should be'),
                'default' => '4',
            ),
            'orderby' => $orderby,
            'order' => $order,
            'attribute' => array(
                'type' => 'text',
                'label' => _('Attribute'),
                'default' => 'color',
            ),
            'filter' => array(
                'type' => 'text',
                'label' => _('Filter'),
                'default' => 'black',
            ),
        ),
        0
	);
    
    $mpRelatedProducts = new MPCEObject('related_products', _('Related Products'), $widdgetIcon,
        array(
            'per_page' => array(
                'type' => 'text',
                'label' => _('Products'),
                'description' => _('How many products to show on the page'),
                'default' => '12',
            ),
            'columns' => array(
                'type' => 'text',
                'label' => _('Columns'),
                'description' => _('How many columns wide the products should be'),
                'default' => '4',
            ),
            'orderby' => $orderby,
        ),
        0
	);

    $woocommerceGroup = new MPCEGroup();
    $woocommerceGroup->setId(MPCEShortcode::PREFIX . 'woocommerce');
    $woocommerceGroup->setName("WooCommerce");
    $woocommerceGroup->setIcon($leftPanelIcon);
    $woocommerceGroup->setPosition(60);
    $woocommerceGroup->addObject(array($mpRecentProducts, $mpFeaturedProducts, $mpProduct,
        $mpProducts, $mpAddToCart, $mpProductPage, $mpProductCategory, $mpProductCategories,
        $mpSaleProducts, $mpBestSellingProducts, $mpTopRatedProducts, $mpProductAttribute,
        $mpRelatedProducts, $mpCart, $mpCheckout, $mpOrderTrackingForm, $mpWoocommerceMyAccount));

    $motopressCELibrary->addGroup($woocommerceGroup);
}