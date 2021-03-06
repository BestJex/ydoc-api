<?php

/*
 * 文档库分组相关 Controller
 *
 * @Created: 2020-06-20 14:20:50
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\library;

use app\entity\model\YLibraryGroupEntity;
use app\kernel\base\AppBaseController;
use app\logic\libraryGroup\LibraryGroupCreateLogic;
use app\logic\libraryGroup\LibraryGroupModifyLogic;
use app\logic\libraryGroup\LibraryGroupRemoveLogic;
use app\service\library\LibraryGroupService;
use think\Db;

class LibraryGroupController extends AppBaseController {

    protected $middleware = [
        \app\kernel\middleware\library\LibraryGroupAuthMiddleware::class => [ // 文档库分组操作鉴权
            'only' => [
                'libraryGroupInfo', 'libraryGroupRemove', 'libraryGroupModify', 'libraryGroupSort',
            ],
        ],
    ];

    /**
     * 获取用户文档库分组集合
     */
    public function libraryGroupCollection() {
        $collection = LibraryGroupService::getLibraryGroupCollection($this->uid, 'id,name,desc,sort');
        return $this->responseData($collection);
    }

    /**
     * 获取文档库分组信息
     */
    public function libraryGroupInfo() {
        $groupId = $this->request->libraryGroupId;
        $groupInfo = LibraryGroupService::getLibraryGroupInfo($groupId, 'id,uid,name,desc,sort,create_time');
        return $this->responseData($groupInfo->toArray());
    }

    /**
     * 创建文档库分组
     */
    public function libraryGroupCreate() {
        // 准备文档库分组实体信息
        $groupEntity = YLibraryGroupEntity::inputMake(['name/s' => '', 'desc/s' => '', 'sort/d' => 0]);
        $groupEntity->uid = $this->uid;

        $groupCreate = LibraryGroupCreateLogic::make();
        Db::transaction(function () use ($groupCreate, $groupEntity) {
            $groupCreate->useLibraryGroup($groupEntity)->create();
        });

        return $this->responseData($groupCreate->libraryGroupEntity->toArray());
    }

    /**
     * 删除文档库分组
     */
    public function libraryGroupRemove() {
        $libraryGroupId = $this->request->libraryGroupId;

        $groupRemove = LibraryGroupRemoveLogic::make();
        Db::transaction(function () use ($groupRemove, $libraryGroupId) {
            $groupRemove->useLibraryGroup($libraryGroupId)->remove();
        });

        return $this->responseSuccess('删除成功');
    }

    /**
     * 修改文档库分组
     */
    public function libraryGroupModify() {
        // 准备待修改的文档库分组信息
        $groupEntity = YLibraryGroupEntity::inputMake(['name/s' => '', 'desc/s' => '', 'sort/f' => 0]);
        $groupEntity->id = $this->request->libraryGroupId;

        $groupModify = LibraryGroupModifyLogic::make();
        Db::transaction(function () use ($groupModify, $groupEntity) {
            $groupModify->useLibraryGroup($groupEntity)->modify();
        });

        return $this->responseData($groupModify->libraryGroupEntity->toArray());
    }

    /**
     * 排序文档库分组
     */
    public function libraryGroupSort() {
        $sort = $this->input('sort/f', 0);

        $sortRes = LibraryGroupService::modifyLibraryGroupSort($this->request->libraryGroupId, $sort);
        if (empty($sortRes)) {
            return $this->responseError('排序失败，请重试');
        }

        return $this->responseSuccess('排序成功');
    }

}