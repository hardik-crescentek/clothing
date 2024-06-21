<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | App Constants
    |--------------------------------------------------------------------------
    |List of all constants for the app
    */

    
    'document_size_limit' => 1024 * 1024 * 10 , //in Bytes,
    'image_size_limit' => 1024 * 1024 * 10, //in Bytes

    'user_img_path' => 'users',
    'material_img_path' => 'materials',
    'img_thimb_dir_name' => 'thumb',
    'purchase_attachment' => 'purchase_attachment',

    'default_img' => 'public/assets/img/no-image.png',

    'yard_of_1meter' => 1.09361,
    'meter_of_1yard' => 0.914403,
    
    'business_nature' => [        
        'tailor_store'=>'Tailor Store', 
        'online_tailor'=>'Online Tailor', 
        'wholesaler_exporter'=>'Wholesaler/Exporter', 
        'reseller'=>'Reseller', 
        'salesman'=>'Salesman', 
        'garment_manufacturer'=>'Garment Manufacturer',             
        'other'=>'Other',
    ],
    
    'order_status' => [
        0=>'Booking', 
        1=>'Order Placed', 
        2 =>'Dispatched', 
        3 =>'Delivered',         
    ]
];
