# Laravel-Ip2Location

Ip2Location provides a free non-intrusive database to help you identify your victors geographical location.

The data you receive from a users IP Address will consists of Country, Region, City, Latitude, Longitude, Zipcode and Timezone. 

As this package uses the LITE/free version of Ip2Location you are not guaranteed 100% accuracy. 

#Installation

### Download Ip2Location DB11.LITE

Visit this page and download the DB11.LITE CSV file. http://lite.ip2location.com/

You will need to create a folder in the database directory called "ip2location" and store the CSV in here with the name "DB11.csv".

You should have the following structure.
```
    ├── database/
    │   ├── ip2location/
    │   │   ├── DB11.csv

```

### Migrations 
Create a new migration file in laravel by running the following command 

```
    php artisan make:migration create_ip2location_tabel
```

Copy the following migration in to the newly created migration file.

```php
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ip2location', function (Blueprint $table) {
            $table->integer('ip_from')->unsigned();
            $table->integer('ip_to')->unsigned();
            $table->char('country_code', 2);
            $table->string('country_name', 64);
            $table->string('region_name', 128);
            $table->string('city_name', 128);
            $table->double('latitude');
            $table->double('longitude');
            $table->string('zip_code', 30);
            $table->string('time_zone', 8);
            // Setting index
            $table->index('ip_from', 'idx_ip_from');
            $table->index('ip_to', 'idx_ip_to');
            $table->index(['ip_from', 'ip_to'], 'idx_ip_from_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ip2location');
    }
```
Run the migration

```
    php artisan migration
```

### Create update command 

Due to the fact IP address ranges get moved around from ISP to ISP all the time we need to update our database frequently.

We need to have a command that will allow us to update the DB11.csv file and refresh the database. 

Run the following command
```
php artisan make:command RefreshIp2Location
```

This will create a new file in app/Console/Commands called RefreshIp2Location.php

Copy the code below in to this new file.
```
Todo:://

```

Register the new command in /app/Console/Kernal.php by adding RefreshIp2Location::class to the $commands array, this will allow us to run this from the CLI.
```php
    protected $commands = [
        RefreshIp2Location::class
    ];
```

### Populate the database

Now we have a database set up with the correct table and the csv file stored in the right directory we can run the command to populate the database with the ip2location data.




