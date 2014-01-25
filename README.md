# BitZero

BitZero is a modification of Dropplets blogging platform. Uses the same concept, but has implementations that do not exist in Dropplets platform.

Some modifications:

- Menu of categories;
- Static Pages;
- Language support;
- Extended support for avatars,

and much more.

Some implementations made ​​aimed at improving the performance of the platform, other ease of use for users.
Features found may have been implemented in Dropplets platform or not. For further reference, please visit the platform, so you can compare and choose the most ideal for your use case.

**BitZero GitHub**: https://github.com/MBarbosaEng/BitZero or URL: **[http://bit.ly/BitZero](http://bit.ly/BitZero)**

**BitZero Demo**: **[http://bit.ly/BitZeroDemo](http://bit.ly/BitZeroDemo)**

**Dropplets GitHub**: https://github.com/circa75/dropplets or **[http://bit.ly/Dropplets](http://bit.ly/Dropplets)**


## Dropplets

Dropplets is a minimalist **Markdown** blogging platform focused on delivering just what you need in a blogging solution, but absolutely nothing you don't. When it comes to basic blogging, all you really want to do is write & publish which is where Dropplets excels. It's databaseless, so it installs on any server in just about 30 seconds. 

## What's Markdown?
Markdown is a text formatting syntax inspired on plain text email. It is extremely simple, memorizable and visually lightweight on artifacts so as not to hinder reading.

> The idea is that a Markdown-formatted document should be publishable as-is, as plain text, without looking like it's been marked up with tags or formatting instructions.

## Getting Started
- [Installation](#installation)
- [Posts](#posts)
- [Dropplets License](#dropplets-license)
- [BitZero Modifications](#bitzero-modifications)
- [Dropplets License](#dropplets-license)
- [BitZero License](#bitzero-license)

## <a name="installation"></a>Installation
The blogging platform is compatible with most server configurations and can be typically installed in under a minute using the few step-by-step instructions below:

1. Download the latest **release** and then extract the downloaded zip file.
3. Upload the entire contents of the extracted zip file to your web server wherever you want to be installed. 
4. Pull up your site in any modern web browser (e.g. if you uploaded Dropplets to **yoursite.com**, load **yoursite.com** in your browser to finish the installation), then create and confirm your password.

## <a name="posts"></a>Posts
The BitZero has different resources on a few points that may have not yet been deployed in Dropplets.

To better understand the differences, read the static pages of the initial installation:

- About BitZero
- About Dropplets
- BitZero Last Modifications
- BitZero Screenshots
- BitZero Versions

and this initial page:

- Writing Post with Markdown

Or visit the following repositories:

**BitZero GitHub**: https://github.com/MBarbosaEng/BitZero or **[http://bit.ly/BitZero](http://bit.ly/BitZero)**

**Dropplets GitHub**: https://github.com/circa75/dropplets or **[http://bit.ly/Dropplets](http://bit.ly/Dropplets)**

## <a name="demo"></a>Demos

BitZero Demos: **[http://bit.ly/BitZeroDemo](http://bit.ly/BitZeroDemo)**

---

## <a name="bitzero-modifications"></a>BitZero Modifications

This is a version of the original system Dropplets.
Some features may not yet be present in the original version, or are not yet deployed.
Always refer to the original design.

If you have any feature suggestion, please contact us.

- [Version 1.04](#Version-1.05)
- [Version 1.04](#Version-1.04)
- [Version 1.03](#Version-1.03)
- [Version 1.02](#Version-1.02)
- [Version 1.01](#Version-1.01)

### <a name="Version-1.05"></a>Version 1.05 - Published: Jan 25, 2014

- Improving the way to show categories 
- Separated into individual files of the code index.php file for easier maintenance. 
- Page demos versioned.

### <a name="Version-1.04"></a>Version 1.04 - Published: Jan 25, 2014

This version has been deployed all existing Pull Requests until the present time. 

This allows for better compatibility with Dropplets. 

Created new styles for checkbox. The style developed by [pfinkbeiner](https://github.com/pfinkbeiner) is not compatible with all browsers.

- [trims and lowers the status string](https://github.com/Circa75/dropplets/pull/315) Created by [kemmeter](https://github.com/kemmeter)
- [Added date_create/ISO compliant timestamp information to README](https://github.com/circa75/dropplets/pull/310) Created by [bicien](https://github.com/bcicen)
- [Add new feature for scheduled publishing.](https://github.com/circa75/dropplets/pull/300) Created by [pfinkbeiner](https://github.com/pfinkbeiner)
- [Add multiple categories support.](https://github.com/circa75/dropplets/pull/274) Created by [zessx](https://github.com/zessx)
- [Added the ability to hide the theme market for speed.](https://github.com/circa75/dropplets/pull/271) Created by [lrobert](https://github.com/lrobert)
- [Fix bug where if the site is called with index.php in the URL](https://github.com/circa75/dropplets/pull/225) Created by [rubenvarela](https://github.com/rubenvarela)



### <a name="Version-1.03"></a>Version 1.03 - Published: Jan 12, 2014

From this version (1.03) the most profound changes will be separated from the project Dropplets.

- Static pages.
- Tags.
- Tag Cloud.
- When Twitter's author is informed on the post, this will be used as an avatar. If not entered, the search will be done automatically by the avatar.
- When Twitter author is given, will be inserted link to Twitter's website.
- Several "flavors" of Markdown (PHP & JavaScript).
- Added the ability to hide the theme market for speed.
- Added the ability to load scripts locally for more speed.

### <a name="Version-1.02"></a>Version 1.02 - Published: Jan 03, 2014

- Choose initial email in the initial configuration.
- Choose the default avatar.
- Avatar Twitter not set by default.
- Avatar from Gravatar by default.
- Avatar of Tumblr.
- Share on Tumblr.
- Comment on twitter (the site, not the article).
- Automatic Menu categories.
- Modernizr.
- Font Awesome.
- Choose the name for Copyright.
- Copyright in the bottom.
- Contact by email.
- Possibility to use PNG image with article icon (see above - Writing Posts).
- Allows you to change the default Favicon.
- Fonts, Scripts and Css moved to local site (if you do not find uses standard).
- Allows you to configure how the pagination will be taken.
- Allow Customization

### Version 1.01 - Published: Dec 23, 2013

- Templates and Interface enables language selection
- Language Pack can be created from a PO file.
- Available languages:

> 	en_US and pt_BR

- Languages already preconfigured:

> 	da_DK, fr_FR, de_DE, el_GR, he_IL, it_IT, ja_JP, ko_KR, ru_RU, nl_NL, zh_CN, zh_TW

- Sharing with Facebook and Google+
- Picture of the post is selected based on social networks, following the order: Twitter, Facebook and Google+
- Data encrypted in the configuration file
- Protected Directories
- Implemented Twetter API v1.1 for future resources

## How to Translate:
- Copy the en_US.po file to one of the names below:

> 	da_DK, fr_FR, de_DE, el_GR, he_IL, it_IT, ja_JP, ko_KR, ru_RU, nl_NL, zh_CN, zh_TW

- Use an editor like Poedit language
- Open the file chosen for translation.
- Write the corresponding translation
- Share on GitHub.



---

## <a name="dropplets-license"></a>Dropplets License

Copyright (c) 2013 Circa75 Media, LLC

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


## <a name="bitzero-license"></a>BitZero License

Copyright (c) 2013 M Barbosa (MBarbosaEng), EngBit

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

