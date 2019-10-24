<?php

namespace birdbook;

class BirdBookBook
{
	private $errors;

	public function __construct()
	{
		add_action('wp_ajax_addtostock', array($this, 'add_to_stock'));
	}

	public function search_by_isbn($isbn)
	{
		$product_id = \wc_get_product_id_by_sku($isbn);

		if ($product_id != 0) {
			$product_attributes = get_post_meta($product_id, '_product_attributes', true);
			$post = get_post($product_id);

			return array(
				'post_id' => $product_id,
				'title' => $post->post_title,
				'subtitle' => $product_attributes['subtitle']['value'],
				'authors' => $product_attributes['authors']['value'],
				'language' => $product_attributes['language']['value'],
				'description' => $post->post_content,
				'pageCount' => $product_attributes['page_count']['value'],
				'publishedDate' => $product_attributes['published_date']['value'],
				'price' => get_post_meta($product_id, '_price', true),
			);
		} else {
			return array();
		}
	}

	public function add_to_stock()
	{
		$data = $_POST;

		if (isset($data['postId'])) {
			$this->increase_stock($data['postId']);
		} else {
			$this->validate($data);

			if (sizeof($this->errors) > 0) {
				wp_send_json_error(array('data' => $data, 'errors' => $this->errors));
			} else {
				$this->errors = $this->add_valid_data_to_database($data);
				if ($error != 0) {
					wp_send_json_error(array('data' => $data, 'errors' => $this->errors));
				} else {
					wp_send_json_success();
				}
			}
		}
	}

	private function increase_stock($post_id, $count = 1)
	{
		// Get an instance of the product object
		$product = wc_get_product($post_id);

		// Get the stock quantity of the product
		$product_stock = $product->get_stock_quantity();

		// Increase back the stock quantity
		if (wc_update_product_stock($product, $count, 'increase')) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

	private function validate($data)
	{
		$this->errors = array();

		if (!isset($data['title']) || strlen($data['title']) == 0) {
			array_push($this->errors, array('title' => 'missing'));
		}

		if (!isset($data['authors']) || strlen($data['authors']) == 0) {
			array_push($this->errors, array('authors' => 'missing'));
		}

		if (!isset($data['language']) || strlen($data['language']) == 0) {
			array_push($this->errors, array('language' => 'missing'));
		}

		if (!isset($data['price'])) {
			array_push($this->errors, array('price' => 'missing'));
		} elseif ($data['price'] <= 0) {
			array_push($this->errors, array('price' => 'invalid value'));
		}
	}

	private function add_valid_data_to_database($data)
	{
		$post_id = wp_insert_post(array(
			'post_title' => $data['title'],
			'post_content' => $data['description'],
			'post_status' => 'draft',
			'post_type' => "product",
		));

		$product_attributes = array();

		$product_attributes[sanitize_title('authors')] = array(
			'name' => wc_clean('authors'), // set attribute name
			'value' => $data['authors'], // set attribute value
			'position' => 1,
			'is_visible' => 1,
			'is_variation' => 0,
			'is_taxonomy' => 0
		);

		update_post_meta($post_id, '_visibility', 'visible');
		update_post_meta($post_id, '_stock_status', 'instock');
		update_post_meta($post_id, 'total_sales', '0');
		update_post_meta($post_id, '_downloadable', 'no');
		update_post_meta($post_id, '_virtual', 'no');
		update_post_meta($post_id, '_regular_price', $data['price']);
		update_post_meta($post_id, '_sale_price', '');
		update_post_meta($post_id, '_purchase_note', '');
		update_post_meta($post_id, '_featured', 'no');
		update_post_meta($post_id, '_weight', '');
		update_post_meta($post_id, '_length', '');
		update_post_meta($post_id, '_width', '');
		update_post_meta($post_id, '_height', '');
		update_post_meta($post_id, '_sku', $data['isbn']);
		update_post_meta($post_id, '_product_attributes', $product_attributes);
		update_post_meta($post_id, '_sale_price_dates_from', '');
		update_post_meta($post_id, '_sale_price_dates_to', '');
		update_post_meta($post_id, '_price', $data['price']);
		update_post_meta($post_id, '_sold_individually', '');
		update_post_meta($post_id, '_manage_stock', 'yes');
		update_post_meta($post_id, '_backorders', 'no');
		update_post_meta($post_id, '_stock', 1);
	}
}
