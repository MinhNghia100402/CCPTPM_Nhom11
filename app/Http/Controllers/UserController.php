<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\IdentityPaper;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function fetchEmploye()
    {
        $users = User::query()->where('role', UserRole::Admin)->get([
            'id',
            'name',
            'email',
            'phone',
            'role',
            'address',
        ]);

        return response()->json(['data' => $users]);
    }

    public function storeEmploye(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'address' => 'required|string',
            'password' => 'required|string',
            'confirm_password' => 'same:password',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = UserRole::Admin;
        $user = User::create($validated);

        return response()->json(['message' => 'Thêm nhân viên thành công']);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $user = User::find($id);

        if (!$user) return to_route('client.index');

        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'address' => 'required|string',
        ]);

        $user->update($validated);

        return to_route('client.profile')->with('message', 'Cập nhật thông tin thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyEmploye(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        $papers = IdentityPaper::query()->where('user_id', $id)->get();

        if ($papers->count() > 0) {
            foreach ($papers as $paper) {
                Storage::disk('public')->delete($paper->image);
            }
        } 
        $user->delete();

        return redirect()->back()->with('message', 'Xóa nhân viên thành công');
    }

    public function changePassword(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string',
            'confirm_password' => 'required|string|same:new_password',
        ]);

        $user = User::find($id);

        if (!Hash::check($validated['old_password'], $user->password)) {
            return redirect()->back()->withErrors(['old_password' => 'Mật khẩu cũ không đúng']);
        }

        $user->update(['password' => Hash::make($validated['new_password'])]);

        return redirect()->back()->with('message', 'Đổi mật khẩu thành công');
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy nhân viên']);
        }

        return response()->json(['data' => $user]);
    }

    public function updateEmploye(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy nhân viên']);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'address' => 'required|string',
        ]);
        $user->update($validated);

        return response()->json(['message' => 'Cập nhật nhân viên thành công']);
    }
}
