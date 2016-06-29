<?php
/**
 * The MIT License (MIT)
 * 
 * Copyright (c) 2016 Smartex.io Ltd.
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
 
/**
 * Smartex Payment Model
 */
class ModelPaymentSmartex extends Model {

	/** @var SmartexLibrary $smartex */
	private $smartex;

	/**
	 * Smartex Payment Model Construct
	 * @param Registry $registry
	 */
	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->language('payment/smartex');
		$this->load->library('smartex');
		$this->smartex = new SmartexLibrary($registry);
	}

	/**
	 * Returns the Smartex Payment Method if available
	 * @param  array $address Customer billing address
	 * @return array|void Smartex Payment Method if available
	 */
	public function getMethod($address)	{
		// Check for connection to Smartex
		$this->smartex->checkConnection();
		if ($this->smartex->setting('connection') === 'disconnected') {
			$this->smartex->log('warn', 'You cannot have Smartex enabled as a payment method without being connected to Smartex.');
			return;
		}

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->smartex->setting('geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		// All Geo Zones configured or address is in configured Geo Zone
		if (!$this->config->get('smartex_geo_zone_id') || $query->num_rows) {
			return array(
				'code'	   => 'smartex',
				'title'	  => $this->language->get('text_title'),
				'terms'	  => '',
				'sort_order' => $this->smartex->setting('sort_order')
			);
		}
	}
}
