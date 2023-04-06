<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

trait GeneralTrait
{

    public function push_notification($tokens, $title, $body, $subtitle = "")
    {

        try {
            //$token_1 = 'e6WBgNuvz0WdoCSZxLpOKM:APA91bFYgWK6jtSrMlG0axpGzWdBz7emIRrc16MCO3Z8jW3gD2NSkGdfaF-y_3pt_k7oh8ZbFUGb0roDp4ycFidta4iGtDEfMAZPfbJTVdyqXhTXYLOnH3LUoCnCAvyvPo6qM-NtLF63';
            $url = 'https://fcm.googleapis.com/fcm/send';
            $serverKey = 'AAAAtxCDEp0:APA91bETDiR9e9oAX1y8w560tyIdZJXUS7-OR671Aqa6OcvxJChO8VlEy-Dz3a-3I8dkyVLU9iIhbjs6LK46sLQSybUxLQ4sdHp9gJYTvQYLR0-JllRr9fhXRuqxYLWQ0jVzqIHvr2mX';

            $data = [
                "registration_ids" => $tokens,
                'notification' => [
                    "title" => $title,
                    "sound" => "sound.caf",
                ],
                "data" => [
                    "type" => $body,
                    "subtitle" => $subtitle,
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

    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    public function returnError($errNum, $msg)
    {
        return response()->json([
            'success' => false,
            'errorNum' => $errNum,
            'message' => $msg
        ]);
    }


    public function returnSuccessMessage($msg = "")
    {
        return [
            'success' => true,
            // 'errorNum ' => $errNum,
            'message' => $msg
        ];
    }

    public function returnData($key, $value, $msg = "")
    {
        return response()->json([
            'success' => true,
            //'errorNum ' => "S000",
            'message' => $msg,
            $key => $value
        ]);
    }
    public function returnErrorApi($errNum, $msg)
    {
        return response()->json([
            'success' => true,
            'status'=>0,
            'message' => $msg,
            'data' =>null,
            'errorId' =>  (string)$errNum,

        ]);
    }
    public function returnErrorApiAuth()
    {
        return response()->json([
            'success' => true,
            'status'=>3,
            'message' => "unAthenticated",
            'data' => null,
            'errorId' => (string)'777777',

        ]);
    }

    public function returnSuccessMessageApi($msg = "")
    {
        return [
            'success' => true,
            'status'=>1,
            'message' => 'code send successfully',
            'data' => $msg,
            'errorId'=>''
        ];
    }

    public function returnDataApi($value, $msg = "")
    {
        return response()->json([
            'success' => true,
            'status'=>1,
            'message' => $msg,
            'data' => $value,
            'errorId'=>''
        ]);
    }


    //////////////////
    public function returnValidationError($code = "E001", $validator)
    {
        return $this->returnError($code, $validator->errors()->first());
    }


    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    public function getErrorCode($input)
    {
        if ($input == "name")
            return 'E0011';

        else if ($input == "password")
            return 'E002';

        else if ($input == "mobile")
            return 'E003';

        else if ($input == "id_number")
            return 'E004';

        else if ($input == "birth_date")
            return 'E005';

        else if ($input == "agreement")
            return 'E006';

        else if ($input == "email")
            return 'E007';

        else if ($input == "city_id")
            return 'E008';

        else if ($input == "insurance_company_id")
            return 'E009';

        else if ($input == "activation_code")
            return 'E010';

        else if ($input == "longitude")
            return 'E011';

        else if ($input == "latitude")
            return 'E012';

        else if ($input == "id")
            return 'E013';

        else if ($input == "promocode")
            return 'E014';

        else if ($input == "doctor_id")
            return 'E015';

        else if ($input == "payment_method" || $input == "payment_method_id")
            return 'E016';

        else if ($input == "day_date")
            return 'E017';

        else if ($input == "specification_id")
            return 'E018';

        else if ($input == "importance")
            return 'E019';

        else if ($input == "type")
            return 'E020';

        else if ($input == "message")
            return 'E021';

        else if ($input == "reservation_no")
            return 'E022';

        else if ($input == "reason")
            return 'E023';

        else if ($input == "branch_no")
            return 'E024';

        else if ($input == "name_en")
            return 'E025';

        else if ($input == "name_ar")
            return 'E026';

        else if ($input == "gender")
            return 'E027';

        else if ($input == "nickname_en")
            return 'E028';

        else if ($input == "nickname_ar")
            return 'E029';

        else if ($input == "rate")
            return 'E030';

        else if ($input == "price")
            return 'E031';

        else if ($input == "information_en")
            return 'E032';

        else if ($input == "information_ar")
            return 'E033';

        else if ($input == "street")
            return 'E034';

        else if ($input == "branch_id")
            return 'E035';

        else if ($input == "insurance_companies")
            return 'E036';

        else if ($input == "photo")
            return 'E037';

        else if ($input == "logo")
            return 'E038';

        else if ($input == "working_days")
            return 'E039';

        else if ($input == "insurance_companies")
            return 'E040';

        else if ($input == "reservation_period")
            return 'E041';

        else if ($input == "nationality_id")
            return 'E042';

        else if ($input == "commercial_no")
            return 'E043';

        else if ($input == "nickname_id")
            return 'E044';

        else if ($input == "reservation_id")
            return 'E045';

        else if ($input == "attachments")
            return 'E046';

        else if ($input == "summary")
            return 'E047';

        else if ($input == "user_id")
            return 'E048';

        else if ($input == "mobile_id")
            return 'E049';

        else if ($input == "paid")
            return 'E050';

        else if ($input == "use_insurance")
            return 'E051';

        else if ($input == "doctor_rate")
            return 'E052';

        else if ($input == "provider_rate")
            return 'E053';

        else if ($input == "message_id")
            return 'E054';

        else if ($input == "hide")
            return 'E055';

        else if ($input == "checkoutId")
            return 'E056';

        else
            return "";
    }


}

