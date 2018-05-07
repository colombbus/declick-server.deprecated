Declick-server
==========

** This project is no longer maintained. Use [declick-server](https://github.com/colombbus/declick-server) instead. **

A Symfony2's Framework web interface for [Declick](https://github.com/colombbus/declick-client)

Declick-server adds support for the Declick-client educational program.
It provides a flexible framework for user management that aims to handle
common tasks such as user registration and password retrieval, project management

Features include:

- Users can be stored via Doctrine ORM, MongoDB/CouchDB ODM or Propel
- Registration support, with an optional confirmation per mail
- Password reset support
- Project and Task management
- Aimed to be unit tested

**Note:** This bundle does *not* provide an authentication system but can
provide the user provider for the core [SecurityBundle](http://symfony.com/doc/current/book/security.html).

**Caution:** This bundle is developed in sync with [symfony's repository](https://github.com/symfony/symfony).
For Symfony 2.0.x, you need to use the 1.2.0 release of the bundle (or lower)

Documentation
-------------

The bulk of the documentation is stored in the `Resources/doc/index.md`
file in this bundle:

[Read the Documentation for master](https://github.com/colombbus/declick-server/blob/master/src/Declick/Resources/doc/index.md)

Installation
------------

All the installation instructions are located in the documentation.

Continuous Integration
------------

Declick-server is TestDriven developped with the [Travis](https://travis-ci.org/colombbus/declick-server)
tool.

License
-------

This bundle is under the GPL v3.0 license. See the complete license in the bundle:

    Resources/meta/LICENSE

About
-----

Declick-server is a [Colombbus](http://www.colombbus.org) initiative.
See also the list of [contributors](https://github.com/colombbus/declick-server/contributors).

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/colombbus/declick-server/issues).

When reporting a bug, it may be a good idea to reproduce it in a basic project
built using the [Symfony Standard Edition](https://github.com/symfony/symfony-standard)
to allow developers of the bundle to reproduce the issue by simply cloning it
and following some steps.
