<?php

namespace App\Listeners;

use App\City;
use App\Events\InsertCitiesEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

/**
 * Class InsertCitiesEventListener
 * @package App\Listeners
 */
class InsertCitiesEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  InsertCitiesEvent  $event
     * @return void
     */
    public function handle(InsertCitiesEvent $event)
    {
        // Get Modification Date
        $file_headers = get_headers("http://download.geonames.org/export/dump/RU.zip", 1);
        $last_modified = date("Y-m-d H:i:s.", strtotime($file_headers['Last-Modified']));

        $last_modification_date = DB::table('cities_info_modified_date')->where('updated_at', '=', $last_modified)->first();

        if (is_null($last_modification_date)) {

            DB::table('cities_info_modified_date')->updateOrInsert(['id' => 1], ['updated_at' => $last_modified]);

            $cities = [];
            $columns = [
                'geonameid',
                'name',
                'asciiname',
                'alternatenames',
                'latitude',
                'longitude',
                'feature_class',
                'feature_code',
                'country_code',
                'cc2',
                'admin1_code',
                'admin2_code',
                'admin3_code',
                'admin4_code',
                'population',
                'elevation',
                'dem',
                'timezone',
                'modification_date',
            ];

            set_time_limit(900);
            ini_set('memory_limit', '2048M');

            $file = 'http://download.geonames.org/export/dump/RU.zip';
            $cities_folder = 'cities';

            $zip_file_path = public_path($cities_folder.'/RU.zip');
            $txt_file_name = 'RU.txt';

            if (!copy($file, $zip_file_path)) {echo "failed to copy $file...\n";exit;}

            $zip = new \ZipArchive();
            if ($zip->open($zip_file_path, \ZipArchive::CREATE) !== TRUE) {
                exit("cannot open <$zip_file_path>\n");
            }
            $zip->extractTo(public_path($cities_folder),[$txt_file_name]);

            $cities_txt = fopen(public_path($cities_folder.'/'.$txt_file_name), 'r');

            // txt convert to array
            while (!feof($cities_txt)) {
                $line = fgets($cities_txt);
                if ($line) {
                    $explode_line = explode("\t", $line);
                    array_push($cities, array_combine($columns, $explode_line));
                }
            }

            $collection = collect($cities);
            $chunks = $collection->chunk(2000);

            City::truncate();
            foreach ($chunks->toArray() as $chunk) {
                City::insert($chunk);
            }
        }
    }
}
