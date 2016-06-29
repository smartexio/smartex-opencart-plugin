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
 * Smartex Payment Controller
 */
class ControllerPaymentSmartex extends Controller {

	/** @var boolean $ajax Whether the request was made via AJAX */
	private $ajax = false;

	/** @var SmartexLibrary $smartex */
	private $smartex;

	/**
	 * Smartex Payment Controller Constructor
	 * @param Registry $registry
	 */
	public function __construct($registry) {
		parent::__construct($registry);

		// Make langauge strings and Smartex Library available to all
		$this->load->language('payment/smartex');
		$this->load->library('smartex');
		$this->smartex = new SmartexLibrary($registry);

		// Setup logging
		$this->logger = new Log('smartex.log');

		// Is this an ajax request?
		if (!empty($this->request->server['HTTP_X_REQUESTED_WITH']) &&
			strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			$this->ajax = true;
		}

		// Check for connection
		if ($this->setting('connection') !== 'disconnected' && $this->setting('connection') !== null) {
			$was_connected = ($this->setting('connection') === 'connected');
			$was_network = $this->setting('network');
			$this->smartex->checkConnection();
		}
	}

	/**
	 * Displays the Payment Method (a redirect button)
	 * @return void
	 */
	public function index() {
		$data['testnet'] = ($this->setting('network') === 'livenet') ? false : true;
		$data['warning_testnet'] = $this->language->get('warning_testnet');
		$data['url_redirect'] = $this->url->link('payment/smartex/confirm', $this->config->get('config_secure'));
		$data['button_confirm'] = $this->language->get('button_confirm');

		if (isset($this->session->data['error_smartex'])) {
			unset($this->session->data['error_smartex']);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/smartex.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/smartex.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/smartex.tpl', $data);
		}
	}

	/**
	 * Generates redirect to invoice url
	 * @return void
	 */
	public function confirm() {
		$this->load->model('checkout/order');
		if (!isset($this->session->data['order_id'])) {
			$this->response->redirect($this->url->link('checkout/cart'));
			return;
		}
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		if (false === $order_info) {
			$this->response->redirect($this->url->link('checkout/cart'));
			return;
		}

		try {
			$invoice = $this->prepareInvoice($order_info);
			$invoice = $this->smartex->createInvoice($invoice);
		} catch (Exception $e) {
			$this->session->data['error_smartex'] = 'Sorry, but there was a problem communicating with Smartex for Ethereum checkout.';
			$this->response->redirect($this->url->link('checkout/checkout'));
			return;
		}

		$this->session->data['smartex_invoice'] = $invoice->getId();
		$this->response->redirect($invoice->getUrl());
	}

	/**
	 * Convenience wrapper for smartex logs
	 * @param string $level The type of log.
	 *					  Should be 'error', 'warn', 'info', 'debug', 'trace'
	 *					  In normal mode, 'error' and 'warn' are logged
	 *					  In debug mode, all are logged
	 * @param string $message The message of the log
	 * @param int $depth Depth addition for debug backtracing
	 * @return void
	 */
	public function log($level, $message, $depth = 0) {
		$depth += 1;
		$this->smartex->log($level, $message, $depth);
	}

	/**
	 * Convenience wrapper for smartex settings
	 *
	 * Automatically persists to database on set and combines getting and setting into one method
	 * Assumes 'smartex_' prefix
	 *
	 * @param string $key Setting key
	 * @param string $value Setting value if setting the value
	 * @return string|null|void Setting value, or void if setting the value
	 */
	public function setting($key, $value = null) {
		// Set the setting
		if (func_num_args() === 2) {
			return $this->smartex->setting($key, $value);
		}

		// Get the setting
		return $this->smartex->setting($key);
	}

