newsletterBundle
================

Newsletter bundle for Symfony 2.

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require usn/newsletter-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Usn\NewsletterBundle\UsnNewsletterBundle(),
        );

        // ...
    }

    // ...
}
```

Step 3: Update your database schema 
-----------------------------------

```bash
php app/console doctrine:schema:update --force
```

Step 4: Include javascript
-------------------------

This bundle requires jquery. Include it in the layout template.

Run the command to install js assets

```bash
$ php app/console assets:install --symlink
```

Include the main.js file as below.

```twig
  <script src="{{ asset('bundles/usnnewsletter/js/main.js') }}"></script>
```

Step 5: update the routing file
-------------------------------

```
#app/config/routing.yml

usn_newsletter:
    resource: "@UsnNewsletterBundle/Resources/config/routing.yml"
```


How to include the newletter form into a template.
==================================================

```twig
{{render(controller('UsnNewsletterBundle:Default:subscribe'))}}
```
