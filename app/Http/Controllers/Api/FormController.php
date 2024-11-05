<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class FormController extends Controller
{
    protected $fields = [
        "fname", "lname", "is_active"
    ];

    public function index()
    {
        //
        return Form::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createForm(Request $request)
    {
        $response = [];
        $dataArray = $request->only($this->fields);
        $validator = Validator::make($dataArray, [
            'fname' => 'required|string',
            'lname' => 'required|string'
        ]);

        if ($validator->fails()) {
            $response["code"] = 0;
            $response["message"] = "Please provide valid inputs.";
            $response["result"] =  $validator->errors();
            return $this->sendResponse($response);
        }

        $FormModel = new Form();
        $form = $FormModel->createForm($dataArray);
        if ($form) {
            $response["code"] = 1;
            $response["message"] = "Form submitted successfully.";
            $response["result"] =  $form;
            return $this->sendResponse($response);
        } else {
            $response["code"] = 0;
            $response["message"] = "Failed to submit form.";
            $response["result"] =  $form;
            return $this->sendResponse($response);
        }
        return "Hi";

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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
