<?php

namespace App\Http\Controllers\backend;

use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('admin.user.index');
    }
    public function delete(User $user)
    {
        $user->delete();
        return response()->json(['status' => 'success', 'message' => 'User has been deleted successfully']);
    }
}
