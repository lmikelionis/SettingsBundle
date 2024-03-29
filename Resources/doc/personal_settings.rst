==================================================
Enabling and using Personal settings functionality
==================================================


Introduction
------------
    Note: This functionality will require You to login using `Sessionless authentication </Resources/doc/ongr_sessionless_authentication.rst>`_.

While using this functionality, settings can be changed per user, from the settings page and the selected values are stored in a separate cookie.

Usage
-----

Add some settings that are grouped in categories into your ``config.yml``:

.. code-block:: yaml

    parameters:
        profiles: [ default, test1, test2, foo_profile ]

        ongr_settings.settings.categories:
            category_1:
                name: Category 1
                description: cat_desc_1
            category_2:
                name: Category 2

        ongr_settings.settings.settings:
            foo_setting_1:
                name: Foo Setting 1
                category: category_1
                description: 'foo_desc_1'
            foo_setting_2:
                name: Foo Setting 2
                category: category_1
            foo_setting_3:
                name: Foo Setting 3
                category: category_2
                description: 'foo_desc_3'
                cookie: project.cookie.alternative_settings # Setting stored in a separate cookie (this cookie must be configured as service)
..


Settings must have a ``name`` and ``category``. ``description`` is optional but highly recommended.

Categories must have a ``name``. ``description`` is optional.

Settings menu is visible under ``/settings_prefix/settings``. The user must be logged in to see the page.

Settings can be stored in multiple cookie stating ``cookie`` parameter and providing cookie service.
More info on usage can be found in `How to work with cookies documentation <https://github.com/ongr-io/CookiesBundle>`_.

Using Settings UI (Personal settings) you can toggle between their output values in twig.

TWIG
~~~~

User selected values can be queried easily from TWIG like this:

.. code-block:: twig

    {% if ongr_setting_enabled('foo_setting_2') %}
        Text when user is logged in and setting equals to true.
    {% else %}
        Otherwise.
    {% endif %}

..

Also its possible to access these values without logging in, by setting second parameter to ``false``:

.. code-block:: twig
    {% if ongr_setting_enabled('foo_setting_2', false) %}
..

Or using a ``UserSettingsManager`` service:

.. code-block:: php

    $this->userSettingsManager = $container->get('ongr_settings.settings.user_settings_manager');
    $isEnabled = $this->userSettingsManager->getSettingEnabled($settingName);

..

~~~~~~~~~~~~~~~~~~~
Settings change API
~~~~~~~~~~~~~~~~~~~

Settings visibility can be toggled when the user visits specific URL generated for that setting. E. g.:

- `http://example.com/settings_prefix/settings/change/Nqlx9N1QthIaQ9wJz0GNY79LoYeZUbJC6OuNe== <http://example.com/settings_prefix/settings/change/Nqlx9N1QthIaQ9wJz0GNY79LoYeZUbJC6OuNe==>`_

!!! FIX [test THIS]: So it is possible to send such URL to remote user (and if ongr_setting_enabled('foo_setting_2', false) parameter is set to false it is possible to send such url to remote unauthorized user, thus enabling the setting by proxy.

~~~~~~~~~~
More about
~~~~~~~~~~

- `Sessionless authentication usage </Resources/doc/ongr_sessionless_authentication.rst>`_
- `General settings usage </Resources/doc/general_settings.rst>`_
- `Flash bag usage </Resources/doc/flash_bag.rst>`_
- `Environment variables usage </Resources/doc/env_variable.rst>`_