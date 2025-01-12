<?php

namespace App\Modules\Cpu\Http\Controllers;

use Illuminate\Http\Request;

class CpuController
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("Cpu::welcome");
    }
}
