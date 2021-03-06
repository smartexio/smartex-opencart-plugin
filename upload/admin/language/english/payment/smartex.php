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
 
$_['heading_title']          = 'Smartex';

// Text
$_['text_payment']           = 'Payment';
$_['text_success']           = 'Success: You have modified the Smartex payment module!';
$_['text_edit']              = 'Edit Smartex';
$_['text_changes']           = 'There are unsaved changes.';
$_['text_smartex']            = '<a onclick="window.open(\'https://smartex.io/\');"><img src="view/image/payment/smartex.png" alt="Smartex" title="smartex" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_general']           = 'General';
$_['text_statuses']          = 'Order Statuses';
$_['text_advanced']          = 'Advanced';
$_['text_connect_to_smartex'] = 'Connect to <img src="view/image/payment/smartex_logo.svg" style="height: 36px; margin: -11px -10px -10px -9px">';
$_['text_connected']         = 'Connected to Smartex %s';
$_['text_high']              = 'High';
$_['text_medium']            = 'Medium';
$_['text_low']               = 'Low';
$_['text_livenet']           = 'Livenet';
$_['text_testnet']           = 'Testnet';
$_['text_all_geo_zones']     = 'All Geo Zones';
$_['text_forum']             = 'Forum';
$_['text_smartex_labs']       = '<a href="http://smartexlabs.com/c/plugins/opencart">OpenCart lab on Smartex Labs</a>';
$_['text_send_request']      = 'Send a Support Request';
$_['text_support']           = 'Having problems trying to accept Ethereum with your OpenCart store? Let us help!';
$_['text_yes']               = 'Yes';
$_['text_no']                = 'No';
$_['text_popup_blocked']     = 'Pop-up Blocked';
$_['text_popup']             = 'It appears your browser is blocking Smartex&apos;s pop-up. Click below to continue.';
$_['text_are_you_sure']      = 'Are you sure?';

// Tab
$_['tab_settings']           = 'Settings';
$_['tab_log']                = 'Log';
$_['tab_support']            = 'Support';

// Button
$_['button_disconnect']      = 'Disconnect';
$_['button_send']            = 'Send';
$_['button_continue']        = 'Continue';


// Entry
$_['entry_api_access']       = 'API Access';
$_['entry_risk_speed']       = 'Risk/Speed';
$_['entry_send_buyer_info']  = 'Send Buyer Information';
$_['entry_geo_zone']         = 'Geo Zone';
$_['entry_status']           = 'Status';
$_['entry_sort_order']       = 'Sort Order';
$_['entry_paid_status']      = 'Paid Status';
$_['entry_confirmed_status'] = 'Confirmed Status';
$_['entry_complete_status']  = 'Complete Status';
$_['entry_notify_url']       = 'Notification URL';
$_['entry_return_url']       = 'Return URL';
$_['entry_debug']            = 'Debug Logging';
$_['entry_name']             = 'Name';
$_['entry_email_address']    = 'Email Address';
$_['entry_subject']          = 'Subject';
$_['entry_description']      = 'Description';
$_['entry_send_logs']        = 'Send Smartex Log';
$_['entry_send_server_info'] = 'Send Server Information';

// Help
$_['help_api_access']        = "<strong>Livenet (default):</strong> real Ethereum transactions<br>\n" .
								"<strong>Testnet:</strong> transactions on the Ethereum Testnet. Requires an account at <strong><em>test.smartex.io</em></strong>";
$_['help_risk_speed']        = "<strong>High</strong> speed confirmations typically take <em><strong>5-10 seconds</strong></em>, and can be used for digital goods or low-risk items<br>\n" .
								"<strong>Medium</strong> speed confirmations take about <em><strong>10 minutes</strong></em>, and can be used for most items<br>\n" .
								"<strong>Low</strong> speed confirmations take about <em><strong>1 hour</strong></em>, and should be used for high-value items";
$_['help_paid_status']       = 'A fully paid invoice awaiting confirmation';
$_['help_send_buyer_info']   = 'Buyer information is sent to Smartex so that it can be included on the invoice';
$_['help_confirmed_status']  = 'A confirmed invoice per Risk/Speed settings';
$_['help_complete_status']   = 'An invoice that has been credited to your account';
$_['help_notify_url']        = 'Smartex&#8217;s IPN will post invoice status updates to this URL';
$_['help_return_url']        = 'Smartex will provide a redirect link to the user for this URL upon successful payment of the invoice';
$_['help_debug']             = 'Enabling debug will write sensitive data to a log file. You should always disable unless instructed otherwise';

// Success
$_['success_connected']		  = 'Success: Connected to Smartex %s';
$_['success_disconnect']      = 'Success: Disconnected from Smartex';
$_['success_clear']           = 'Success: The Smartex log has been cleared';
$_['success_support_request'] = 'Success: Your Support Request has been sent. Hang in there!';

// Warning
$_['warning_permission']        = 'Warning: You do not have permission to modify the Smartex payment module.';
$_['warning_unable_to_connect'] = 'Warning: Unable to connect to Smartex. Check the log for more information.';
$_['warning_disconnected']      = 'Warning: You are no longer connected to Smartex. <a href="%s" class="alert-link">Click here to reconnect.</a>';

// Error
$_['error_status']                        = 'You cannot enable this payment method until you have connected your OpenCart store to Smartex. <a href="%s">Connect now!</a>';
$_['error_notify_url']                    = '`Notification URL` needs to be a valid URL';
$_['error_return_url']                    = '`Return URL` needs to be a valid URL';
$_['error_request_name']                  = 'You must enter a name';
$_['error_request_email_address']         = 'You must enter an email address';
$_['error_request_email_address_invalid'] = '`Email Address` needs to be a valid email address';
$_['error_request_subject']               = 'You must enter a subject';
$_['error_request_description']           = 'You must enter a description';

// Log
$_['log_error_install']          = 'The Smartex payment extension was not installed correctly or the files are corrupt. Please reinstall the extension. If this message persists after a reinstall, contact support@smartex.io with this message.';
$_['log_unable_to_connect']      = 'Unable to connect to Smartex';
$_['log_unknown_network']        = 'Unknown network class: %s. Using Livenet instead';
$_['log_no_private_key']         = 'No private key found. Generating key...';
$_['log_private_key_found']      = 'Private key found. Decrypting and unserializing...';
$_['log_private_key_wrong_type'] = 'The private key is of the wrong type.  Received %s';
$_['log_encrypted_key']          = 'Encrypted key: %s';
$_['log_decrypted_key']          = 'Decrypted key: %s';
$_['log_no_public_key']          = 'No public key found. Generating key...';
$_['log_public_key_found']       = 'Public key found. Decrypting and unserializing...';
$_['log_public_key_wrong_type']  = 'The public key is of the wrong type.  Received %s';
$_['log_public_key_regenerate']  = 'Attempting to regenerate public key...';
$_['log_regenerate_success']     = 'Public key was corrupt. Regenerated it from private key';
