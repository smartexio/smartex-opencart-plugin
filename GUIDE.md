# Using Smartex for OpenCart
## Prerequisites
Last Cart Version Tested: 2.0.1.1

You must have a Smartex merchant account to use this library.  It's free to [sign-up for a Smartex merchant account](https://smartex.io).

## Getting Started
Go to the [latest release](https://github.com/smartexio/smartex-opencart-plugin/releases/latest) and download the file called `smartex-opencart-plugin.zip`


## Install
### Via Extension Installer
In your OpenCart store's administration section, go to Extensions > Extenstion Installer
Upload `smartex-opencart-plugin.zip` *Note: you have to setup the FTP settings in the Store settings to use the Extension Installer
Click 'Continue'

### Via FTP
Upload all files in the `upload` directory in `smartex-opencart-plugin.zip` to your OpenCart store's installation directory.

## Setup
### Install the Payment Extension
Go to Extensions > Payments.
Find the Smartex payment extension and click the install button.  The page will refresh, you'll see a success message, and the install button will turn into a red uninstall button.
Click on the edit button.  You are now at the Smartex plugin's configuration screen.

### Connect to Smartex
For live transactions, just press the Connect to Smartex button.  For test transactions, press the drop down button attached to the Connect to Smartex button and select testnet.
You will be redirected to your Smartex merchant account and asked to approve a token which connects your store to Smartex's API.
Upon pressing Approve, you will be redirected to Smartex plugin's configuration screen.

Configure the settings that work best for you.  Each setting has a tooltip that can help explain what it does.
Set the status setting to enabled and click save at the top right of the page.

You're done!
