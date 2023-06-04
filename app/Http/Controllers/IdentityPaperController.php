<?php

namespace App\Http\Controllers;

use App\Models\IdentityPaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IdentityPaperController extends Controller
{
    public function showByUserId(string $id)
    {
        $data = IdentityPaper::query()->where('user_id', $id)->get();

        $arr = [];
        foreach ($data as $item) {
            $newArr = $item->toArray();
            $newArr['image'] = config('app.url') . config('app.image') . $item->image;
            $arr[] = $newArr;
        }
        return response()->json(['data' => $arr]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'image' => 'required|image',
            'user_id' => 'required|numeric|exists:users,id',
        ]);

        $file_name = time() . '.' . $request->file('image')->extension();
        $request->file('image')->storeAs('images/identity_papers', $file_name, 'public');

        $validated['image'] = 'images/identity_papers/' . $file_name;
        IdentityPaper::create($validated);

        return response()->json(['message' => 'Thêm giấy tờ tùy thân thành công']);
    }

    public function destroy($id)
    {
        $obj = IdentityPaper::find($id);

        if (!$obj) {
            return response()->json(['message' => 'Không tìm thấy giấy tờ tùy thân']);
        }

        Storage::disk('public')->delete($obj->image);
        $obj->delete();

        return response()->json(['message' => 'Xóa thành công giấy tờ tùy thân']);
    }
}
