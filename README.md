# Phalcon-Kahlan integration playground
Here we have a simple application written with a [Phalcon](https://www.phalconphp.com/) framework.  
And simple test suite written with [Kahlan](https://github.com/crysalead/kahlan) BDD testing framework.

# Prerequisites
To run this playgound you should have:
* Phalcon
* Composer (installed globally prefered)

# Running
```
mkdir /tmp/phalcon-kahlan
cd /tmp/phalcon-kahlan
git clone git@github.com:m1ome/kalhan_phalcon.git . 
composer install
./vendore/bin/kahlan
```

# Application
This is a basic *REST API* application.
### Routes
	* GET */api/users* - for all users list
	* GET */api/users/{id}* - load specific user by id
	* GET */api/users/search/{name}* - load users by %name% pattern
	* PUT */api/users/{id}* - update specific user information by id
	* DELETE */api/users/{id}* - delete specific user by id

### Directories
	* `/application` - Main application folder
		* `/application/model/` - Models folder
		* `/application/controller` - Controller folder
		* `/application/database` - Database lays here
	* `/spec` - Spec folders for *Kahlan*
		* `/spec/database` - Testing database
		* `/spec/fixtures` - Fixture generator
		* `/spec/helper` - Helper files for suite
		* `/spec/suite` - Whole suite by itself

# Kahlan run
`./vendor/bin/kahlan`