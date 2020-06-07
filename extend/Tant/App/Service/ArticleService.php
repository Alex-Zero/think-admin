<?php

declare(strict_types=1);

/*
 * This file is part of TAnt.
 * @link     https://github.com/edenleung/think-admin
 * @document https://www.kancloud.cn/manual/thinkphp6_0
 * @contact  QQ Group 996887666
 * @author   Eden Leung 758861884@qq.com
 * @copyright 2019 Eden Leung
 * @license  https://github.com/edenleung/think-admin/blob/6.0/LICENSE.txt
 */

namespace TAnt\App\Service;

use app\BaseService;
use TAnt\App\Model\Article;
use TAnt\Contract\InterfaceService;

class ArticleService extends BaseService implements InterfaceService
{
    public function __construct(Article $model)
    {
        $this->model = $model;
    }

    public function list(int $pageNo, int $pageSize, array $params = [])
    {
        $query = $this->model->alias('a')
            ->join('article_category c', 'a.category_id = c.id')
            ->order('top desc, a.id desc')
            ->field('a.id, a.image, a.title, a.top, a.sort, a.create_time, a.update_time, c.name as category_name');

        if (!empty($params['cid'])) {
            $query->where('c.id', $params['cid']);
        }

        $data = $query->paginate([
            'list_rows' => $pageSize,
            'page'      => $pageNo,
        ]);

        return [
            'data'       => $data->items(),
            'pageSize'   => $pageSize,
            'pageNo'     => $pageNo,
            'totalPage'  => count($data->items()),
            'totalCount' => $data->total(),
        ];
    }

    public function create(array $data)
    {
        return $this->model->save($data);
    }

    public function update($id, array $data)
    {
        return $this->model->find($id)->save($data);
    }

    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    public function info($id)
    {
        return $this->model->alias('a')
            ->join('article_category c', 'c.id = a.category_id')
            ->field('a.*, c.name as category_name')
            ->find($id);
    }
}
