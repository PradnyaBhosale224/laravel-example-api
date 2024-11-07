<?php
    namespace App\Http\Controllers;

    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Validator;

    class AuthController extends Controller
    {
        protected $fields = [
            "user_id","name", "email","password","role", "password_confirmation","role","is_active"
        ];
    
        // Register method
        public function register(Request $request)
        {   
            $dataArray = $request->only($this->fields);
            $validator = Validator::make($dataArray, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'role' => 'required|string|max:255',
            ]);
           
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            
            $userModel = new User();
            $form = $userModel->register($dataArray);
           
            // Generate a token for the user
            $token = $form->createToken('API Token')->plainTextToken;

            return response()->json([
                'user' => $form,
                'token' => $token,
            ], 201);
        }

        // Login method
        public function login(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
           
            // Check credentials
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Invalid login details'], 401);
            }
            
            // Get authenticated user
            $user = Auth::user();
            
            // Create token
            $token = $user->createToken('API Token')->plainTextToken;
          
            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 200);
        }

        // Logout method
        public function logout(Request $request)
        {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully'], 200);
        }
    }
