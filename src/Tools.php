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

    /**
     * @desc: 检测是否为邮箱
     * @user: wipzhu
     * @datetime: 2023/08/31 15:28
     * @param $text
     * @return bool
     */
    public static function isEmail($text): bool
    {
        return preg_match('/^\w+([\-.]\w+)*@\w+([\-.]\w+)*$/', trim($text));
    }

    /**
     * @desc: 检测是否为手机
     * @user: wipzhu
     * @datetime: 2023/08/31 15:30
     * @param $text
     * @return bool
     */
    public static function isMobile($text): bool
    {
        return preg_match('/^1[3|4|5|6|7|8|9]\d{9}$/', trim($text));
    }

    /**
     * @desc: 给手机或邮箱加***
     * @user: wipzhu
     * @datetime: 2023/08/31 15:30
     * @param $text
     * @param string $type
     * @return bool|string
     */
    public static function replaceStar($text, string $type = 'mobile'): bool|string
    {
        if ($type == 'email' && static::isEmail($text)) {
            $input = explode('@', $text);
            $len = strlen($input[0]);
            $end = $len <= 3 ? 1 : 3;
            return substr($input[0], 0, $end) . '****@' . $input[1];
        } elseif ($type == 'mobile' && static::isMobile($text)) {
            $map['mobile'] = trim($text);
            return substr($map['mobile'], 0, 3) . "*****" . substr($map['mobile'], 7, 4);
        }
        return $text;
    }

    /**
     * @desc: 格式化输出调试信息
     * @user: wipzhu
     * @datetime: 2023/08/31 15:30
     * @param $arr
     * @return void
     */
    public static function pr($arr): void
    {
        if (is_array($arr) || is_object($arr)) {
            if (!empty($arr)) {
                echo "<pre>";
                print_r($arr);
                echo "<pre/>";
            } else {
                echo "pr数组为空" . PHP_EOL;
            }
        } else {
            echo "<pre>";
            var_dump($arr);
            echo "<pre/>";
        }
    }

    /**
     * @desc: 获取随机字符串
     * @user: wipzhu
     * @datetime: 2023/08/31 15:31
     * @param $length
     * @return string
     */
    public static function getRandomStr($length): string
    {
        //字符组合
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str) - 1;
        $randStr = '';
        for ($i = 0; $i < $length; $i++) {
            $num = mt_rand(0, $len);
            $randStr .= $str[$num];
        }
        return $randStr;
    }

    /**
     * @desc: 获取毫秒级时间戳
     * @user: wipzhu
     * @datetime: 2023/08/31 15:31
     * @return string
     */
    public static function get_millisecond(): string
    {
        // 获取微秒数时间戳
        $tempTime = explode(' ', microtime());
        // 转换成毫秒数时间戳
        return (float)sprintf('%.0f', (floatval($tempTime[0]) + floatval($tempTime[1])) * 1000);
    }

    /**
     * @desc: 导出到csv文件
     * @user: wipzhu
     * @datetime: 2023/08/31 15:31
     * @param $data
     * @param $title
     * @param $filename
     * @param string $savePath
     * @return void
     */
    public static function exportCsv($data, $title, $filename, string $savePath = '../data/exportFile/'): void
    {
        // 判断保存目录是否存在 不存在就创建
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        array_unshift($data, $title);

        $fullName = $savePath . $filename . '.csv'; //设置文件名

        header("Content-Type: text/csv;charset=utf-8");
        header("Content-Disposition: attachment;filename=\"$fullName\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        $fp = fopen($fullName, 'w');
        // 对于用 wps 和编辑器打开无乱码但是用 excel 打开出现乱码的问题,可以添加以下一行代码解决问题
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

}