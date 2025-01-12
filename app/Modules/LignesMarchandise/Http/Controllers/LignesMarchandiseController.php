<?php

namespace App\Modules\LignesMarchandise\Http\Controllers;

use Illuminate\Http\Request;

class LignesMarchandiseController
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("LignesMarchandise::welcome");
    }
}
