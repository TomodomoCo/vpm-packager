# VPM Packager

*VPM Packager* is a WordPress plugin that makes it possible to easily manage a custom Composer repository, sort of like your own version of [Packagist.org](http://packagist.org/).

VPM Packager is loosely inspired by Rarst's [release-belt](https://github.com/Rarst/release-belt), although it doesn't share any code (probably to this plugin's detriment).

## Why?

We built VPM Packager because we wanted a way to install commercial plugins with Composer. Additionally, most of the current solutions are more technical in nature, and we wanted to make it easy for our non-technical staff&mdash;e.g. an overseas virtual assistant&mdash;to manage the Composer repo. This frees our developers up to focus on maintaining sites and testing updates, rather than managing the update infrastructure.

## Installation

[Custom Field Suite](https://github.com/mgibbs189/custom-field-suite) is required for this plugin to work. Please install it before proceeding.

Next, install and activate VPM Packager.

Finally, visit the Custom Field Suite import page, and import the contents of cfs-structure.json. This will set up the interface for managing plugin versions.

## Using VPM Packager

VPM Packager provides a new custom post type called "Packages". Each plugin ("package") you want to support gets its own entry in this post type. For example, you might create an entry for Gravity Forms, an entry for Easy Digital Downloads, etc.

> **Note:** The title of the entry does not matter. Name it anything you want!

Next, fill in the Name and Type information. The Name should be in the form `vendor/package`. The type should be a supported Composer type.

Finally, upload a version of the plugin. You'll need to provide a version number and a ZIP file.

Once you're happy, you can publish the package entry.

Then, in your project's composer.json file, add the custom repository:

````json
	"repositories": [
		{
			"type": "composer",
			"url" : "http://url.for.your.wordpress.install.com"
		}
	],
````

Finally, add any packages:

````json
	"require": {
		"vendor/package": "1.0"
	},
````

## Caveat emptor

Out of the box, VPM Packager provides no authentication mechanism. If you're hosting commercial plugins, you'll probably want to make sure&mdash;at minimum&mdash;you use robots.txt to prevent search engines from indexing your repository. You should probably also keep it out of any public code repos. I'd suggest keeping this plugin on a dedicated WordPress installation at its own domain.

If you're feeling particularly adventurous, you should probably configure HTTP Basic Auth, which is [supported in Composer](http://seld.be/notes/authentication-management-in-composer).

## License

**Copyright (c) 2015 [Van Patten Media Inc.](https://www.vanpattenmedia.com/) All rights reserved.**

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

*   Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
*   Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
*   Neither the name of the organization nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
