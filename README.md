# WSU Embeds

Extends the WSUWP Platform to include support for various embeds.

## Supported Shortcodes

* `[qualtrics]` to embed Qualtrics surveys.
	* `url` - **Required.** The full URL to your Qualtrics survey.
	* `width` - Defaults to `100%`. Accepts the same values as the CSS property `width`.
	* `height` - Defaults to `100%`. Accepts the same values as the CSS propety `height`.
	* `min_height` - Defaults to `400px`. Accepts the same values as the CSS property `min-height`.
* `[qualtrics_multi]` to redirect a user randomly to one of multiple surveys.
	* `url1`, `url2`, `url3`, `url4`, `url5` can all be used to specify a different URL for the survey.