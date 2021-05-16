<?php
namespace Luoyecb\Utils;

define('CRLF', "\r\n");

class HttpClient {

	public function postForm(string $url, array $data): string {
		$headers = [
			'Content-type' => 'application/x-www-form-urlencoded',
		];
		return $this->post($url, http_build_query($data), $headers);
	}

	public function postJson(string $url, array $data): string {
		$headers = [
			'Content-type' => 'application/json',
		];
		return $this->post($url, json_encode($data), $headers);
	}

	public function post(string $url, string $rawdata, array $headers): string {
		$options = [
			'http' => [
				'method' => 'POST',
				'content' => $rawdata,
			],
		];

		$header_str = $this->buildHeaders($headers);
		if (!empty($header_str)) {
			$options['http']['header'] = $header_str;
		}

		$context = stream_context_create($options);
		return file_get_contents($url, false, $context);
	}

	public function get(string $url): string {
		return file_get_contents($url);
	}

	private function buildHeaders(array $headers) {
		if (empty($headers)) {
			return "";
		}

		$header_str = "";
		foreach ($headers as $k => $v) {
			$header_str .= ($k . ":" . $v . CRLF . CRLF);
		}
		return $header_str;
	}

}
