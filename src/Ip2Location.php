<?php
namespace Ip2Location;

use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

class Ip2Location
{


    /**
     * @return string
     * Returns direct location of stored CSV
     */
    public static function csvLocation()
    {
        return database_path() . '/ip2location/ip2location.csv';
    }

    /**
     * Refreshes database with current file stored in csvLocation
     */
    public static function refreshDatabase()
    {
        DB::transaction(function () {
            DB::statement('DELETE FROM ip2location');
            $csv = \League\Csv\Reader::createFromPath(Ip2Location::csvLocation());
            $results = $csv->fetch();
            foreach ($results as $row) {
                if (isset($row[0])) $data['ip_from'] = $row[0];
                if (isset($row[1])) $data['ip_to'] = $row[1];
                if (isset($row[2])) $data['country_code'] = $row[2];
                if (isset($row[3])) $data['country_name'] = $row[3];
                if (isset($row[4])) $data['region_name'] = $row[4];
                if (isset($row[5])) $data['city_name'] = $row[5];
                if (isset($row[6])) $data['latitude'] = $row[6];
                if (isset($row[7])) $data['longitude'] = $row[7];
                if (isset($row[8])) $data['zip_code'] = $row[8];
                if (isset($row[9])) $data['time_zone'] = $row[9];
                DB::table('ip2location')->insert($data);
            }
        });
    }

    /**
     * @return bool
     */
    public static function getClientsLocation()
    {
        if (!$_SERVER['REMOTE_ADDR']) {
            //Clients IP address could not be found
            return false;
        }
        $sql = 'SELECT * FROM ip2location WHERE ip_to >= INET_ATON(:ip_address) order by ip_to limit 1';
        $countryName = DB::select($sql, array('ip_address' => $_SERVER['REMOTE_ADDR']));
        if (!isset($countryName[0])) {
            return false;
        }
        return $countryName[0];
    }


    /**
     * @param bool $ip_address
     * @return mixed
     * @throws Exception
     */
    public static function lookUpIpLocation($ip_address = false)
    {
        if (!$ip_address) {
            throw new Exception('Ip Address has not been provided');
        }
        $sql = 'SELECT * FROM ip2location WHERE ip_to >= INET_ATON(:ip_address) order by ip_to limit 1';
        $countryName = DB::select($sql, array('ip_address' => $ip_address));
        if (!isset($countryName[0])) {
            return false;
        }
        return $countryName[0];
    }

}