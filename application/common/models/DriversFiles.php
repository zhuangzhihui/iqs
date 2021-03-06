<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/16
 * Time: 16:28
 */

namespace app\common\models;

use app\admin\validate\DriverFiles as FileValidate;
use Aws\Exception\AwsException;
use think\Exception;
use app\common\helper\Aws;

/**
 * Class download
 * @package app\common\models
 */
class DriversFiles extends Base
{
    protected $table = 'tb_driver_files';
    protected $success = "保存成功！";
    protected $failed = "保存失败！";
    protected $url = '/wavlink/driver/list.html';

    public function file()
    {
        return $this->belongsTo('Drivers');
    }

    /**
     * 保存下载地址的方法
     * @param $data
     * @return false|string
     */
    public function saveData($data)
    {
        $validate = new FileValidate();
        if (isset($data['id'])) {
            if ($validate->scene('edit')->check($data)) {
                try {
                    return self::allowField(true)->save($data, ['id' => $data['id']]) ?
                        show(true, $this->success, $this->url) : show(false, $this->failed, $this->url);
                } catch (Exception $exception) {
                    return show(false, $exception->getMessage(), '');
                }
            } else {
                return show(false, $validate->getError(), '');
            }
        } else {
            if ($validate->scene('add')->check($data)) {
                return self::allowField(true)->save($data) ?
                    show(true, $this->success, $this->url) : show(false, $this->failed, $this->url);
            } else {
                return show(false, $validate->getError(), '');
            }
        }
    }

    /***
     *
     * 通过file_id获取该属于该文件的所有的下载项
     * @param $id
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function getDataByFileId($id)
    {
        try {
            return self::where(['file_id' => $id])->order(['listorder' => 'desc', 'id' => 'asc'])->select();
        } catch (Exception $exception) {
            //todo:: 异常
        }
    }

    /***
     * 通过单个下载项的ID获得本条数据用于编辑或者其他操作
     * @param $id
     * @return mixed
     */
    public static function getDataById($id)
    {
        try{
            return self::get($id);
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }

    /***
     * 删除下载链接的文件，同时删除aws桶里的数据（key）
     * 用到的知识点：try {} catche(){} /调用 AWS(自己写的helper) 里的 deleteKey()
     * @param $id
     * @return false|string
     */
    public function deleteById($id)
    {
//        $data=self::get($id);
        try {
            return self::destroy($id);
        } catch (Exception $exception){
            return show(false,$exception->getMessage());
        }
    }
}