	/**
	 * Prepares an Invoice to send to Smartex
	 *
	 * @param array $order_info OpenCart checkout order
	 * @return Invoice
	 */
	private function prepareInvoice($order_info = array()) {
		$invoice = new \Smartex\Invoice();
		if (empty($order_info['order_id'])) {
			$this->log('error', 'Cannot prepare invoice without `order_id`');
			throw Exception('Cannot prepare invoice without `order_id`');
		}
		$this->log('info', sprintf('Preparing Invoice for Order %s', (string)$order_info['order_id']));
		$invoice->setOrderId((string)$order_info['order_id']);
		if (empty($order_info['currency_code'])) {
			$this->log('error', 'Cannot prepare invoice without `currency_code`');
			throw Exception('Cannot prepare invoice without `currency_code`');
		}
		$invoice->setCurrency(new \Smartex\Currency($order_info['currency_code']));
		if (empty($order_info['total'])) {
			$this->log('error', 'Cannot prepare invoice without `total`');
			throw Exception('Cannot prepare invoice without `total`');
		}
		$invoice->setPrice((float)$order_info['total']);

		// Send Buyer Information?
		if ($this->setting('send_buyer_info')) {
			$buyer = new \Smartex\Buyer();
			$buyer->setFirstName($order_info['firstname'])
					->setLastName($order_info['lastname'])
					->setEmail($order_info['email'])
					->setPhone($order_info['telephone'])
					->setAddress(array($order_info['payment_address_1'], $order_info['payment_address_2']))
					->setCity($order_info['payment_city'])
					->setState($order_info['payment_zone_code'])
					->setZip($order_info['payment_postcode'])
					->setCountry($order_info['payment_country']);
			$invoice->setBuyer($buyer);
		}

		$invoice->setFullNotifications(true);

		$return_url = $this->setting('return_url');
		if (empty($return_url)) {
			$return_url = $this->url->link('payment/smartex/success', $this->config->get('config_secure'));
		}

		$notify_url = $this->setting('notify_url');
		if (empty($notify_url)) {
			$notify_url = $this->url->link('payment/smartex/callback', $this->config->get('config_secure'));
		}

		$invoice->setRedirectUrl($return_url);
		$invoice->setNotificationUrl($notify_url);
		$invoice->setTransactionSpeed($this->setting('risk_speed'));
		return $invoice;
	}

	/**
	 * Success return page
	 *
	 * Progresses the order if valid, and redirects to OpenCart's Checkout Success page
	 *
	 * @return void
	 */
	public function success() {
		$this->load->model('checkout/order');
		$order_id = $this->session->data['order_id'];
		if (is_null($order_id)) {
			$this->response->redirect($this->url->link('checkout/success'));
			return;
		}

		$order = $this->model_checkout_order->getOrder($order_id);
		try {
			$invoice = $this->smartex->getInvoice($this->session->data['smartex_invoice']);
		} catch (Exception $e) {
			$this->response->redirect($this->url->link('checkout/success'));
			return;
		}

		switch ($invoice->getStatus()) {
			case 'paid':
				$order_status_id = $this->setting('paid_status');
				$order_message = $this->language->get('text_progress_paid');
				break;
			case 'confirmed':
				$order_status_id = $this->setting('confirmed_status');
				$order_message = $this->language->get('text_progress_confirmed');
				break;
			case 'complete':
				$order_status_id = $this->setting('complete_status');
				$order_message = $this->language->get('text_progress_complete');
				break;
			default:
				$this->response->redirect($this->url->link('checkout/checkout'));
				return;
		}

		// Progress the order status
		$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
		$this->session->data['smartex_invoice'] = null;
		$this->response->redirect($this->url->link('checkout/success'));
	}

	/**
	 * IPN Handler
	 * @return void
	 */
	public function callback() {
		$this->load->model('checkout/order');

		$post = file_get_contents("php://input");
		if (empty($post)) {
			$this->log('warn', 'IPN handler called with no data');
			return;
		}

		$json = @json_decode($post, true);
		if (empty($json)) {
			$this->log('warn', 'IPN handler called with invalid data');
			$this->log('trace', 'Invalid JSON: ' . $post);
			return;
		}

		if (!array_key_exists('id', $json)) {
			$this->log('warn', 'IPN handler called with invalid data');
			$this->log('trace', 'Invoice object missing ID field: ' . $var_export($json, true));
			return;
		}

		if (!array_key_exists('url', $json)) {
			$this->log('warn', 'IPN handler called with invalid data');
			$this->log('trace', 'Invoice object missing URL field: ' . $var_export($json, true));
			return;
		}

		// Try to set the network based on the url first since the merchant may have
		// switched networks while test invoices are still being confirmed
		$network = null;
		if (true === strpos($json['url'], 'https://test.smartex.io')) {
			$network = 'testnet';
		} elseif (true === strpos($json['url'], 'https://smartex.io')) {
			$network = 'livenet';
		}

		$invoice = $this->smartex->getInvoice($json['id'], $network);

		switch ($invoice->getStatus()) {
			case 'paid':
				$order_status_id = $this->setting('paid_status');
				$order_message = $this->language->get('text_progress_paid');
				break;
			case 'confirmed':
				$order_status_id = $this->setting('confirmed_status');
				$order_message = $this->language->get('text_progress_confirmed');
				break;
			case 'complete':
				$order_status_id = $this->setting('complete_status');
				$order_message = $this->language->get('text_progress_complete');
				break;
			default:
				$this->response->redirect($this->url->link('checkout/checkout'));
				return;
		}

		// Progress the order status
		$this->model_checkout_order->addOrderHistory($invoice->getOrderId(), $order_status_id);
	}
}
