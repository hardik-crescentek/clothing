<?php

namespace App\Utils;

use DB;
use Storage;
use File;
use Image;
use GuzzleHttp\Client;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Keygen\Keygen;
use App\Purchase;
use App\PurchaseItem;
use App\OrderItem;

class Util
{
    /**
     * Calculates percentage for a given number
     *
     * @param int $number
     * @param int $percent
     * @param int $addition default = 0
     *
     * @return float
     */
    public static function calc_percentage($number, $percent, $addition = 0)
    {
        return ($addition + ($number * ($percent / 100)));
    }

    /**
     * Calculates base value on which percentage is calculated
     *
     * @param int $number
     * @param int $percent
     *
     * @return float
     */
    public static function calc_percentage_base($number, $percent)
    {
        return ($number * 100) / (100 + $percent);
    }

    /**
     * Calculates percentage
     *
     * @param int $base
     * @param int $number
     *
     * @return float
     */
    public static function get_percent($base, $number)
    {
        $diff = $number - $base;
        return ($diff / $base) * 100;
    }

    
    /**
     * Sends SMS notification.
     *
     * @param  array $data
     * @return void
     */
    public static function sendSms($data)
    {
        return;

        // $client = new Client();

        // if ($sms_settings['request_method'] == 'get') {
        //     $response = $client->get($sms_settings['url'] . '?' . http_build_query($request_data));
        // } else {
        //     $response = $client->post($sms_settings['url'], [
        //         'form_params' => $request_data
        //     ]);
        // }
    }

    /**
     * Generates unique token
     *
     * @param void
     *
     * @return string
     */
    public static function generateToken()
    {
        // return md5(rand(1, 10) . microtime());

        // $alnum = Keygen::alphanum(15)->generate();
        $token = Keygen::token(28)->generate();

        return $token;
    }

    public static function generateFileName($ext = null)
    {
        $filename = Keygen::bytes();
        if (filled($ext)) {
            $filename = $filename->suffix($ext);
        }
        $filename = $filename->generate(true, ['strrev', function ($key) {
            return substr(md5($key), mt_rand(0, 8), 20);
        }]);
        return $filename;
    }

    public static function generateID($length = 16)
    {
        // ensures the length is an integer
        $length = is_int($length) ? $length : 16;

        // prefixes the key with a random integer between 1 - 9 (inclusive)
        return Keygen::numeric($length - 1)->prefix(mt_rand(1, 9))->generate();
    }
    
    public static function gen_new_barcode_id($article_no)
    {
        $year = date("y");
        $month = date("m");
        $date = date("d");
        $rand_no = rand(10,100);
        $rand_no2 = mt_rand(10,100);
        $string_bc = $article_no;
        return  $string_bc;
    }

    /**
     * Uploads document to the server if present in the request
     * @param obj $request, string $file_name, string dir_name
     *
     * @return string
     */
    public static function uploadFile($request, $file_name, $dir_name)
    {
        // $dir_name = ($dir_name ? DIRECTORY_SEPARATOR.ltrim($dir_name, DIRECTORY_SEPARATOR) : $dir_name) ;
        if ($request->hasFile($file_name) && $request->file($file_name)->isValid()) {
            if ($request->$file_name->getSize() <= config('constants.image_size_limit')) {
                $new_file_name = self::generateFileName() . '.' . $request->$file_name->getClientOriginalExtension();
                return $request->$file_name->storeAs($dir_name, $new_file_name);
            }
        }
    }

    public static function genrateThumb($originalImage, $width = 150, $height = 150, $destination = null)
    {
        if (!Storage::exists($originalImage)) {
            return false;
        }
        // echo $source = Storage::path('');die;
        $source = Storage::path($originalImage);
        if (blank($destination)) {
            $destination = dirname($source) . DIRECTORY_SEPARATOR . ltrim(config('constants.img_thimb_dir_name'), DIRECTORY_SEPARATOR);
        }
        if (!File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true, true);
        }
        $thumbnailImage = Image::make($source);
        $destination .= DIRECTORY_SEPARATOR . $thumbnailImage->basename;
        // $thumbnailImage->fit(100, 100, function ($constraint) {
        //     $constraint->upsize();
        // });
        // $thumbnailImage->resizeCanvas($width, $height, 'center', false, 'ff00ff');
        $thumbnailImage->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $thumbnailImage->save($destination);
    }

    /**
     * Retrieves IP address of the user
     *
     * @return string
     */
    public static function getUserIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function removeFile($file, $thumb = false)
    {
        if (Storage::exists($file)) {
            Storage::delete($file);
            if ($thumb) {
                $path_info = pathinfo($file);
                $file = $path_info['dirname'] . DIRECTORY_SEPARATOR . ltrim(config('constants.img_thimb_dir_name'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $path_info['basename'];
                Storage::delete($file);
            }
        }
    }

    public static function get_purchase_data($id)
    {
        $purchase_data = Purchase::where('id',$id)->first();
        return ($purchase_data) ? $purchase_data->pcs_no : "";
    }

    public static function get_single_purchase_data($id)
    {
        $purchase_data = Purchase::where('id',$id)->first();
        return ($purchase_data) ? $purchase_data : "";
    }

    public static function get_purchase_item_data($id)
    {
        $purchase_data = PurchaseItem::where('purchase_id',$id)->first();
        return ($purchase_data) ? $purchase_data->article_no : "";
    }

    public static function get_order_data($id)
    {
        $order_data = OrderItem::where('roll_id',$id)->first();
        return ($order_data) ? $order_data : "";
    }
}
