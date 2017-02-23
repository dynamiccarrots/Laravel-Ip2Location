# Laravel-Ip2Location

Ip2Location provides a free non-intrusive database to help you identify your visitors geographical location.

The data you receive from a users IP Address will depend on what database size you download from Ip2Location. The largest of these consists of Country, Region, City, Latitude, Longitude, Zipcode and Timezone. 

Only download the database that you need, the large databases can have over 4 million rows. The smallest one has about 160K rows. 

As this package uses the LITE/free version of Ip2Location you are not guaranteed 100% accuracy. 

#Installation

### Download Ip2Location DB11.LITE

Visit this page and download one of the DB files, these range from DB1 to DB11. http://lite.ip2location.com/

You will need to create a folder in the database directory called "ip2location" and store the CSV in here with the name "ip2location.csv".

You should have the following structure.
```
    ├── database/
    │   ├── ip2location/
    │   │   ├── ip2location.csv

```

### Migrations 
Create a new migration file in laravel by running the following command.

```
# php artisan make:migration create_ip2location_table
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
          $table->string('country_name', 64)->nullable();
          $table->string('region_name', 128)->nullable();
          $table->string('city_name', 128)->nullable();
          $table->double('latitude')->nullable();
          $table->double('longitude')->nullable();
          $table->string('zip_code', 30)->nullable();
          $table->string('time_zone', 8)->nullable();
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
Run the migration.

```
# php artisan migration
```

### Create update command 

Due to the fact IP address ranges get moved around from ISP to ISP all the time. We need to update our database on a monthly basis if you want to stay up to date.

We need to have a command that will allow us to change the ip2location.csv file and use its data to refresh our database.

Run the following command.
```
# php artisan make:command RefreshIp2Location
```

This will create a new file in app/Console/Commands called RefreshIp2Location.php.

Replace the code in this new file with this.
```
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ip2Location\Ip2Location;

class RefreshIp2Location extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ip2location:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the Ip2location table with file /database/ip2location/DB11.csv';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        Ip2Location::refreshDatabase();
        return;
    }
}

```

Register the new command in /app/Console/Kernal.php by adding RefreshIp2Location::class to the $commands array, this will allow us to run this from the CLI.
```php
    protected $commands = [
        RefreshIp2Location::class
    ];
```

### Populate the database

Now we have a database set up with the correct table and the csv file stored in the right directory we can run the command to populate the database with the ip2location data.

Run this command.
```
# php artisan ip2location:refresh
```


# Use Examples

### Return the Country Code of a IP address

Provide your own IP Address and Ip2Location will convert this to a Country Code.

```php
echo Ip2Location::lookUpIpLocation('8.8.8.8')->country_code
```


### Return the Country Code client

Return the Country Code of the client

```php
echo Ip2Location::getClientsLocation()->country_code
```
