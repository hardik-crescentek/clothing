<?php
if (!function_exists('img_url')) {
    function img_url($file, $orignal = false, $default = null)
    {
        if (Storage::exists($file)) {
    
            if (!$orignal) {
                $path_info = pathinfo($file);
                $file = $path_info['dirname'] . DIRECTORY_SEPARATOR . ltrim(config('constants.img_thimb_dir_name'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $path_info['basename'];
            }
    
            return Storage::url($file);
        }
    
        $default = ($default) ? $default : config('constants.default_img');
        return url($default);
    }
}

if(!function_exists('attechment_url')){
    function attechment_url($file,$default=null)
    {
        if($file!=null){
            if (Storage::exists($file)) {
                return Storage::url($file);
            }
        }
        $default = ($default) ? $default : config('constants.default_img');
        return url($default);
    }
}
if (!function_exists('meter2yard')) {
    function meter2yard($meter)
    {
        return $meter * config('constants.yard_of_1meter', 1.09361);
    }
}

if (function_exists('yard2meter')) {
    function yard2meter($yard)
    {
        return $yard * config('constants.meter_of_1yard', 0.914403);
    }
}

if (!function_exists('calc_total')) {
    function calc_total($price, $qty, $shipping = 0, $tax = 0, $discount = 0)
    {
        $total_price = $price * $qty;
        $total_price = $total_price + $shipping - $discount;
        if ($tax) {
            $total_price = $total_price + ($total_price * ($tax / 100));
        }
        return number_format((float) $total_price, 2, '.', '');
    }
}

if (!function_exists('calc_total_per_meter')) {
    function calc_total_per_meter($price, $qty, $shipping = 0, $tax = 0, $discount = 0)
    {
        $exp_per_meter = ($shipping - $discount) / $qty;
        $total_price = $price + $exp_per_meter;
        if ($tax) {
            $total_price = $total_price + ($total_price * ($tax / 100));
        }
        return number_format((float) $total_price, 2, '.', '');
    }
}

if (!function_exists('total_cost')) {
    function total_cost($price , $qty , $shipping_cost_per_meter)
    {
        $total_price = $price + $shipping_cost_per_meter;
        $total_cost = $total_price * $qty;
        return number_format((float) $total_cost, 2, '.', '');
    }
}

if (!function_exists('total_per_meter')) {
    function total_per_meter($price , $shipping_cost_per_meter)
    {
        $total_price=$price + $shipping_cost_per_meter;
        return number_format((float) $total_price, 2, '.', '');
    }
}

/**
 * Format decimal value
 * @param float $number <p>Float Value.</p>
 * @param int $decimals <p>Sets the number of decimal points. 0=never, 1=if needed, 2=always Default=2  </p>     
 */

 if (!function_exists('decimal_format')) {
     function decimal_format($number, $decimals = 1)
     {
         if (!$number) { // zero
             $number = ($decimals == 2 ? '0.00' : '0'); // output zero
         } else { // value
             if (floor($number) == $number) { // int
                 $number = number_format($number, ($decimals == 2 ? 2 : 0), '.', ''); // format
             } else { // float
                 $number = number_format(round($number, 2), ($decimals == 0 ? 0 : 2), '.', ''); // format
             } // integer or decimal
         } // value
         return $number;
     
     }
 }
