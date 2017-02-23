# Laravel-Ip2Location
Use the free Ip2Location LITE database within Laravel

# Migrations 

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

