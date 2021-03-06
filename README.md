# UAMS 2020 Theme

## Installation Instructions

1. Upload the Bootstrap for Genesis theme folder via FTP to your wp-content/themes/ directory. (The Genesis parent theme needs to be in the wp-content/themes/ directory as well.)
2. Go to your WordPress dashboard and select Appearance.
3. Activate the Bootstrap for Genesis theme.
4. Inside your WordPress dashboard, go to Genesis > Theme Settings and configure them to your liking.

## Building from Source

1. Install [Git](https://git-scm.com/).
2. Clone the repository to your local machine.
3. Install [Node](https://nodejs.org/en/).
4. Install [Gulp](https://gulpjs.com/) globally.
5. Install [Yarn](https://yarnpkg.com/en/docs/install)
6. Run `yarn install` to install dependencies through terminal/CLI program.
7. Replace site url in line `45` of `gulpfile.js` to your local development URL(e.g. http://bootstrap.test).
8. Run `gulp` through your favorite CLI program.

## Features

1. Bootstrap 4
2. Bootstrap components
	* Comment Form
	* Search Form
	* Jumbotron
	* Navbar
3. Sass
4. Gulp
5. Footer Widgets(modified to add bootstrap column classes based on the number of widget areas)
6. Additional Widget Areas
	* Home Featured(jumbotron)
7. TGM Plugin Activation Support
8. Multi-level dropdown menus using [SmartMenus](http://www.smartmenus.org/) Bootstrap Addon

## Credits

Based on the [Bootstrap for Genesis](https://github.com/webdevsuperfast/bootstrap-for-genesis) theme by Rotsen Mark Acob

Without these projects, this theme wouldn't be where it is today.

* [Genesis Framework](http://my.studiopress.com/themes/genesis/)
* [Bootstrap](http://getbootstrap.com)
* [Sass](http://sass-lang.com/)
* [Gulp](http://gulpjs.com/)
* [TGM Plugin Activation](http://tgmpluginactivation.com/)
* [WP Bootstrap Navwalker](https://github.com/twittem/wp-bootstrap-navwalker)
* [Bootstrap Genesis](https://github.com/salcode/bootstrap-genesis)
* [Bones for Genesis 2.0 with Bootstrap integration](https://github.com/jer0dh/bones-for-genesis-2-0-bootstrap)
* [SmartMenus Bootstrap Addon](http://www.smartmenus.org/)