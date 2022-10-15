sfBugBundle - package for share symfony profiler pages
=====
[!["sfBugBundle logo"](https://sfbug.io/assert/img/logo.png)](https://sfbug.io/)

This package extends the Symfony Profiler component by adding the ability to share profiler pages. You can also manually select / hide certain pages that you want to share

Demonstration
---
Visit our website for more information: https://sfbug.io

---

[!["sfBugBundle Preview"](https://sfbug.io/assert/img/mockup.png)](https://sfbug.io/)



## How to use it / Installation

1. Install the package with composer

```
composer require emrdev/sf-bug-bundle --dev
```
2. Create a router configuration file(if not exists): config/routes/sfbug.yaml
```
when@dev:
    SfBugBundle:
      resource: '@SfBugBundle/Resources/config/routing/routing.yaml'
```
(!!!) Never enable it on production servers as it will lead to major security vulnerabilities in your project.


## Support the project

sfBug.io is and will always be free (like air). It's open source software, just the way we like it. We enter the code and just get pleasure.

[!["Buy Me A Coffee"](https://sfbug.io/assert/img/bmc-coffee.png)](https://www.buymeacoffee.com/emr_dev)
