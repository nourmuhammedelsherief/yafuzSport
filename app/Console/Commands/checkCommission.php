<?php

namespace App\Console\Commands;


use App\UserDevice;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class checkCommission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:commission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        $now = Carbon::now('Asia/Riyadh')->format('Y-m-d');
       $orders=  DB::table('orders')->where('end_date','!=',null)->where('status','!=',3)->where('end_date','<',$now)->get();
        foreach ($orders as $data){

            DB::table('orders')->where('id',$data->id)->update(['status'=>3]);
            $devicesTokens =  UserDevice::where('user_id',DB::table('orders')->where('id',$data->id)->first()->user_id)
                ->get()
                ->pluck('device_token')
                ->toArray();


            if ($devicesTokens) {
                sendMultiNotification("طلب منتهي", "تم انتهاء مدة طلبك" ,$devicesTokens);

            }

            saveNotification(DB::table('orders')->where('id',$data->id)->first()->user_id, "طلب منتهي" , '1', "تم انتهاء مدة طلبك" , $data->id , null);

        }

        $offers = DB::table('sawaq_offer_price')->where('end_date','!=',null)->where('end_date','<',$now)->where('commission_status','!=',1)->get();
        foreach ($offers as $data){
            DB::table('sawaq_offer_price')->where('id',$data->id)->update(['commission_status'=>2]);
        }


    }
}
