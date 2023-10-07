<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Percentage;

class HomeController extends Controller
{

    private $myMap = array();

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function declareMap(){
        $this->myMap[1]="January";
        $this->myMap[2]="February";
        $this->myMap[3]="March";
        $this->myMap[4]="April";
        $this->myMap[5]="May";
        $this->myMap[6]="June";
        $this->myMap[7]="July";
        $this->myMap[8]="August";
        $this->myMap[9]="September";
        $this->myMap[10]="October";
        $this->myMap[11]="November";
        $this->myMap[12]="December";
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $lastFiveMonthsTotal = Invoice::lastFiveMonthsTotal()->get();

        // return $lastFiveMonthsTotal;
        $total=0;
        foreach ($lastFiveMonthsTotal as $l){
            $total += $l->total;
        }
        // return $total;
        $totals =[0,0,0,0,0];
        $lastMonths=[1,1,1,1,1];
        for($i=0;$i<count($lastFiveMonthsTotal);$i++){
            $totals[$i] = $lastFiveMonthsTotal[$i]['total'];
            $lastMonths[$i] = $lastFiveMonthsTotal[$i]['month'];
        }
        // return $lastMonths;
        $this->declareMap();
        // return $this->myMap;
        // return $lastMonth;
        // return $totals;
        $count_all = Invoice::count();
        $States =  ['2','1','3'];
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
            ->labels([$this->myMap[$lastMonths[0]],$this->myMap[$lastMonths[1]],$this->myMap[$lastMonths[2]],$this->myMap[$lastMonths[3]],$this->myMap[$lastMonths[4]]])
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858','#81b214','#ff9642','#091f42','#164209'],
                    'data' =>$totals,
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
