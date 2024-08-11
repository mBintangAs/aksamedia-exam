<?php
namespace App\Http\Response;

class BaseResponse
{
    public static function success($message, $data)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ]);
    }
    public static function successWithPagination($message, $data,$pagination)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'pagination' => $pagination,
        ]);
    }
    public static function actionSuccess($message)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
        ]);
    }

    public static function error($message, $statusCode = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $statusCode);
    }
}
