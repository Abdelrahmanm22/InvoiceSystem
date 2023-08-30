<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $count_all = Invoice::count();
        $States =  ['1','2','3'];
        $percentage =[];
        for($i=0;$i<3;$i++){
            $x = Invoice::where('Value_Status', $States[$i])->count();
            if ($x==0){
                $nx=0;
            }else{
                $nx = $x/$count_all *100;
            }
            array_push($percentage, $nx);
        }
        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 350, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858','#81b214','#ff9642'],
                    'data' =>$percentage,
                ],
                


            ])
            ->options([]);


        $chartjs_2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858', '#81b214', '#ff9642'],
                    'data' => $percentage,
                ]
            ])
            ->options([]);

        return view('home', compact('chartjs', 'chartjs_2'));
    }
}
