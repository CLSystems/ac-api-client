<?php

namespace CLSystems\Adcell;

use Exception;

/**
 * Class Client
 *
 * More info:
 * https://www.adcell.de/api/v2/?language=en#&controller=gettingstarted
 *
 * @package CLSystems\Adcell
 */
class Client
{
	const BASE_URI = 'https://www.adcell.de/api';
	const VERSION  = '/v2';

	private $userName;
	private $passWord;

	public function __construct($userName, $passWord)
	{
		$this->userName = $userName;
		$this->passWord = $passWord;
	}

	/**
	 * Get Token from API
	 *
	 * @return string
	 * @throws Exception
	 */
	public function getToken()
	{
		$data = $this->request(
			'user',
			'getToken',
			[
				'userName' => $this->userName,
				'password' => $this->passWord,
			]
		);

		return $data['data']['token'];
	}

	/**
	 * This returns information, terms and conditions and management ratios about programs
	 *
	 * @param $options
	 * @return mixed
	 * @throws Exception
	 * @return array
	 */
	public function getProgramsExport($options): array
	{
		$data = $this->request(
			'affiliate',
			'program/export',
			[
				'userName' => $this->userName,
				'password' => $this->passWord,
			] + $options
		);

		return $data['data'];
	}

	/**
	 * Returns the basic URI for API call
	 *
	 * @return string
	 */
	private function getApiBaseUrl()
	{
		return self::BASE_URI . self::VERSION;
	}

	/**
	 * Starts a Request and returns Data as array
	 *
	 * @param string $service ServiceName
	 * @param string $call MethodName
	 * @param array $options Options
	 * @throws Exception
	 * @return array
	 */
	protected function request(string $service, string $call, array $options): array
	{
		$url = $this->getApiBaseUrl() . '/' . $service . '/' . $call . '?';

		foreach ($options as $key => $value)
		{
			$url .= '&' . $key . '=' . $value;
		}

		$response = file_get_contents($url);
		if (strlen($response) == 0)
		{
			throw new Exception('Invalid contents received');
		}

		$data = json_decode($response, true);
		if (false === $data)
		{
			throw new Exception('Invalid response received');
		}

		if (200 !== (int) $data['status'])
		{
			throw new Exception('Invalid response: ' . $data['message']);
		}

		return $data;
	}

}
