<?php

/*
 * 文档排序 Logic
 *
 * @Created: 2020-06-27 17:33:42
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryDoc;

use app\constants\common\LibraryOperateCode;
use app\entity\model\YLibraryDocEntity;
use app\exception\AppException;
use app\extend\library\LibraryOperateLog;
use app\kernel\model\YLibraryDocGroupModel;
use app\kernel\model\YLibraryDocModel;
use app\logic\extend\BaseLogic;
use app\service\library\LibraryDocService;

class LibraryDocSortLogic extends BaseLogic {

    /**
     * 文档实体信息
     *
     * @var YLibraryDocEntity
     */
    protected $libraryDocEntity;

    /**
     * 使用文档
     *
     * @param int $libraryId 文档库id
     * @param int $libraryDocId 文档id
     * @return $this
     * @throws AppException
     */
    public function useLibraryDoc($libraryId, $libraryDocId) {
        $libraryDocEntity = YLibraryDocEntity::make(['id' => $libraryDocId, 'library_id' => $libraryId]);

        // TODO: 文档变更上级分组时，需要重新计算sort值

        $this->libraryDocEntity = $libraryDocEntity;

        return $this;
    }

    /**
     * 指定上级分组、排序
     *
     * @param int $sort 分组排序值
     * @param int $parentGroupId 上级文档分组id
     * @return $this
     * @throws AppException
     */
    public function useOption($sort, $parentGroupId = -1) {
        $this->libraryDocEntity->sort = $sort;

        if ($parentGroupId >= 0) {
            if ($parentGroupId == 0) {
                $parentGroupId = 0;
            } else if (!YLibraryDocGroupModel::existsOne(['id' => $parentGroupId, 'library_id' => $this->libraryDocEntity->library_id])) {
                throw new AppException('上级文档分组不存在');
            }
            $this->libraryDocEntity->group_id = $parentGroupId;
        }

        return $this;
    }

    /**
     * 排序文档分组
     *
     * @return $this
     * @throws AppException
     */
    public function sort() {
        $libraryDocEntity = $this->libraryDocEntity;
        $libraryDocEntity->update_time = time();

        $updateRes = YLibraryDocModel::update($libraryDocEntity->toArray(), ['id' => $libraryDocEntity->id], 'group_id,sort,update_time');
        if (empty($updateRes)) {
            throw new AppException('修改失败，请重试');
        }

        // 文档库操作日志
        $docInfo = LibraryDocService::getLibraryDocInfo($libraryDocEntity->id, 'library_id,title');
        LibraryOperateLog::record(
            $docInfo['library_id'], LibraryOperateCode::LIBRARY_DOC_MODIFY, '文档：' . $docInfo['title'], $docInfo
        );

        return $this;
    }

}