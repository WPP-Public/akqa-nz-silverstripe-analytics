# silverstripe-analytics

[![CI](https://github.com/WPP-Public/akqa-nz-silverstripe-analytics/actions/workflows/ci.yml/badge.svg)](https://github.com/WPP-Public/akqa-nz-silverstripe-analytics/actions/workflows/ci.yml)

Heyday's standard analytics module to be used across all sites requiring
analytics

## Installation

```sh
composer require heyday/silverstripe-analytics
```

## Setup

First, add details to SilverStripe configuration; for instance by creating an
_analytics.yml_ file with the following details:

```yml
Page:
  extensions:
    - Heyday\Analytics\AnalyticsExtension
SilverStripe\Core\Injector\Injector:
  AnalyticsService:
    class: Heyday\Analytics\GoogleTagManagerProvider
    constructor:
      0: 'GTM-XXXXX'
```

For Google Tag Manager _AnalyticsID_ will be something like:

```yml
GTM-XXXXX
```

For Google Analytics _AnalyticsID_ will be something like:

```yml
UA-XXXXXXXXX-X
```

Analytics for the site can now be included in a SilverStripe template simply
with the following code in the relevant .ss file:

```js
{$AnalyticsCode.RAW}
```

### Google Tag Manager Code

Google Tag Manager code should be set as high in the `<head>` of the page as
possible:

```html
<head>
	<title>Page Title</title>
	{$AnalyticsCode.RAW}
```

However, if you do have meta tags that set the charset or http-equiv attributes,
you'll want them to be at the very top of head, since browsers expect them to be
among the first characters of an HTML document.

Google Tag Manager no script tag is now separate from the Tag Manager container.
To use it in your template, just call the variable `TagManagerNoScript`

```html
</head>
<body>
	{$TagManagerNoScript}
...
```

### Google Analytics Code

Google Analytics Code code should be set just after the `<body>` tag:

```html
</head>
<body>
	{$AnalyticsCode}
...
```

### Using Google Search Console verification with Google Tag Manager

If you have a **Google Tag Manager** account, you can verify ownership of a site
using your **Google Tag Manager** container snippet code.

To verify ownership using **Google Tag Manager**, choose **Google Tag Manager**
in the verification details page for your site, and follow the instructions
shown.

When copying Tag Manager code:

- You must have "View, Edit, and Manage" account level permissions in **Google
  Tag Manager**.
- Place the Tag Manager code immediately after the opening <body> tag of your
  page. If you do not, verification will fail.
- You cannot insert a data layer (**or anything other than HTML comments**)
  between the <body> tag and the tag manager code. If you do, verification will
  fail.
- Use the code exactly as provided; do no modify it. If you modify it,
  **verification will fail**.

For more information, check out this page:
https://support.google.com/webmasters/answer/35179
