<?php

namespace Wipzhu\MyPhpTools;

class Tools
{
    /**
     * @desc: helloWorld
     * @user: wipzhu
     * @datetime: 2023/08/31 15:02
     * @return void
     */
    public static function helloWorld()
    {
        echo "Hello World !" . PHP_EOL;
    }

    /**
     * @desc: checkIdNumber
     * @user: wipzhu
     * @datetime: 2023/08/31 15:02
     * @param $inputNo
     * @return bool
     */
    public static function checkIdNumber($inputNo)
    {
        //将本体码各位数字乘以对应加权因子并求和，除以11得到余数，根据余数通过校验码对照表查得校验码。
//        $inputNo = '412726199411074912';
        if (strlen($inputNo) != 18) {
            return false;
        }
        $bodyNo = substr($inputNo, 0, -1);
        $tailNo = substr($inputNo, strlen($inputNo) - 1, 1);

        // 加权因子
        $weightFactor = [
            0 => '7', 1 => '9', 2 => '10', 3 => '5',
            4 => '8', 5 => '4', 6 => '2', 7 => '1',
            8 => '6', 9 => '3', 10 => '7', 11 => '9',
            12 => '10', 13 => '5', 14 => '8', 15 => '4',
            16 => '2',
        ];
        // 校验码
        $checkArr = [
            0 => '1', 1 => '0', 2 => 'x', 3 => '9',
            4 => '8', 5 => '7', 6 => '6', 7 => '5',
            8 => '4', 9 => '3', 10 => '2'
        ];

        $sum = 0;
        foreach (str_split($bodyNo) as $index => $num) {
            $sum += $num * $weightFactor[$index];
        }
        $mod = $sum % 11;
        $checkCode = $checkArr[$mod];
        $idCardNo = $bodyNo . $checkCode;
        if ($tailNo == $checkCode) {
            return true;
        } else {
            return false;
        }
    }
}