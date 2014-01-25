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
- [BitZero License](#bitzero-license)

## <a name="installation"></a>Installation
Dropplets is compatible with most server configurations and can be typically installed in under a minute using the few step-by-step instructions below:

1. Download the latest **release** and then extract the downloaded zip file.
3. Upload the entire contents of the extracted zip file to your web server wherever you want Dropplets to be installed. 
4. Pull up your site in any modern web browser (e.g. if you uploaded Dropplets to **yoursite.com**, load **yoursite.com** in your browser to finish the installation), then create and confirm your password.

## <a name="posts"></a>Posts
The BitZero has different resources on a few points that may have not yet been deployed in Dropplets.

To better understand the differences, read the initial installation of the system static pages:

- BitZero
- Dropplets

Or visit the following repositories:

**BitZero GitHub**: https://github.com/MBarbosaEng/BitZero or URL: **[http://bit.ly/BitZero](http://bit.ly/BitZero)**

**Dropplets GitHub**: https://github.com/circa75/dropplets or **[http://bit.ly/Dropplets](http://bit.ly/Dropplets)**


## <a name="dropplets-license"></a>Dropplets License
Copyright (c) 2013 Circa75 Media, LLC

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

---

## <a name="bitzero-modifications"></a>BitZero Modifications

This is a version of the original system Dropplets.
Some features may not yet be present in the original version, or are not yet deployed.
Always refer to the original design.

From this version (1.03) the most profound changes will be separated from the project Dropplets.

If you have any feature suggestion, please contact us.

- [Version 1.03](#Version-1.03)
- [Version 1.02](#Version-1.02)
- [Version 1.01](#Version-1.01)
- [BitZero License](#bitzero-license)

### Version 1.03 - Published: Jan 12, 2014

#### Fixes

- Corrected the minimum amount of post and category.
- Added trim to the name used for the search Avatar.
- Locale date format for old PHP.
- Data recovery on remote servers using the cURL module.

#### New Features

- Static pages.
- Tags.
- Tag Cloud.
- When Twitter's author is informed on the post, this will be used as an avatar. If not entered, the search will be done automatically by the avatar.
- When Twitter author is given, will be inserted link to Twitter's website.
- Several "flavors" of Markdown (PHP & JavaScript).
- Added the ability to hide the theme market for speed.
- Added the ability to load scripts locally for more speed.

#### Changes

- Menu with static pages allowed.
- Template simpleI8N - 404.php style improvements.
- Generate the Twitter Card only if exist on config.

### Version 1.02 - Published: Jan 03, 2014

#### Fixes

- Url to retrieve password.
- Url to page 404.
- Blog Url configuration.
- Tests of existence of variables.

#### New Features

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

#### Customization

- Favicon.png
> Put in the site root (./favicon.png)

- Font Merriweather and Source Sans Pro
> First searches for the file ./src/fonts/fonts.css

- Loading.gif
> First searches for the file ./src/imgs/loading.gif

- Modernizr.js
> Located in ./src/js/modernizr.custom.js

- Jquery-1.10.2.min.js
> First searches for the file ./src/js/

- Menu.css
> If you want to customize the menu, copy the file to the directory of your template and change as desired.



### Version 1.01 - Published: Dec 23, 2013

#### What is new?
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

#### How to Translate:
- Copy the en_US.po file to one of the names below:

> 	da_DK, fr_FR, de_DE, el_GR, he_IL, it_IT, ja_JP, ko_KR, ru_RU, nl_NL, zh_CN, zh_TW

- Use an editor like Poedit language
- Open the file chosen for translation.
- Write the corresponding translation
- Share on GitHub.

## Increasing the speed of the system

To increase speed, you can put scripts needed for the system to function in the same hosting provider.
An example of this is Twitter and Tumblr. They use external scripts.
In the case of Twitter and Tumblr, check the existing file in the README.md:

- /bitzero/other_sources/twitter/README.md
- /bitzero/other_sources/tumblr/README.md

Another solution is to disable the loading of templates (show / hide) in the setup menu.

Remember to respect the copyright and rules of each author.

## <a name="bitzero-license"></a>BitZero License
Copyright (c) 2013 M Barbosa (MBarbosaEng), EngBit

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

