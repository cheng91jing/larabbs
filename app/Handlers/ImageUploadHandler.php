<?php
/**
 * 图片上传处理
 */

namespace App\Handlers;


use Illuminate\Http\UploadedFile;
use Storage;
use Image;

class ImageUploadHandler
{
    //只允许一下后缀的图片文件上传
    protected $allowed_ext = ["png", "jpg", "gif", 'jpeg'];

    public function savePublic(UploadedFile $file, $folder, $file_prefix = '', $max_width = false)
    {
//        //构建存储的文件夹规则,值如：uploads/images/avatar/201804/15
//        //文件夹切割能让查找效率更高
//        $folder_name = "uploads/image/{$folder}/" . date('Ym/d', time());
//
//        //文件具体存储的物理路径， public_path() 获取的是 public 文件夹的物理路径
//        // 值如：/home/vagrant/Code/larabbs/public/uploads/images/avatars/201709/21/
//        $upload_path = public_path() . '/' . $folder_name;
//
//        //获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
//        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';
//
//        // 拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID
//        // 值如：1_1493521050_7BVc9v9ujP.png
//        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;
//
//        //如果上传的不是图片将终止操作
//        if(!in_array($extension, $this->allowed_ext)){
//            return false;
//        }
//
//        //将图片移动到我们的目标存储路径中
//        $file->move($upload_path, $filename);
//        return [
//            'path' => config('app.url') . "/{$folder_name}/{$filename}",
//        ];
        //公共文件保存路径目录 如 avatar/201804/15
        $path = "{$folder}/" . date('Ym/d');
        //保存的文件名
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = ($file_prefix ? $file_prefix . '_' : '') .time() . '_' . str_random(10) . '.' . $extension;
        $store_path = $file->storeAs($path, $filename, 'public');
//        dd(Storage::disk('public')->path($store_path));
        //裁剪图片
        if($max_width && $extension != 'gif'){
            $this->reduceSize(Storage::disk('public')->path($store_path), $max_width);
        }

        return [
            'path' => Storage::url($store_path),
        ];
    }

    public function reduceSize($file_path, $max_width)
    {
        Image::make($file_path)->resize($max_width, null, function($constraint){
            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save();
    }
}