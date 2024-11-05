<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentForm;
use Illuminate\Support\Facades\Validator;

class StudentFormController extends Controller
{
    protected $fields = [
        "form_id","fname", "lname", "is_active"
    ];

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
            // return $this->sendResponse($response);
            return $response;
        }

        $StudentForm = new StudentForm();
        $form = $StudentForm->createForm($dataArray);
        // print_r($form);
        if ($form) {
            $response["code"] = 1;
            $response["message"] = "Form submitted successfully.";
            $response["result"] =  $form;
            // print_r($response);
            return $response;
        } else {
            $response["code"] = 0;
            $response["message"] = "Failed to submit form.";
            $response["result"] =  $form;
            // return $this->sendResponse($response);
            return $response;
        }

    }

    public function fetchForm(Request $request)
    {
        $response = [];
        try {
            $formModel = new StudentForm();
            $form = $formModel->fetchForm($request->form_id ?? null);
            if (!$form) {
                $response["code"] = 1;
                $response["message"] = "Forms is not present.";
                // return  $this->sendResponse($response);
                return $response;
            }

            $response["code"] = 1;
            $response["message"] = "Students forms fetched successfully.";
            $response['result'] =  $form;
            return $response;
            // return  $this->sendResponse($response);
        } catch (\Exception $e) {
            $response["code"] = 0;
            $response["message"] = $e->getMessage();
            // return $this->sendResponse($response, 500);
            return $response;
        }
    }

    public function updateForm(Request $request)
    {
        // echo "Hi";
       
        $response = [];
        $dataArray = $request->only([...$this->fields, 'is_delete']);
        if (isset($dataArray['is_active']))  unset($dataArray['is_active']);
      
        $validator = Validator::make($dataArray, [
            'form_id' => ['required', 'exists:student_forms,id,is_active,1']
        ]);
        $isDelete = $dataArray['is_delete'] ?? 0;
        if ($validator->fails()) {
            $response["code"] = 0;
            $response["message"] = "Please provide valid inputs.";
            $response["result"] =  $validator->errors();
            // return $this->sendResponse($response);
            return $response;
        }
        $formModel = new StudentForm();
        $form = $formModel->updateForm($dataArray, $isDelete);
        // print_r($form);
        if ($form) {
            $response["code"] = 1;
            $response["message"] = $isDelete ? "Form deleted successfully." : "Form updated successfully.";
            $response["result"] =  $form;
            // return $this->sendResponse($response);
            return $response;
        } else {
            $response["code"] = 0;
            $response["message"] = "Failed to update Form.";
            $response["result"] =  $form;
            // return $this->sendResponse($response);
            return $response;
        }
        //  print_r($validator);
    }
}
