<?php namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'id';
    protected $allowedFields = ['parent_id','label','url','roles','categories','sort_order','is_active'];
    protected $useTimestamps = true;

    public function getTree()
    {
        $items = $this->where('is_active',1)->orderBy('sort_order','ASC')->findAll();
        $tree = [];
        foreach ($items as $it) {
            if (is_null($it['parent_id'])) {
                $it['children'] = [];
                $tree[$it['id']] = $it;
            }
        }
        foreach ($items as $it) {
            if (! is_null($it['parent_id']) && isset($tree[$it['parent_id']])) {
                $tree[$it['parent_id']]['children'][] = $it;
            }
        }
        return $tree ?: [];
    }
}
