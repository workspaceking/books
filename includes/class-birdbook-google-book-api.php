<?php

/**
 * Created by PhpStorm.
 * User: Rashid
 * Date: 06.09.17
 * Time: 16:49
 */

namespace birdbook;


class BirdBookGoogleBookApi
{

	const API_SEARCH_URL = 'https://www.googleapis.com/books/v1/volumes?q=';

	public function search_book($keyword)
	{
		$options = get_option('birdbook_options');
		$api_key = $options['birdbook_google_book_api_key'];

		$api_results = wp_remote_get(
			$this::API_SEARCH_URL . $keyword . '&key=' . $api_key
		);

		if (isset($api_results['body'])) {
			$api_results = json_decode($api_results['body'], true);
			if (isset($api_results['error'])) {
				wp_send_json_error(array());
			} else {
				$response = $this->reformat_data_for_ajax_response($api_results['items']);
				wp_send_json_success($response);
			}
		} else {
			wp_send_json_error(array());
		}
	}

	private function reformat_data_for_ajax_response($api_results)
	{
		$response = array();

		foreach ($api_results as $key => $item) {
			$item_response = array(
				'title' => $item['volumeInfo']['title'],
				'subtitle' => $item['volumeInfo']['subtitle'],
				'authors' => $item['volumeInfo']['authors'],
				'description' => $item['volumeInfo']['description'],
				'language' => $item['volumeInfo']['language'],
				'pageCount' => $item['volumeInfo']['pageCount'],
				'maturityRating' => $item['volumeInfo']['maturityRating'],
				'categories' => $item['volumeInfo']['categories'],
				'publishedDate' => $item['volumeInfo']['publishedDate'],
			);
			array_push($response, $item_response);
		}
		return $response;
	}

	private function is_valid_info_from_google_books_api()
	{
		if (
			isset($this->google_book_api_results['items']) &&
			sizeof($this->google_book_api_results['items']) > 0
		) {

			$i = 0;

			while (
				$i < sizeof($this->google_book_api_results['items'])
				&& $this->google_book_api_matched_item < 0
			) {

				$item = $this->google_book_api_results['items'][$i];

				if (isset($item['volumeInfo']['industryIdentifiers'][0]['identifier'])) {
					if ($item['volumeInfo']['industryIdentifiers'][0]['identifier'] == $this->isbn) {
						$this->google_book_api_matched_item = $i;
					}
				}

				if (isset($item['volumeInfo']['industryIdentifiers'][1]['identifier'])) {
					if ($item['volumeInfo']['industryIdentifiers'][1]['identifier'] == $this->isbn) {
						$this->google_book_api_matched_item = $i;
					}
				}
				$i++;
			}


			if ($this->google_book_api_matched_item == -1) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	private function load_info_from_google_books_api()
	{
		$this->title = $this->google_book_api_results['items'][$this->google_book_api_matched_item]['volumeInfo']['title'];
		$this->description = $this->google_book_api_results['items'][$this->google_book_api_matched_item]['volumeInfo']['description'];
		$this->published_date = $this->google_book_api_results['items'][$this->google_book_api_matched_item]['volumeInfo']['publishedDate'];
		$this->page_count = $this->google_book_api_results['items'][$this->google_book_api_matched_item]['volumeInfo']['pageCount'];

		$this->authors = implode(',', $this->google_book_api_results['items'][$this->google_book_api_matched_item]['volumeInfo']['authors']);
	}
}
