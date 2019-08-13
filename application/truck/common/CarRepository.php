<?php


namespace app\truck\common;


use app\truck\model\WarningModel;

class CarRepository
{

    public static function saveRows($resultStr, $filter = null)
    {
        $rows = self::getDataFromRawResponse($resultStr);

        // 服务器没有返回数据
        if (empty($rows))
            return 0;

        $filter = '衡阳市';
        $rows = LocationTranslator::addAddress($rows, $filter);

        $savedCount = self::saveRowsReal($rows);
        sleep(0.1);

        if (is_numeric($savedCount)) {

            if ($savedCount > 0) {
                echo "保存" . $savedCount . "条记录\n,进入下一页查询\n";
            } else {
                // 数据重复导致没有保存
                echo "保存" . $savedCount . "条记录\n";
            }
            return $savedCount;
        } else {
            echo $savedCount;
            return 0;
        }
    }

    /**
     * @param $resultStr
     * @return mixed
     */
    private static function getDataFromRawResponse($resultStr)
    {
        $result = json_decode($resultStr, true);

        $rows = $result['Rows'];
        return $rows;
    }


    /**
     * @param array $rows
     * @return int 本次存入数据库的记录数
     */
    public static function saveRowsReal(array $rows)
    {
        $model = new WarningModel();
        $savedCount = $model->add($rows);
        return $savedCount;
    }

}