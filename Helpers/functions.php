<?php

function responseToJson($code = 0, $msg = '', $paras = null)
{
    $res["code"] = $code;
    $res["msg"] = $msg;
    $res["result"] = $paras;
    return response()->json($res);
}

function get_week(){
        $dayNumber = date('W') * 7;
        $weekDayNumber = date("w", mktime(0, 0, 0, 1, $dayNumber, date("Y")));//当前周的第几天
        $startNumber = $dayNumber - $weekDayNumber;
        $week = mktime(0, 0, 0, 1, $startNumber + 1, date("Y"));//开始日期
        return $week;
}

function time_diff($diff){
    if($diff==0)
        return "";
    elseif(0<$diff&&$diff<60){
        return $diff."秒";
    }elseif(60<=$diff&&$diff<3600)
        return (floor($diff/60))."分钟";
    elseif(3600<=$diff&&$diff<86400)
        return (floor($diff/3600))."小时";
}