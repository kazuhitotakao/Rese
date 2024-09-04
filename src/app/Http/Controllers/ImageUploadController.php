<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageUploadController extends Controller
{
    public function imageUpload(Request $request)
    {
        if (app('env') == 'local') {
            $image_file = $request->file('image');
            $image_db = Image::where('user_id', Auth::id())->first();
            $shop_image = Shop::where('user_id', Auth::id())->pluck('image')->first();
            if ($image_file == null) {
                return redirect('/owner-page')->with('messageImg', 'ファイルを選択してください');
            }
            $form = [
                'path' => $image_file->store('public/images'),
                'user_id' => Auth::id(),
            ];
            if ($image_db == null) {
                // $image_dbを登録
                $path = $image_file->store('public/images');
                $image = new Image();
                $image->path = $path;
                $image->user_id = Auth::id();
                $image->save();
                return redirect('/owner-page')->with('messageImg', '画像を登録しました。');
            } else {
                // $image_dbありで、$shop_imageの有り無しで場合分け
                if ($shop_image == null) {
                    // $image_dbのみ更新
                    $image_db->update($form);
                    return redirect('/owner-page')->with('messageImg', '画像を更新しました。');
                } else {
                    // $image_dbと$shop_image更新
                    $image_db->update($form);
                    $shop = Shop::where('user_id', Auth::id())->first();
                    $shop->update(['image' => $image_file->store('public/images')]);
                    return redirect('/owner-page')->with('messageImg', '画像を更新しました。');
                }
            }
        }
        if (app('env') == 'production') {
            $image_file = $request->file('image');
            $image_db = Image::where('user_id', Auth::id())->first();
            $shop_image = Shop::where('user_id', Auth::id())->pluck('image')->first();
            if ($image_file == null) {
                return redirect('/owner-page')->with('messageImg', 'ファイルを選択してください');
            }
            $path = Storage::disk('s3')->putFile('/', $image_file);
            $form = [
                'path' => Storage::disk('s3')->url($path),
                'user_id' => Auth::id(),
            ];
            if ($image_db == null) {
                // $image_dbを登録
                $path = Storage::disk('s3')->putFile('/', $image_file);
                // $image_file->store('public/images');
                $image = new Image();
                $image->path = Storage::disk('s3')->url($path);
                $image->user_id = Auth::id();
                $image->save();
                return redirect('/owner-page')->with('messageImg', '画像を登録しました。');
            } else {
                // $image_dbありで、$shop_imageの有り無しで場合分け
                if ($shop_image == null) {
                    // $image_dbのみ更新
                    $image_db->update($form);
                    return redirect('/owner-page')->with('messageImg', '画像を更新しました。');
                } else {
                    // $image_dbと$shop_image更新
                    $image_db->update($form);
                    $shop = Shop::where('user_id', Auth::id())->first();
                    $shop->update(['image' => Storage::disk('s3')->url($path)]);
                    return redirect('/owner-page')->with('messageImg', '画像を更新しました。');
                }
            }
        }
    }
}
