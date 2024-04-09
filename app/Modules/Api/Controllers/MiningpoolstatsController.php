<?php
namespace App\Modules\Api\Controllers;



class MiningpoolstatsController
{
    public function index(){
       $html=file_get_contents("https://miningpoolstats.stream/litecoin");
       file_put_contents("mining.txt",$html);
    }



}