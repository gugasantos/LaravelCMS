<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Visitor;
use Illuminate\Http\Request;

class HomeAController extends Controller
{

 public function __construct(){
    $this->middleware('auth');
}


 public function index(Request $request ){
    $interval = intval($request->input('interval', 30));
    if($interval > 120){
        $interval = 120;
    }

    //contagem de visitantes
    $dateInterval = date('Y-m-d H:i:s', strtotime('-'.$interval. 'days'));
    $visitsCount = Visitor::where('date_access', '>=' , $dateInterval)->count();

    //contagem de usuarios online
    $dateLimit = date('Y-m-d H:i:s' , strtotime('-5 minutes'));
    $onlineList = Visitor::select('ip')->where('date_access', '>=', $dateLimit)
    ->groupBy('ip')
    ->get();
    $onlineCount = count($onlineList);

    //contagem de páginas
    $pageCount = Page::count();

    //contagem de usuários
    $userCount = Page::count();

    //contagem para o pagePie

    $pagePie = [];
    $visitsAll = Visitor::selectRaw ('page , count(page) as c')->where('date_access', '>=', $dateLimit)->groupBy('page')->get();
    foreach($visitsAll as $visit){
        $pagePie[$visit['page'] ] = intval($visit['c']);
    }
    $pageLabels = json_encode(array_keys($pagePie));
    $pageValue = json_encode(array_values($pagePie));

    return view('admin.homeA',[
        'visitsCount' => $visitsCount,
        'onlineCount' => $onlineCount,
        'pageCount' => $pageCount,
        'userCount' => $userCount,
        'pageValue' => $pageValue,
        'pageLabels' => $pageLabels,
        'dateInterval' => $interval
    ]);
 }

}
