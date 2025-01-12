<?php

namespace App\Modules\Bad\Http\Controllers;

use App\Modules\Bad\Models\Bad;
use App\Modules\Cpu\Models\Cpu;
use App\Modules\LignesMarchandise\Models\LignesMarchandise;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BadController
{

    public function insertOrReplaceBad(Request $request)
    {

        // Define validation rules for user registration
        $rules = [
            'badId' => 'required',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return error response
        if ($validator->fails()) {
            return [
                "error" => $validator->errors()->first(),
                "status" => 422
            ];
        }
        DB::beginTransaction();
        try {
            $bad = Bad::where('badId', $request->badId)->first(); // model or null
            if ($bad == null) {
                $bad = Bad::create($request->all());
                if ($request->lignesMarchandise != null) {

                    foreach ($request->lignesMarchandise as $ligne) {
                        $ligne["bad_id"] = $bad->id;
                        $lignesMarchandise = LignesMarchandise::firstOrCreate(
                            ['ctr_id' => $ligne['ctr_id'], 'bad_id' => $bad->id], // Replace with the field(s) to check for uniqueness
                            $ligne // Data to insert if not found
                        );
                    }
                }
                $bad->lignes_marchandise = $bad->lignes_marchandise;
                $bad->cpus = $bad->cpus;
                DB::commit();
                return [
                    "payload" => $bad,
                    "message" => "BAD created successfully",
                    "status" => 201
                ];
            } else {
                $bad = $bad->update($request->all());
                $bad = Bad::where('badId', operator: $request->badId)->first(); // model or null
                if ($request->lignesMarchandise != null) {
                    for ($i = 0; $i < count($request->lignesMarchandise); $i++) {
                        $lignesMarchandise = LignesMarchandise::where('bad_id', $bad->id)
                            ->where("ctr_id", $request->lignesMarchandise[$i]["ctr_id"])
                            ->first(); // model or null
                        if ($lignesMarchandise == null) {
                            $lignesMarchandise = new LignesMarchandise();
                            $lignesMarchandise->ctr_id = $request->lignesMarchandise[$i]["ctr_id"];
                            $lignesMarchandise->isoType = $request->lignesMarchandise[$i]["isoType"];
                            $lignesMarchandise->ctr_length = $request->lignesMarchandise[$i]["ctr_length"];
                            $lignesMarchandise->linesite_id = $request->lignesMarchandise[$i]["linesite_id"];
                            $lignesMarchandise->ctr_fe_ind = $request->lignesMarchandise[$i]["ctr_fe_ind"];
                            $lignesMarchandise->bad_id = $bad->id;
                            $lignesMarchandise->save();
                        } else {
                            $lignesMarchandise->ctr_id = $request->lignesMarchandise[$i]["ctr_id"];
                            $lignesMarchandise->isoType = $request->lignesMarchandise[$i]["isoType"];
                            $lignesMarchandise->ctr_length = $request->lignesMarchandise[$i]["ctr_length"];
                            $lignesMarchandise->linesite_id = $request->lignesMarchandise[$i]["linesite_id"];
                            $lignesMarchandise->ctr_fe_ind = $request->lignesMarchandise[$i]["ctr_fe_ind"];
                            $lignesMarchandise->bad_id = $bad->id;
                            $lignesMarchandise->update();
                        }
                    }
                }
                $bad->lignes_marchandise = $bad->lignes_marchandise;
                $bad->cpus = $bad->cpus;
                DB::commit();
                return [
                    "payload" => $bad,
                    "message" => "BAD updated successfully",
                    "status" => 201
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getTrace(),
                'status' => 500
            ];
        }
    }
    public function add_or_replace_cpus_to_bad(Request $request)
    {
        $rules = [
            'badId' => 'required',
        ];
        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        $bad = Bad::where('badId', $request->badId)->first();
        if (!$bad) {
            return [
                "error" => "BAD NOT FOUND",
                "status" => 422
            ];
        }
        DB::beginTransaction();
        try {
            if ($request->cpus != null) {

                foreach ($request->cpus as $ligne) {
                    $ligne["bad_id"] = $bad->id;
                    $cpu = Cpu::updateOrCreate(
                        ['cpuId' => $ligne['cpuId'], 'bad_id' => $bad->id], // Replace with the field(s) to check for uniqueness
                        $ligne // Data to insert if not found
                    );
                }
            }

            $bad->cpus = $bad->cpus;
            $bad->lignes_marchandise = $bad->lignes_marchandise;
            DB::commit();
            return [
                "payload" => $bad,
                "message" => "BAD created successfully",
                "status" => 201
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getTrace(),
                'status' => 500
            ];
        }
    }
    public function index(Request $request)
    {
        try {
            $bads = Bad::select()->with("lignes_marchandise")->with("cpus")->get();

            return [
                "payload" => $bads,
                "status" => 200
            ];
        } catch (\Exception $e) {
            return [
                "error" => $e->getTrace(),
                "status" => 500
            ];
        }
    }


    public function setCheckOutContainers()
    {
        //config(['MESSAGE_ID' => 2]);

        $xmlArray = array();
        try {
            $store = DB::connection('oracle');
            $data = $store->select("SELECT
                ctr_no,
                CTR_SYSTEMUPD_FUNC,
                ctr_id,
                CTR_SYSTEMUPD_FUNC ,
                CTR_SYSTEMUPD_USER,
                ctr_length,
                ctr_height,
                ctr_category_cd,
                ctr_fe_ind ,
                release_id,
                booking_id,
                ARR_TS ,
                DEP_TS ,
                ARR_TRUCKDRIVER_ID,
                ARR_TRUCKDRIVER_NAME,
                ARR_truck_id,
                DEP_TRUCKDRIVER_ID,
                DEP_TRUCKDRIVER_NAME,
                dep_truck_id,
                DEP_TRUCKCARRIER_CD
            FROM
                tc3_ctr_m
            WHERE
                 ctr_category_cd = 'I'
                AND CTR_FE_IND = 'F'
                AND DEP_TS < CURRENT_TIMESTAMP
                AND DEP_TS >= CURRENT_TIMESTAMP - INTERVAL '2' DAY");



            $content = "";
            dd($data);
            //dd( count($data));
            for ($j = 0; $j < count($data); $j++) {
                $date = Carbon::now()->toIso8601String();
                $time = explode("+", $date)[0];
                try {

                    if ($data[$j]->idvoyage != $data[$j]->idvoyage2) {
                        if ($data[$j]->idvoyage == "" || $data[$j]->idvoyage == null || $data[$j]->idvoyage == " ") {
                        }
                    }
                } catch (Exception $e) {
                    return $e;
                }
            }
        } catch (Exception $e) { //first
            return $e;
        }
        return $xmlArray;
    }
}
