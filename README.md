#Transport Mode Detection
This is a prototype for an application that can determine the used transport mode (bike, bus, car, walk, train) from a given gpx-file. Basically it offers two different analyse methods. The first one uses values derived form the gpx-file only (velocity, acceleration, stopprate, ...). The second method uses in addition also gis data like bus stops,train stations, rails and highway. 

More information about how this this works and how it was implemented can be found in here https://github.com/michaelzangerle/thesis (german only).

## Prerequisites

To run this project you need a webserver that supports PHP e.g. Apache and a database that is supported by doctrine e.g. mySQL. Furthermore you need Composer https://getcomposer.org/ to install the php dependencies.

###Requirements

- PHP 5.4 or higher
- Database (e.g. mySQL 14.14)

### Optional

Add a vhost: 

```bash
<VirtualHost *:80>

	DocumentRoot /[PATH_TO_THE_PROJECT]/web
	ServerName tmd.lo
	ServerAlias www.tmd.lo	
	
	ErrorLog ${APACHE_LOG_DIR}/tmd.error.log
	CustomLog ${APACHE_LOG_DIR}/tmd.access.log combined

	<Directory /[PATH_TO_THE_PROJECT]/web>
		AllowOverride All
		Require all granted
		Options Indexes FollowSymLinks
	</Directory>

</VirtualHost>
```

## Installation

Clone the repository
```bash
git clone git@github.com:michaelzangerle/tmd.git
```

Set the correct permissions for the webserver on the folder and install the dependencies. Setting up the configuration parameters will be done in the following questions. You can find the parameters otherwise in ```app/config/parameters.yml```.

```bash
composer install
```
Clear the cache directories and set the correct permissions

__Linux:__
```bash
rm -rf app/cache/*
rm -rf app/logs/*
sudo setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
```

__Mac OSX:__
```bash
rm -rf app/cache/*
rm -rf app/logs/*
mkdir app/data
APACHEUSER=`ps aux | grep -E '[a]pache|[h]ttpd' | grep -v root | head -1 | cut -d\  -f1`
sudo chmod +a "$APACHEUSER allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
```
