<?php namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table      = 'departments';
    protected $primaryKey = 'id';

    // return rows as arrays (more convenient across your codebase)
    protected $returnType = 'array';

    // allow these fields to be inserted/updated via $model->save()/update()
    protected $allowedFields = [
        'faculty_id',
        'name',
        'code',
        'created_at',
        'updated_at',
    ];

    // automatic timestamps (will populate created_at, updated_at)
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // soft deletes not enabled by default; enable if you want soft-delete behavior
    protected $useSoftDeletes = false;
    protected $deletedField  = 'deleted_at';

    // (optional) basic validation rules you can enable/use in controllers
    protected $validationRules = [
        'faculty_id' => 'required|is_natural_no_zero',
        'name'       => 'required|min_length[2]|max_length[255]',
        'code'       => 'permit_empty|alpha_numeric_punct|max_length[32]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Get departments for a faculty (ordered by name).
     *
     * @param int $facultyId
     * @return array
     */
    public function getByFaculty(int $facultyId): array
    {
        return $this->where('faculty_id', $facultyId)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Return an id=>name array suitable for select inputs.
     * If $facultyId provided, filters by it.
     *
     * @param int|null $facultyId
     * @return array
     */
    public function getForSelect(?int $facultyId = null): array
    {
        $builder = $this;
        if (! is_null($facultyId)) {
            $builder = $builder->where('faculty_id', $facultyId);
        }

        $rows = $builder->orderBy('name', 'ASC')->findAll();
        $out = [];
        foreach ($rows as $r) {
            $out[$r['id']] = $r['name'];
        }
        return $out;
    }
}
