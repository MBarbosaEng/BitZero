#BitZero Versions
- MBarbosaEng
- MBarbosaEng
- 2013/04/28 20:30 GMT
- Static
- published
- bitzero,versions
- BitZero is a modification of Dropplets blogging platform. Uses the same concept, but has implementations that do not exist in Dropplets platform.

Some modifications:

- Menu of categories;
- Static Pages;
- Language support;
- Extended support for avatars,

and much more.

Some implementations made ​​aimed at improving the performance of the platform, other ease of use for users.
Features found may have been implemented in Dropplets platform or not. For further reference, please visit the platform, so you can compare and choose the most ideal for your use case. 

## Links

**BitZero GitHub**: [https://github.com/MBarbosaEng/BitZero](http://bit.ly/BitZero) or URL: **[http://bit.ly/BitZero](http://bit.ly/BitZero)**

**BitZero Demo**: **[http://bit.ly/BitZeroDemo](http://bit.ly/BitZeroDemo)**

## Important notice: 

We must remember that it is not mandatory application of these requests and that many may be obsolete or have already been implemented by the BitZero system itself. 

There are features that are in development and therefore may conflict with a specific request. So they can not be present at the time but likely to be deployed in the future. 

If you have an idea but do not know how to program, share with us so we can develop it for you.

## Versions

- [Version 1.04](https://github.com/MBarbosaEng/BitZero)
- [Version 1.03](#Version-1.03)
- [Version 1.02](#Version-1.02)
- [Version 1.01](#Version-1.01)
- [Dropplets License](#dropplets-license)
- [BitZero License](#bitzero-license)

### <a name="Version-1.04"></a>Version 1.04 - Published: Jan 25, 2014

This version has been deployed all existing Pull Requests until the present time. 

This allows for better compatibility with Dropplets. 

Created new styles for checkbox. The style developed by [pfinkbeiner](https://github.com/pfinkbeiner) is not compatible with all browsers.

#### New Features & Credits

- [trims and lowers the status string](https://github.com/Circa75/dropplets/pull/315) Created by [kemmeter](https://github.com/kemmeter)
- [Added date_create/ISO compliant timestamp information to README](https://github.com/circa75/dropplets/pull/310) Created by [bicien](https://github.com/bcicen)
- [Add new feature for scheduled publishing.](https://github.com/circa75/dropplets/pull/300) Created by [pfinkbeiner](https://github.com/pfinkbeiner)
- [Add multiple categories support.](https://github.com/circa75/dropplets/pull/274) Created by [zessx](https://github.com/zessx)
- [Added the ability to hide the theme market for speed.](https://github.com/circa75/dropplets/pull/271) Created by [lrobert](https://github.com/lrobert)
- [Fix bug where if the site is called with index.php in the URL](https://github.com/circa75/dropplets/pull/225) Created by [rubenvarela](https://github.com/rubenvarela)


### <a name="Version-1.03"></a>Version 1.03 - Published: Jan 12, 2014

From this version (1.03) the most profound changes will be separated from the project Dropplets.

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

### <a name="Version-1.02"></a>Version 1.02 - Published: Jan 03, 2014

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
> First searches for the file ./bitzero/other_sources/fonts/fonts.css

- Loading.gif
> First searches for the file ./bitzero/imgs/loading.gif

- Modernizr.js
> Located in ./bitzero/js/modernizr.custom.js

- Jquery-1.10.2.min.js
> First searches for the file ./bitzero/js/jquery-1.10.2.min.js

- Menu.css ()
> Located in ./bitzero/css/menu.css 
> If you want to customize the menu, copy the file to the directory of your template and change as desired.



### <a name="Version-1.01"></a>Version 1.01 - Published: Dec 23, 2013

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

# <a name="dropplets-license"></a>Dropplets License

Copyright (c) 2013 Circa75 Media, LLC

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

# <a name="bitzero-license"></a>BitZero License

Copyright (c) 2013/2014 M Barbosa (MBarbosaEng), EngBit

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

