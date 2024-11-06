<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use Illuminate\Support\Facades\Validator;

class ConversationController extends Controller
{
    public function fetchConversation(Request $request)
    {
        $response = [];

        // $validator = Validator::make($request->all(), [
        //     'user_id' => [$this->exists('agent', 'id', ['is_Active' => 1])]
        // ]);

        // $validator = Validator::make($request->all(), [
        //     'user_id' => ['required', 'exists:agent,id']
        // ]);
        // echo "Hi";
        // print_r($request->all());
        // if ($validator->fails()) {
        //     $response["code"] = 0;
        //     $response["message"] = "Please provide valid inputs.";
        //     $response["result"] =  $validator->errors();
        //     // return $this->sendResponse($response);
        //     return $response;
        // }else{
        //     echo "Hi";
        // }
        
        try {            
            $conversation = new Conversation();
            $data = [];
            $data = $conversation->view_all_conversation($request);
            // print_r($data);
            if ($data) {
                $response['code'] = 1;
                $response['message'] = 'Conversation fetched successfully.';
                $response['result'] = $data;
            } else {
                $response['code'] = 0;
                $response['message'] = 'Conversation Not Found.';
                $response['result'] = [];
            }

            return $response;
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
            return $$response;
        }
    }
}
