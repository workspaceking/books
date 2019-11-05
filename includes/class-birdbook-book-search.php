<?php

/**
 * Created by PhpStorm.
 * User: Rashid
 * Date: 08.09.17
 * Time: 10:23
 */

namespace birdbook;

require_once __DIR__ . '/class-birdbook-book.php';
require_once  __DIR__ . '/class-birdbook-google-book-api.php';

class birdbooksearch
{

	private $google_api;
	private $birdbook_book;

	public function __construct($primary_resource, $secondary_resource)
	{
		$this->birdbook_book = $primary_resource;
		$this->google_api = $secondary_resource;

		add_action('wp_ajax_searchbookbyisbn', array($this, 'get_books_by_isbn'));
	}

	public function get_books_by_isbn()
	{
		$isbn = $_GET['isbn'];

		$results_from_database = $this->birdbook_book->search_by_isbn($isbn);

		if (sizeof($results_from_database) > 0) {
			wp_send_json_success($results_from_database);
		} else {
			$this->get_books_from_google_api($isbn);
		}
	}

	private function get_books_from_google_api($isbn)
	{
		$results_from_google = $this->google_api->search_book($isbn);
		if (sizeof($results_from_google) > 0) {
			wp_send_json_success($results_from_google);
		} else {
			wp_send_json_error(array());
		}
	}
}
