RCHParamFetcherBundle
=====================

Automates parameters fetching and validation from the body of your requests.

Inspired by the [FOSRestBundle ParamFetcher](http://symfony.com/doc/current/bundles/FOSRestBundle/param_fetcher_listener.html) in order to use it out of the bundle.

Installation
------------

#### Download the bundle

```bash
$ composer require rch/param-fetcher-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

#### Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
// app/AppKernel.php

$bundles = array(
    // ...
    new RCH\ParamFetcherBundle\RCHParamFetcherBundle(),
);
```

Usage
-----

```php
<?php

use RCH\ParamFetcherBundle\Controller\Annotations as RCH;
use RCH\ParamFetcherBundle\Request\ParamFetcher;
use Symfony\Component\Validator\Constraints\Email;

class FooController extends Controller
{
    /**
     * @RCH\RequestParam(name="email", requirements={@Email}, nullable=true)
     */
    public function barAction(ParamFetcher $paramFetcher)
    {
        return array('email' => $paramFetcher->get('email'));
    }
}
```

This bundle works pretty much like the FOSRestBundle ParamFetcher.  
For a more advanced usage, [look at its documentation](http://symfony.com/doc/current/bundles/FOSRestBundle/param_fetcher_listener.html).

Contributing
------------

See the contribution guidelines in the [CONTRIBUTING.md](CONTRIBUTING.md) distributed file.

__Missing:__

- Setting up bundle configuration and defaults:

```yml
rch_param_fetcher:
    exception_listener :
        enabled: [true|false]
        use_request_format: [true|false]
        format: [json|xml|html]
```

- Creating a `ParamValidator` & Move `ParamFetcher::handleRequirements()` into.

- Creating interfaces `ParamFetcherInterface`, `ParamReaderInterface` & `ParamBagInterface`.

- Using a `ConstraintViolationList` from validation errors.

License
-------

The code is released under the GPL-3.0 license.

For the whole copyright, see the [LICENSE](LICENSE) file.
