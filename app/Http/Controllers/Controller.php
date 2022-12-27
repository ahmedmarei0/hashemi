<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function send_notification($tokens, $title, $body)
    {

        try {
            //$token_1 = 'e6WBgNuvz0WdoCSZxLpOKM:APA91bFYgWK6jtSrMlG0axpGzWdBz7emIRrc16MCO3Z8jW3gD2NSkGdfaF-y_3pt_k7oh8ZbFUGb0roDp4ycFidta4iGtDEfMAZPfbJTVdyqXhTXYLOnH3LUoCnCAvyvPo6qM-NtLF63';
            $url = 'https://fcm.googleapis.com/fcm/send';
            // $serverKey = 'AAAAtxCDEp0:APA91bFjxb511Hbuk2IrA0EpskLFlyTtLi6kMGfPY3NAOkkiW0CERXIc7IZOdyGc_Ex64dLF5v2O03G5QdnEnqB2_hsqcdQg93Lmunu3AQ5KHPVKlvyO4CiI9CbxIcXlaxycBTI4XTeh';
            $serverKey = 'AAAA8fKSKWs:APA91bH0rFQJIGqH___-ccXM8MRgTjhZMVH3TtaU19R4QPxXO6uaMkSDeT_lTwsUo6I0BOJPbKomOgL8cy6zh5t6xRnRlehlVvCFdKcMDaA6lhhw_NcwD8IWuCDAkgXhou1gfFOAyTSn';

            $data = [
                "registration_ids" => $tokens,
                'notification' => [
                    "title" => $title,
                    "sound" => "sound.caf",
                ],
                "data" => [
                    "type" => $body,
                ],
                "aps" => [
                    "alert" => $title,
                    "sound" => "soundnote.aiff",
                ],
                "apns" => [
                    "payload" => [
                        "aps" => [
                            "alert" => $title,
                            "sound" => "sound.caf",
                        ],
                    ],
                ],

            ];
            $encodedData = json_encode($data);

            $headers = [
                'Authorization:key=' . $serverKey,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
            // Execute post
            $result = curl_exec($ch);
            if ($result === false) {
                // die('Curl failed: ' . curl_error($ch));
                return false;
            }
            // Close connection
            curl_close($ch);
            // FCM response
            return $result;
            return true;

        } catch (\Exception$ex) {
            return false;
            // return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }

    public function upload_image($file, $destination)
    {
        return \App\Helpers\UploadFilesHelper::upload_image($file, $destination);

    }
    public function store_file($options=[])
    {
        $options = array_merge([
            //'source'=>"",
            'validation'=>"file",
            'path_to_save'=>'/uploads/files/',
            'type'=>'',
            'type_id'=>"",
            'user_id'=>NULL,
            'resize'=>[400,15000],
            'small_path'=>'small/',
            'visibility'=>'PUBLIC',
            'file_system_type'=>env('FILESYSTEM_DRIVER','s3'),
            'optimize'=>false,
            'new_extension'=>"",
            'used_at'=>NULL,
        ],$options);
        return \App\Helpers\UploadFilesHelper::store_file($options);

    }

    public function remove_hub_file($name)
    {
        return \App\Helpers\UploadFilesHelper::remove_hub_file($name);
    }
    public function use_hub_file($name, $type_id, $user_id = null, $is_main = 0)
    {
        return \App\Helpers\UploadFilesHelper::use_hub_file($name, $type_id, $user_id , $is_main);
    }

    public function show_file()
    {
        $path =  public_path(env("STORAGE_URL").request()->url);

        if(\File::exists($path)){
            return response()->file($path);
        }else{
            toastr()->success('لم يتم التمكن من عرض الملف فشل!','عملية فاشلة');
            return back();
        }
    }
    public function download_file()
    {
        $path =  public_path(env("STORAGE_URL").request()->url);

        if(\File::exists($path)){
            return response()->download($path);
        }else{
            toastr()->success('لم يتم التمكن من عرض الملف فشل!','عملية فاشلة');
            return back();
        }
    }

    public function delete_file($folder ,$file)
    {
        if( \File::exists(public_path(env("STORAGE_URL").'/uploads//'.$folder.'//'.$file))){
            \File::delete(public_path(env("STORAGE_URL").'/uploads//'.$folder.'//'.$file));
            if( \File::exists(public_path(env("STORAGE_URL").'/uploads//'.$folder.'//small//'.$file))){
                \File::delete(public_path(env("STORAGE_URL").'/uploads//'.$folder.'//small//'.$file));
            }
        }
        $this->remove_hub_file($file);
    }
}
