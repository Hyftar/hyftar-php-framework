# Hyftar's PHP framework

Hyftar's PHP framework is a bare bone PHP framework that was built in order to
simplify website building in PHP by using the best practices.

It implements the MVC design pattern and can optionnaly use Twig for
templating.

## Usage manual

### Installation

1.  Download the framework either directly or by cloning the repo
2.  Run `composer install` to install the project dependencies
3.  Configure your web server to have this project's `public` folder as
    its web root.
4.  Configure your database configuration data in `App/Config.php`
5.  Create controllers, views and routes.

For a quick test server, you can run the command `php -S localhost:3030 -t public/`

### Routing

The Router translates requests into controllers and actions.
Two sample controllers route are included and both have an action already
routed.

Routes are added with the add method. You can add fixed URL routes, and specify
the controller and action, like this:

```php
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('tomatoes/', ['controller' => 'StaticFiles', 'action' => 'tomatoes']);
```

You can also write "catch all" routes which will find the controller and
action name from the URI.

```php
$router->add('{controller}/{action}');
```

In addition to the controller and action, you can specify any parameter you
like within curly braces, and also specify a custom regular expression for that
parameter:

```php
$router->add('{controller}/{id:\d+}/{action}');
```

Your custom parameters must respect this Regex Pattern:
`/\{([a-z]+):([^\}]+)\}/`

The first group being the parameter name and second being the pattern that
will match the value.

You can also specify a namespace for the controller:

```php
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
```

Unless otherwise specified, every route will respond only to the `GET` HTTP
method. You can specify the request method by passing a string as the 3rd
parameter:

```php
$router->add('login', ['controller' => 'login', 'action' => 'create'], 'POST');
```

Every `GET` route also supports `HEAD` requests which will return only the
header of the response along with the `Content-Length`.

### Controllers

Controllers respond to client actions (clicking on a link, submitting a form
etc.). Controllers are classes that extend the `Core\Controller` class.

Controllers are stored in the `App/Controllers` folder. Two sample controllers
are included. Controller classes need to be in the `App/Controllers` namespace.
You can add subdirectories to organise your controllers, so when adding a route
for these controllers you need to specify the namespace (see the routing
section above).

Controller classes contain methods that are the actions. To create an action,
add the `Action` suffix to the method name. The sample home controller in `App/
Controllers/Home.php` has a sample index action.

You can access route parameters (for example the `id` parameter shown in the
route examples above) in actions via the `$this->route_params` property.

### Views

Views are used to display information (normally using HTML). View
files go in the App/Views folder. Views can be in one of two formats: standard
PHP, but with just enough PHP to show the data. No database access or anything
like that should occur in a view file. You can render a standard PHP view in a
controller, optionally specifying the content-type and passing in variables,
like this:

```php
View::render(
  'Home/index.php',
  'text/html',
  [
    'id' => '2',
    'colours' => [
      'leaf' => '#318822',
      'core' => '#BB2C2C'
    ],
    'weight' => [
      'unit' => 'ounces',
      'value' => '5'
    ]
  ]
);
```

The second format uses the Twig templating engine. Using Twig
allows you to have simpler, safer templates that can take advantage
of things like template inheritance. You can render a Twig template

like this:
```php
View::renderTemplate(
  'Home/index.php',
  'text/html',
  [
    'id'    => '2',
    'colours' => [
      'leaf' => '#318822',
      'core' => '#BB2C2C'
    ],
    'weight' => [
      'unit' => 'ounces',
      'value' => '5'
    ]
  ]
);
```



### Models

Models are used to get and store data in your application. They know nothing
about how this data is to be presented in the views. Models extend the
`Core\Model` class and use `PDO` to access the database. They're stored in
the`App/Models` folder. A sample tomato model class is included in `App/Models/
Tomato.php`. You can get the `PDO` database connection instance like this:

```php
$db = static::getDB();
```

### Errors

If the `SHOW_ERRORS` configuration setting is set to
true, full error detail will be shown in the
browser if an error or exception occurs. If it's
set to false, a generic message will be shown using
the `App/Views/404.html.twig` or `App/Views/500.html.twig` views,
depending on nature of the error.
