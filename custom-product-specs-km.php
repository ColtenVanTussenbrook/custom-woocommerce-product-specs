<?php
/*
Plugin Name: Custom Product Specs KM
Plugin URI:  kitemedia.com/plugins
Description: Plugin to add custom specs to listed products
Version:     1.0
Author:      Colten Van Tussenbrook
Author URI:  coltenv.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
*/

//create custom fields for square footage, width, depth
// display custom fields
function woocommerce_product_custom_fields () {
  global $woocommerce, $post;
  echo '<div class="product_custom_field">';
  // Custom Product Number Field
    woocommerce_wp_text_input(
      array(
          'id' => 'square_footage_number_field',
          'placeholder' => 'Square Footage',
          'label' => __('Square Footage', 'woocommerce'),
          'type' => 'number',
          'custom_attributes' => array(
              'step' => 'any',
              'min' => '0',
              'max' => '10,000'
          )
      )
    );
  echo '</div>';
  echo '<div class="product_custom_field_width">';
  // Custom Product Number Field
    woocommerce_wp_text_input(
      array(
          'id' => 'width_number_field',
          'placeholder' => 'Width',
          'label' => __('Width', 'woocommerce'),
          'type' => 'number',
          'custom_attributes' => array(
              'step' => 'any',
              'min' => '0',
              'max' => '199'
          )
      )
    );
  echo '</div>';
  echo '<div class="product_custom_field_depth">';
  // Custom Product Number Field
    woocommerce_wp_text_input(
      array(
          'id' => 'depth_number_field',
          'placeholder' => 'Depth',
          'label' => __('Depth', 'woocommerce'),
          'type' => 'number',
          'custom_attributes' => array(
              'step' => 'any',
              'min' => '0',
              'max' => '199'
          )
      )
    );
  echo '</div>';
  }
add_action( 'woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields' );

//save input from custom fields
function woocommerce_product_custom_fields_save($post_id) {
    $square_footage_input = $_POST['square_footage_number_field'];
    if (!empty($square_footage_input))
        update_post_meta($post_id, 'square_footage_number_field', esc_attr($square_footage_input));

    $width_input = $_POST['width_number_field'];
    if (!empty($width_input))
        update_post_meta($post_id, 'width_number_field', esc_attr($width_input));

    $depth_input = $_POST['depth_number_field'];
    if (!empty($depth_input))
        update_post_meta($post_id, 'depth_number_field', esc_attr($depth_input));
  }
add_action( 'woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save' );

//display new specs under products
function greenlean_display_meta(){
  global $product;
  $square_footage_display = 0;
  $width_display = 0;
  $depth_display = 0;

//get taxonomy or category
  $bedrooms = get_the_terms($product->get_id(), 'product_cat');
  $bathrooms = get_the_terms($product->get_id(), 'bathrooms');
  $levels = get_the_terms($product->get_id(), 'levels');
  $square_footage = get_post_meta($product->get_id(), 'square_footage_number_field');
    if (isset($square_footage[0])) {
      $square_footage_display = number_format($square_footage[0]);
    }
    else {
      $square_footage_display = "not set";
    }
  $width = get_post_meta($product->get_id(), 'width_number_field');
    if (isset($width[0])) {
      $width_display = number_format($width[0]);
      $width_display .= "ft";
    }
    else {
      $width_display = "not set";
    }
  $depth = get_post_meta($product->get_id(), 'depth_number_field');
    if (isset($depth[0])) {
      $depth_display = number_format($depth[0]);
      $depth_display .= "ft";
    }
    else {
      $depth_display = "not set";
    }

  //pluck taxonomy or category from array
  $bedrooms_pull = wp_list_pluck($bedrooms, 'name');
  $bathrooms_pull = wp_list_pluck($bathrooms, 'name');
  $levels_pull = wp_list_pluck($levels, 'name');

  //get needed data from array
  $bedrooms_temp = implode(", ", $bedrooms_pull);
    //delete word 'bedrooms'
  $bedrooms_display = str_replace('Bedroom', '', $bedrooms_temp);
  $bathrooms_display = implode(", ", $bathrooms_pull);
  $levels_display = implode(", ", $levels_pull);

  $new_price = $product->get_price_html();

  echo '<div class="product-added-meta-1">';
  echo '<span class="greenlean-meta-back">' . "Bedrooms: $bedrooms_display" . '</span>';
  echo '<span class="greenlean-meta-back">' . "Bathrooms: $bathrooms_display" . '</span>';
  echo '<span class="greenlean-meta-back">' . "SqFt: $square_footage_display" . '</span><br>';
  echo '</div>';
  echo '<div class="product-added-meta-2">';
  echo '<span class="greenlean-meta-back">' . "Levels: $levels_display" . '</span>';
  echo '<span class="greenlean-meta-back">' . "Width: $width_display" . '</span>';
  echo '<span class="greenlean-meta-back">' . "Depth: $depth_display" . '</span>';
  echo '</div>';
  echo '<span class="new-price-display">' . $new_price . '</span>';

}

  add_action('woocommerce_after_shop_loop_item', 'greenlean_display_meta');
?>