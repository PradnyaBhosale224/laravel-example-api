<?php

namespace App\Http\Controllers;

use App\Models\cluster;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClusterController extends Controller
{
    protected $fields = [
        "cluster_id","cluster_name", "is_active"
    ];

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createCluster(Request $request)
    {
        echo "Hi";
        $dataArray = $request->only($this->fields);
        $validator = Validator::make($dataArray, [
            'cluster_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            $response["code"] = 0;
            $response["message"] = "Please provide valid inputs.";
            $response["result"] =  $validator->errors();
            // return $this->sendResponse($response);
            return $response;
        }
        $clusterForm = new cluster();
        $form = $StudentForm->createForm($dataArray);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(cluster $cluster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cluster $cluster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cluster $cluster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cluster $cluster)
    {
        //
    }
}
