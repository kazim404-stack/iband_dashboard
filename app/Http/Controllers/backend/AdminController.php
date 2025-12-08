<?php

namespace App\Http\Controllers\backend;

use App\DataTables\AdminsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthAdminRequest;
use App\Models\Admin;
use App\Models\Order;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    /**
     * fetch and display tody, yesterday, this month and the year orders
     */
    public function index()
    {

        $todayOrders = Order::whereDay('created_at', Carbon::today())->get();
        $yesterdayOrders = Order::whereDay('created_at', Carbon::yesterday())->get();
        $monthOrders = Order::whereMonth('created_at', Carbon::now()->month)->get();
        $yearOrders = Order::whereYear('created_at', Carbon::now()->year)->get();
        return view('admin.dashboard.dashboard')->with([
            'todayOrders' => $todayOrders,
            'yesterdayOrders' => $yesterdayOrders,
            'monthOrders' => $monthOrders,
            'yearOrders' => $yearOrders
        ]);
    }
    /**
     * display login form
     */
    public function login()
    {
        if (!auth()->guard('admin')->check()) {
            return view('admin.login');
        }
        return redirect()->route('admin.dashboard');
    }
    /**
     * Auth the admin
     */
    public function auth(AuthAdminRequest $request)
    {
        if ($request->validated()) {
            if (auth()->guard('admin')->attempt([
                'email' => $request->email,
                'password' => $request->password,
            ])) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('admin.login')->with([
                    'error' => 'These credentials do not match our records'
                ]);
            }
        }
    }
    /**
     * logout the admin
     */
    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
    /**
     * change password form
     */
    public function showChangePasswordForm()
    {
        return view('admin.profile.change_password');
    }
    /**
     * change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed'
        ]);

        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return redirect()->back()->with([
                "message" => "Unauthorized request",
                "alert-type" => "error"
            ]);
        }

        if (!Hash::check($request->current_password, $admin->password)) {
            return redirect()->back()->with([
                "message" => "Credentials do not match",
                "alert-type" => "error"
            ]);
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->back()->with([
            "message" => "Password changed successfully",
            "alert-type" => "success"
        ]);
    }
    /**
     * change profile form
     */
    public function chageProfileForm()
    {
        return view('admin.profile.change_profile');
    }
    // change profile
    public function chageProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'required|email|max:200',
            'image' => 'mimes:png,jpg,jpeg,webp|max:2048'
        ]);
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->back()->with([
                "message" => "User not authenticated",
                "alert-type" => "error"
            ]);
        }
        if (!empty($request->hasFile('image'))) {
            $adminImage = $request->file('image');
            $imageName = uniqid() . '.' . $adminImage->getClientOriginalExtension();
            $imagePath = 'backend/assets/images/admin/' . $imageName;
            if (!empty($admin->image) && File::exists(public_path($admin->image))) {
                File::delete(public_path($admin->image));
            }
            $adminImage->move(public_path('backend/assets/images/admin'), $imageName);
        } else {
            $imagePath = $admin->image;
        }
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->image = $imagePath;
        $admin->save();
        $notification = [
            "message" => "profile has been update successfully",
            "alert-type" => "success"
        ];
        return redirect()->back()->with($notification);
    }
    // change status
    public function changeStatus(Request $request)
    {
        $user = Admin::findOrFail($request->id);
        $user->status = $request->status == "true" ? 1 : 0;
        $user->save();
        return response()->json(['status' => "success", "message" => "Status has been change successfully"]);
    }
    // admin list
    public function adminList(AdminsDataTable $dataTable)
    {
        return $dataTable->render('admin.index');
    }
    // Add user
    public function addAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048',
            'password' => 'required|min:8|max:64|confirmed'
        ]);
        $admin = new Admin();
        if (!empty($request->hasFile('image'))) {
            $adminImage = $request->file('image');
            $imageName = uniqid() . '.' . $adminImage->getClientOriginalExtension();

            $imagePath = 'backend/assets/images/admin/' . $imageName;
            $adminImage->move(public_path('backend/assets/images/admin/'), $imageName);
        } else {
            $imagePath = '';
        }
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->image = $imagePath;
        $admin->password = Hash::make($request->password);
        $admin->save();
        $notification = [
            'message' => 'Added Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('admin.admin.list')->with($notification);
    }
    // admin create
    public function adminCreate()
    {
        return view('admin.create');
    }
    // admin delete
    public function delete($id)
    {
        if ($id) {
            $admin = Admin::findOrFail($id);
            if (is_file($admin->image) && public_path($admin->image)) {
                unlink(public_path($admin->image));
            }
            $admin->delete();
            return response()->json(['status' => 'success', 'message' => "The admin has beeb deleted successfully"]);
        }
    }
}
