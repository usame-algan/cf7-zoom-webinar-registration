# Contact Form 7 Zoom Webinar Registration

[![License: GPLv3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/ualgan)

Allow registrations for your Zoom Webinar through Wordpress Contact Form 7

## Requirements

- Zoom Account that can create webinars
- [JWT Application](https://marketplace.zoom.us/develop/create) for your Zoom Account

## Installation

1. Download the [latest release](https://github.com/usame-algan/cf7-zoom-webinar-registration/releases/latest)
2. Upload the file to your wordpress installation
3. Activate the plugin

## Setup

1. After plugin activation you can save your `API Key` and `API Secret` in the Settings Page
2. The plugin looks for field names prefixed with `cf7zwr-`. The rest of the field name should be according to the [Zoom API reference](https://marketplace.zoom.us/docs/api-reference/zoom-api/webinars/webinarregistrantcreate). For example if you want to save the first name of a registrant the field should be named `cf7zwr-first_name`
3. The webinar ID can be saved within the form settings inside the `Zoom` panel

## License

This plugin is licensed under the GPL v3. See `license.txt` for more information.

## Bugs

If you find any Bugs please feel free to [open an issue here](https://github.com/usame-algan/cf7-zoom-webinar-registration/issues)!

## Changelog

#### [1.0.3] - 2020-06-28
- Now displays selected questions from Zoom once the webinar id has been set

#### [1.0.2] - 2020-06-28
- Added custom field for the webinar id

## Credits

This plugin was inspired by [Gravity Forms Zoom Webinar Registration](https://github.com/michaelbourne/gravity-forms-zoom-webinar-registration)
