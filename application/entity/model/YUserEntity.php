<?php

/*
 * YUserModel Entity
 *
 * @Created: 2020-06-19 17:09:05
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\entity\model;

use app\entity\extend\BaseEntity;
use app\kernel\model\YUserModel;

/**
 * @property int $id 用户uid
 * @property string $account 用户账号
 * @property string $password 用户密码
 * @property string $password_salt 用户密码盐
 * @property string $avatar 用户头像
 * @property string $email 用户邮箱
 * @property string $nickname 用户昵称
 * @property string $qq qq号
 * @property string $phone 手机号
 * @property int $status 用户状态：1正常 2禁用
 * @property int $delete_time 删除时间
 * @property int $create_time 用户注册时间
 */
class YUserEntity extends BaseEntity {

    protected $model = YUserModel::class;

}