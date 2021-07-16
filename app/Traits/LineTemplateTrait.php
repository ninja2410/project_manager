<?php

namespace App\Traits;


use Illuminate\Http\Request;

use App\Item;
use DB;
use \Auth;

trait LineTemplateTrait {
    public function getNewPrice($line_template){
        $tmp = 0;
        foreach($line_template->details as $line){
            $tmp += ($line->item->budget_cost * $line->quantity);
        }
        $line_template->price = $tmp;
        $line_template->update();
        return $tmp;
    }
}
