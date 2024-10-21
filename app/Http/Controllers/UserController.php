<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Plan;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use App\Models\Utility;
use Lab404\Impersonate\Impersonate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->can('Manage User')) {
            $users = User::where('created_by', '=', \Auth::user()->creatorId())->where('current_store', \Auth::user()->current_store)->get();
            $plan  = Plan::find(\Auth::user()->plan);
            foreach ($users as $key => $user) {
                $key = $key+1;
                if ($user->is_disable != 1) {
                    if ($key > $plan->max_users && $plan->max_users != '-1') {
                        $user['is_active'] = 0;
                        $user->save();
                    } else {
                        $user['is_active'] = 1;
                        $user->save();
                    }
                }
            }
            return view('users.index', compact('users'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->can('Create User')) {
            $user  = \Auth::user();
            $roles = Role::where('created_by', '=', $user->creatorId())->where('store_id', $user->current_store)->get()->pluck('name', 'id');
            return view('users.create', compact('roles'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('Create User')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => [
                        'required',
                        Rule::unique('users')->where(function ($query) {
                            return $query->where('created_by', \Auth::user()->id)->where('current_store', \Auth::user()->current_store);
                        })
                    ],
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $default_language = DB::table('settings')->select('value')->where('name', 'owner_default_language')->first();
            $objUser    = \Auth::user()->creatorId();
            $objUser = User::find($objUser);
            $role_r = Role::findById($request->role);
            $date = date("Y-m-d H:i:s");
            // $total_users = \Auth::user()->countUsers();
            $total_users = \Auth::user()->countStoreUsers(\Auth::user()->current_store);
            $plan = Plan::find($objUser->plan);
            if ($total_users < $plan->max_users || $plan->max_users == -1) {
                $user                       =  new User();
                $user->name                 =  $request['name'];
                $user->email                =  $request['email'];
                $user->password             = !empty($request['password_switch']) && $request['password_switch'] == 'on' ? Hash::make($request['password']) : null;
                $user->type                 = $role_r->name;
                $user->lang                 = $default_language->value ?? 'en';
                $user->created_by           = \Auth::user()->creatorId();
                $user->email_verified_at    = $date;
                $user->current_store        = $objUser->current_store;
                $user->plan                 = $objUser->plan;
                $user->is_enable_login      = !empty($request['password_switch']) && $request['password_switch'] == 'on' ? 1 : 0;
                $user->save();
                $user->assignRole($role_r);

                //webhook
                $module = 'New User';
                $store = \Auth::user()->current_store;
                // dd($store);
                $webhook =  Utility::webhook($module, $store);

                if ($webhook) {
                    $parameter = json_encode($user);

                    // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                    $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                    if ($status != true) {
                        $msg = 'Webhook call failed.';
                    }
                }
            } else {

                return redirect()->back()->with('error', __('Your Userds limit is over Please upgrade plan'));
            }
            return redirect()->route('users.index')->with('success', __('User successfully created.') .((isset($msg)) ? '<br> <span class="text-danger">' . $msg . '</span>' : ''));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('profile');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::user()->can('Edit User')) {
            $user  = User::find($id);
            $roles = Role::where('created_by', '=', \Auth::user()->creatorId())->where('store_id', \Auth::user()->current_store)->get()->pluck('name', 'id');
            return view('users.edit', compact('user', 'roles'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('Edit User')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'unique:users,email,' . $id,
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $user = User::findOrFail($id);

            $role          = Role::findById($request->role);
            $input         = $request->all();
            $input['type'] = $role->name;
            $user->fill($input)->save();

            $user->assignRole($role);
            $roles[] = $request->role;
            $user->roles()->sync($roles);
            return redirect()->route('users.index')->with('success', 'User successfully updated.');
        }
        else{
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Auth::user()->can('Delete User')) {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('users.index')->with('success', 'User successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function reset($id)
    {
        if (\Auth::user()->can('Reset Password')) {
            $Id        = \Crypt::decrypt($id);

            $user = User::find($Id);

            $employee = User::where('id', $Id)->first();

            return view('users.reset', compact('user', 'employee'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function updatePassword(Request $request, $id)
    {
        if (\Auth::user()->can('Reset Password')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'password' => 'required|confirmed|same:password_confirmation',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $user                 = User::where('id', $id)->first();
            if (isset($request->login_enable)) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'is_enable_login' => 1,
                ])->save();
    
                return redirect()->back()->with(
                    'success',
                    'User login enable successfully.'
                );
            } else {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();
                
                return redirect()->back()->with(
                    'success',
                    'User Password successfully updated.'
                );
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function LoginWithOwner(Request $request, User $user, $id)
    {
        $user = User::find($id);
        if ($user->is_enable_login != 0 && $user->is_active != 0 && $user->password != null) {
            if ($user && auth()->check()) {
                Impersonate::take($request->user(), $user);
                return redirect('/dashboard');
            }
        } else {
            return redirect()->back()->with('error', __('Owner account has been deactivated.'));
        }
    }
    public function ExitOwner(Request $request)
    {
        \Auth::user()->leaveImpersonation($request->user());
        return redirect('/dashboard');
    }

    public function OwnerInfo($id)
    {
        if(!empty($id)){
            $data = $this->Counter($id);
            if($data['is_success']){
                $users_data = $data['response']['users_data'];
                $store_data = $data['response']['store_data'];
                return view('admin_store.ownerinfo', compact('id','users_data','store_data'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function Counter($id)
    {
        $response = [];
        if(!empty($id))
        {
            $stors= Store::where('created_by', $id)
            ->selectRaw('COUNT(*) as total_store, SUM(CASE WHEN is_store_enabled = 0 THEN 1 ELSE 0 END) as disable_store, SUM(CASE WHEN is_store_enabled = 1 THEN 1 ELSE 0 END) as active_store')
            ->first();
            $stores = Store::where('created_by',$id)->get();
            $users_data = [];
            foreach($stores as $store)
            {
                $users = User::where('created_by',$id)->where('current_store',$store->id)->selectRaw('COUNT(*) as total_users, SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as disable_users, SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_users')->first();

                $users_data[$store->name] = [
                    'store_id' => $store->id,
                    'total_users' => !empty($users->total_users) ? $users->total_users : 0,
                    'disable_users' => !empty($users->disable_users) ? $users->disable_users : 0,
                    'active_users' => !empty($users->active_users) ? $users->active_users : 0,
                ];
            }
            $store_data =[
                'total_store' =>  $stors->total_store,
                'disable_store' => $stors->disable_store,
                'active_store' => $stors->active_store,
            ];

            $response['users_data'] = $users_data;
            $response['store_data'] = $store_data;

            return [
                'is_success' => true,
                'response' => $response,
            ];
        }
        return [
            'is_success' => false,
            'error' => 'Plan is deleted.',
        ];
    }

    public function UserUnable(Request $request)
    {
		if(!empty($request->id) && !empty($request->owner_id))
		{
		    if($request->name == 'user')
		    {
		        User::where('id', $request->id)->update(['is_active' => $request->is_store_enabled , 'is_disable' => $request->is_store_enabled == 0 ? 1 : 0 ]);
		        $data = $this->Counter($request->owner_id);

		    }
		    elseif($request->name == 'store')
		    {
                $enabled_stores = Store::where('created_by', $request->owner_id)
                    ->where('is_store_enabled', 1)
                    ->count();
                $owner = User::find($request->owner_id);
                if($request->is_store_enabled == 0){
                    if($enabled_stores != 1){

                        Store::where('id',$request->id)->update(['is_store_enabled' => $request->is_store_enabled]);

                        User::where('current_store',$request->id)->where('type','!=','owner')->update(['is_active' => $request->is_store_enabled , 'is_disable' => $request->is_store_enabled == 0 ? 1 : 0 ]);

                        $stores_enabled = Store::where('created_by', $request->owner_id)->where('is_store_enabled', 1)->first();
                        User::where('id', $request->owner_id)->update(['current_store' => $stores_enabled->id]);
                    }else{
                        return response()->json(['error' => __('All Store can not disable. At least One store must be enabled.')]);
                    }
                }else{
                    Store::where('id',$request->id)->update(['is_store_enabled' => $request->is_store_enabled]);
                }
                $data = $this->Counter($request->owner_id);
		    }
		    if($data['is_success'])
		    {
		        $users_data = $data['response']['users_data'];
		        $store_data = $data['response']['store_data'];
		    }
		    if($request->is_store_enabled == 1){

		        return response()->json(['success' => __('Successfully Unable.'),'users_data' => $users_data, 'store_data' => $store_data]);
		    }else
		    {
		        return response()->json(['success' => __('Successfull Disable.'),'users_data' => $users_data, 'store_data' => $store_data]);
		    }
		}
		return response()->json('error');
    }

    public function UserLoginManage($id)
    {
        if(\Auth::user()->can('Reset Password'))
        {
            $oId        = \Crypt::decrypt($id);
            $user = User::find($oId);
            if($user->is_enable_login == 1)
            {
                $user->is_enable_login = 0;
                $user->save();
                return redirect()->back()->with('success', 'User login disable successfully.');
            }
            else
            {
                $user->is_enable_login = 1;
                $user->save();
                return redirect()->back()->with('success', 'User login enable successfully.');
            }

        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
