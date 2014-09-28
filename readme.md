Nette srigi-sandbox
===================

Sandbox is a pre-packaged and pre-configured Nette Framework application
that you can use as the skeleton for your new applications. This is optionated fork of original [nette-sandbox](https://github.com/nette/sandbox).

To get better starting point for my Nette Framework projects, I modified original sandbox to fit my style. Most notable differences are:

- removed Adminer

  I use separate installation of Adminer as local virtualhost.

- cleanup of uneeded files (`web.config`, `print.css` )

  Does anybody care about IIS webserver? Printing webpages?

- using 2 spaces for indentation in non `.php` files (`.js`, `.json` or `.latte`). There is a `editorconfig.json` file to help to follow this code-style.

  Two spaces established as general standard in Javascript world. I extended that idea to `.json` & `.html` (`.latte`) files.

- removed `App\Presenters`, `App\Model` namespaces (everything is in `App` namespace)

  Since there is a subword `Presenter` in `App\SomePresenter`, I don't see any sense to have namespace for presenters. My `UserPresenter` will not definitely have name collision with my `UserModel` or `UserForm` since there is that subword `Presenter`, `Model`, `Form` in the name of my classes.

- renamed `HomepagePresenter` to `DefaultPresenter`

  Default entry point in sandbox should have more general name.

- more configuration directives by default

  Example of configuring of presenters.

- more complete `.gitignore` file

- lots of code cleanup

  Removed obvious comments from code, better `use` (namespace) conventions in code, moved CSS styles to `.css` files.

- layout system using `@wrapper.latte`

  Using Nette's template-inheritance we can define layout for each presenter. There is nice example - `DefaultPresenter` use 2-columns layout, `SignPresenter` use 1-colum layout.

- rewrite of signup form template to use fully manual rendering

  also moved this template to more suitable place

- Zurb Foundation CSS framework

  Highly-optionated, but don't want to setup this all-the-time

- [gulpjs](http://gulpjs.com) dev-stack

  Stack for `SASS` stylesheets , two main processes - *developement* (with livereload) & *build*.


Installing
----------

The best way to install `srigi-sandbox` is by using [Composer](https://getcomposer.org/). If you don't have Composer yet, download
it following [the instructions](http://doc.nette.org/composer). Then use command:

    composer create-project srigi/sandbox myapp
    cd myapp

Make directories `temp` and `log` writable. Navigate your browser
to the `www` directory and you will see a welcome page. PHP 5.4 allows
you run `php -S localhost:8888 -t www` to start the web server and
then visit `http://localhost:8888` in your browser.

It is CRITICAL that whole `app`, `log` and `temp` directories are NOT accessible
directly via a web browser! See [security warning](http://nette.org/security-warning).

Devstack
--------

`srigi-sandbox` use **gulpjs** as a minimal dev-stack to support you during development. To fully embrace advantages of dev-stack you must install needed tools & packages. Nodejs is main requirement. See [installation instruction](https://github.com/joyent/node/wiki/installing-node.js-via-package-manager) on how to install nodejs on your machine.

Then install dev-stack:

    npm install -g gulp bower
    npm install   (in root of your project)
    bower install

#### Run devstack:

    gulp

Now you can use [livereload](http://feedback.livereload.com/knowledgebase/articles/86242-how-do-i-install-and-use-the-browser-extensions) for automatic refresh of your browser when you change files of your project.

Your `.js` files will be automatically linted, you can see code-style errors on the console.  `// TODO lint php files`

#### Build your project

    gulp build

This command will optimize frontend parts of your project & create `build` directory with builded project.
`// TODO rethink build process not to copy whole app to build folder`

License
-------
- Nette: New BSD License or GPL 2.0 or 3.0 (http://nette.org/license)
- jQuery: MIT License (https://jquery.org/license)
- Adminer: Apache License 2.0 or GPL 2 (http://www.adminer.org)
- Sandbox: The Unlicense (http://unlicense.org)
