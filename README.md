# mailer-demo
A sample demo project for a mailing app listing email subscribers and their fields.

### 1. Requirements

Project requires PHP >= 7.4, as well as ext-curl, ext-pdo and ext-json installed on your system.

### 2. Installation
 - clone the repository
 - run `composer install`
 - copy the provided example config file - `cp .env.example .env` 
 - change the app url and mysql credentials as needed
 - import the provided .sql file - `mysql -u user -p database < maildb.sql` (note that the .sql does not include `CREATE DATABASE` statements)
 - start the built-in PHP server with `php -S localhost:3000`, or create a vhost file in your webserver config pointing to the project directory
 - the API is now accessible at `http://<<APP_URL>>/api`. Check `src/Router.php` to see the available resources
 - if you have PHPUnit installed, you can run the provided tests with `phpunit --bootstrap vendor/autoload.php tests -v`
 - to test the API, you can also use the provided Postman collection
 
### 3. Questions and answers

- _Why there is not a DI container?_

Adding a DI container would be a logical next step. However, I feel that for a sample demo project like this one it's an overkill

- _Why there are environment parameters used all along the application?_

For the same reason above. Replacing calls to `$_ENV` with injecting these environment parameters directly in the class is trivial after adding a DI container.

- _Why the router exposes exception information on a 400 or a 500 error?_

That depends on the server configuration. 400 errors require a meaningful description so that the client can understand what's wrong with the request.
500 errors should not actually expose any information, but in development environment it's helpful to have it. The usual way of dealing with that on a standard
server is to disable `display_errors`, enable `log_errors` and add a PSR-3 logger

- _What's the point of having a database connection pool in a PHP process?_

Actually none. It's here just for demonstrating the pattern. A PHP process will have to wait for the connection result before continuing on,
which means a single connection is more than enough (as long as you do not spawn threads). A reasonable approach is to have an object holding
just one connection open at all times until the request finishes or you're 100% certain you're done with database requests.

- _Is the email checker really going to know if an email address can receive emails?_

Unfortunately, no. What I've gathered so far is that you can only check for:
 - correct format of the email address
 - MX or A records of the host address
 - live server listening on it

As SMTP servers do not usually respond as they should to VRFY commands, the only 100% certain way to know is to actually send an email to that address.

- _Why there are code duplicates existing? Isn't it better to refactor it to a separate method/class/anything else?_

It is, but it depends on the amount of code, and the number of occurrences. In our case, these are small code fragments (~10-15 lines), encountered in 2 places at maximum.
And a good time to refactor these is usually when they are duplicates in 3 places.

- _What's with the weird pagination based on ID? Isn't the standard offset/limit better?_

Again, it depends on the case. The pagination approach used here is called 'seek method', and it's described in more details [here](https://taylorbrazelton.com/posts/2019/03/offset-vs-seek-pagination/).
The main drawback of offset pagination is slower and slower query as the number of records increases.
