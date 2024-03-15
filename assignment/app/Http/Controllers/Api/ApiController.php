<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class ApiController extends Controller
{
    //register
    public function saveRegister(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email'],
            'type' => ['required'],
            'password' => ['required', 'confirmed', 'min:3', 'max:255']
        ]);
  
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type
        ]);

        return response() -> json([
            'status' => true,
            'message' => 'User created successfully'
        ], 201);
    }
    //register end

    //login
    public function loginAction(Request $request)
    {
        validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();

        if(!Auth::attempt($request->only('email', 'password'))){
            throw ValidationException::withMessages(['email' => 'Invalid login details']);
        }

        $user = User::where('email', $request->email)->first();

        if(Auth::user()->type == 'Owner'){
            $token = $user->createToken("ownerToken")->plainTextToken;

            return response() -> json([
                'status' => true,
                'message' => 'Owner login successful',
                'token' => $token
            ], 201);

        }elseif(Auth::user()->type == 'Cashier'){
            $token = $user->createToken("cashierToken")->plainTextToken;
            
            return response() -> json([
                    'status' => true,
                    'message' => 'Cashier login successful',
                    'token' => $token
                ], 201);
        }else{
            $token = $user->createToken("managerToken")->plainTextToken;
            return response() -> json([
                    'status' => true,
                    'message' => 'Manager login successful',
                    'token' => $token
                ], 201);
        }
    }
    //login end


    //logout
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        if(Auth::user()->type == 'Owner'){

            return response() -> json([
                'status' => true,
                'message' => 'Owner logout successful',
            ], 201);

        }elseif(Auth::user()->type == 'Cashier'){
            
            return response() -> json([
                    'status' => true,
                    'message' => 'Cashier logout successful',
                ], 201);
        }else{

            return response() -> json([
                    'status' => true,
                    'message' => 'Manager logout successful',
                ], 201);
        }
    }
    //logout end 

    //add items
    public function addItems(Request $request){  

        if(Auth::user()->type == 'Owner'){
            $request->validate([
                'item_name' => ['required', 'max:255', 'unique:items'],
                'description' => ['required'],
                'manufacturer' => ['required'],
                'unit_price' => ['required','numeric', 'min:0'],
                'quantity_in_stock' => ['required','integer', 'min:0'],
            ]);
      
            Item::create([
                'item_name' => $request->item_name,
                'description' => $request->description,
                'manufacturer' => $request->manufacturer,
                'unit_price' => $request->unit_price,
                'quantity_in_stock' => $request->quantity_in_stock
            ]);

            $data = Item::all();

            return response() -> json([
                'status' => true,
                'message' => 'Item added successfully',
                'data' => $data
            ]);

        }else{
            return response() -> json([
                'status' => false,
                'message' => 'Unauthorized access'
            ]);
        }

    }
    //add items end

    //edit items
    public function editItems(Request $request, $id){
        $item = Item::find($id);

        if(!$item){
            return response() -> json([
                'status' => false,
                'message' => 'Item not found'
            ]);
        }else{
            if(Auth::user()->type == 'Owner' || Auth::user()->type == 'Cashier'){
                
                $request->validate([
                    'item_name' => ['required', 'max:255', 'unique:items'],
                    'description' => ['required'],
                    'manufacturer' => ['required'],
                    'unit_price' => ['required','numeric', 'min:0'],
                    'quantity_in_stock' => ['required','integer', 'min:0'],
                ]);
                
                $item->update([
                    'item_name' => $request->item_name,
                    'description' => $request->description,
                    'manufacturer' => $request->manufacturer,
                    'unit_price' => $request->unit_price,
                    'quantity_in_stock' => $request->quantity_in_stock
                ]);
                
                $data = Item::all();
                return response() -> json([
                    'status' => true,
                    'message' => 'Item details updated successfully',
                    'data' => $data
                ]);
            }else{
                return response() -> json([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
            }
        }
    }
    //edit items end

    //delete items
    public function deleteItems(Request $request, $id){
        $item = Item::find($id);
        if(!$item){
            return response() -> json([
                'status' => false,
                'message' => 'Item not found'
            ]);
        }else{
            if(Auth::user()->type == 'Owner' || Auth::user()->type == 'Cashier'){
                $item->delete();
                return response() -> json([
                    'status' => true,
                    'message' => 'Item deleted successfully'
                ]);
            }else{
                return response() -> json([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
            }
        }
    }
    //delete items end

    //add customers
    public function addCustomers(Request $request){

        if(Auth::user()->type == 'Owner'){
            $request->validate([
                'customer_name' => ['required', 'max:255', 'unique:customers'],
                'email' => ['required','email', 'unique:customers'],
                'phone' => ['required','string', 'max:20'],
                'address' => ['required','string'],
            ]);

            Customer::create([
                'customer_name' => $request->customer_name,
                'email' => $request->email,
                'manufacturer' => $request->manufacturer,
                'phone' => $request->phone,
                'address' => $request->address
            ]);

            $data = Customer::all();

            return response() -> json([
                'status' => true,
                'message' => 'Customer details added successfully',
                'data' => $data
            ]);
        }else{
            return response() -> json([
                'status' => false,
                'message' => 'Unauthorized access'
            ]);
        }
    }
    //add customers end

    //edit customer details
    public function editCustomer(Request $request, $id){

        $customer = Customer::find($id);

        if(!$customer){
            return response() -> json([
                'status' => false,
                'message' => 'Customer not found'
            ]);
        }else{
            if(Auth::user()->type == 'Owner' || Auth::user()->type == 'Manager'){
                
                $request->validate([
                    'customer_name' => ['required', 'max:255', 'unique:customers'],
                    'email' => ['required','email', 'unique:customers'],
                    'phone' => ['required','string', 'max:20'],
                    'address' => ['required','string'],
                ]);
    
                $customer->update([
                    'customer_name' => $request->customer_name,
                    'email' => $request->email,
                    'manufacturer' => $request->manufacturer,
                    'phone' => $request->phone,
                    'address' => $request->address
                ]);
                
                $data = Customer::all();
                return response() -> json([
                    'status' => true,
                    'message' => 'Customer details updated successfully',
                    'data' => $data
                ]);
            }else{
                return response() -> json([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
            }
        }

    }
    //edit customer details end

    //delete customer details
    public function deleteCustomer(Request $request, $id){

        $customer = Customer::find($id);

        if(!$customer){
            return response() -> json([
                'status' => false,
                'message' => 'Customer not found'
            ]);
        }else{
            if(Auth::user()->type == 'Owner' || Auth::user()->type == 'Manager'){
                
                $customer->delete();

                return response() -> json([
                    'status' => true,
                    'message' => 'Customer details deleted successfully',
                ]);
            }else{
                return response() -> json([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ]);
            }
        }

    }
    //delete customer details end
}