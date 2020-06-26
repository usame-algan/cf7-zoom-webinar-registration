# Contact Form 7 Zoom Webinar Registration

Allow registrations for you Zoom Webinar through Wordpress Contact Form 7

## Requirements

- Zoom Account that can create webinars
- [JWT Application](https://marketplace.zoom.us/develop/create) for your Zoom Account

## Installation

1. Download the repository .zip file: [cf7-zoom-webinar-registration.zip](#)
2. Upload the file to your wordpress installation
3. Activate the plugin

## Setup

1. After plugin activation you can save your `API Key` and `API Secret` in the Settings Page
2. The plugin looks for field names prefixed with `cf7zwr-`. The rest of the field name should be according to the [Zoom API reference](https://marketplace.zoom.us/docs/api-reference/zoom-api/webinars/webinarregistrantcreate).
3. The webinar ID needs to be saved with the form under `Additional Settings`. The Syntax is `webinar_id: {your webinar id without whitespaces}`

## Bugs

If you find any Bugs please feel free to [open an issue here](#)!

## Credits

This plugin was inspired by [Gravity Forms Zoom Webinar Registration](https://github.com/michaelbourne/gravity-forms-zoom-webinar-registration)