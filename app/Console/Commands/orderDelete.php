<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\OrderItem;
use Carbon\Carbon;

class orderDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete out dated booking Order';

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
    
        $orderDate = Order::select('id','order_date','booking_days','status')->where('status','0')->get();
        if ($orderDate) {
            foreach ($orderDate as $key => $value) {
                $orderDate = $value['order_date'];
                $validDay = $value['booking_days'];
                $validDate = Carbon::createFromFormat('d/m/Y',$orderDate)->addDays($validDay);
                $currentDate = Carbon::now();

                if ($validDate < $currentDate) {
                   $order =  Order::find($value['id']);
                   $order->delete();
                   if ($order) {
                        \Log::info('Order Deleted');
                   }            
                }else{
                    \Log::info("Can't Delete This Order");
                }  
            }
        }
            \Log::info('Delete advance bookin Order schedular working Properly');
 
    }
}
