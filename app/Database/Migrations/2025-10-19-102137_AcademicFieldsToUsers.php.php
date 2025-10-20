<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAcademicFieldsToUsers extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        if (! $db->tableExists('users')) {
            throw new \RuntimeException('users table not found. Create it first.');
        }

        $existing = $db->getFieldNames('users');
        $cols = [];

        $fields = [
            'academic_rank' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true,'default'=>null,'after'=>'designation'],
            'date_of_first_appointment' => ['type'=>'DATE','null'=>true,'default'=>null,'after'=>'academic_rank'],
            'current_appointment_date' => ['type'=>'DATE','null'=>true,'default'=>null,'after'=>'date_of_first_appointment'],
            'current_appointment_grade' => ['type'=>'VARCHAR','constraint'=>100,'null'=>true,'default'=>null,'after'=>'current_appointment_date'],
            'courses_taught' => ['type'=>'TEXT','null'=>true,'default'=>null,'after'=>'current_appointment_grade'],
            'teaching_load' => ['type'=>'INT','constraint'=>11,'null'=>true,'default'=>null,'after'=>'courses_taught'],
            'research_areas' => ['type'=>'TEXT','null'=>true,'default'=>null,'after'=>'teaching_load'],
            'supervisions_count' => ['type'=>'INT','constraint'=>11,'null'=>true,'default'=>null,'after'=>'research_areas'],
            'postgraduate_supervisor' => ['type'=>'VARCHAR','constraint'=>5,'null'=>true,'default'=>null,'after'=>'supervisions_count'],
            'grants' => ['type'=>'TEXT','null'=>true,'default'=>null,'after'=>'postgraduate_supervisor'],
            'publications' => ['type'=>'TEXT','null'=>true,'default'=>null,'after'=>'grants'],
            'dissertation' => ['type'=>'TEXT','null'=>true,'default'=>null,'after'=>'publications'],
            'articles' => ['type'=>'TEXT','null'=>true,'default'=>null,'after'=>'dissertation'],
            'books_monographs' => ['type'=>'TEXT','null'=>true,'default'=>null,'after'=>'articles'],
            'number_pub_accepted' => ['type'=>'INT','constraint'=>11,'null'=>true,'default'=>null,'after'=>'books_monographs'],
            'number_of_points' => ['type'=>'INT','constraint'=>11,'null'=>true,'default'=>null,'after'=>'number_pub_accepted'],
            'conference_taken' => ['type'=>'TEXT','null'=>true,'default'=>null,'after'=>'number_of_points'],
            'contributions_to_knowledge' => ['type'=>'TEXT','null'=>true,'default'=>null,'after'=>'conference_taken'],
            'professional_experience' => ['type'=>'TEXT','null'=>true,'default'=>null,'after'=>'contributions_to_knowledge'],
            'participation' => ['type'=>'TEXT','null'=>true,'default'=>null,'after'=>'professional_experience'],
            'completed_profile' => ['type'=>'TINYINT','constraint'=>1,'null'=>false,'default'=>0,'after'=>'participation'],
            'completed_at' => ['type'=>'DATETIME','null'=>true,'default'=>null,'after'=>'completed_profile'],
        ];

        foreach ($fields as $name => $spec) {
            if (! in_array($name, $existing)) $cols[$name] = $spec;
        }

        if (! empty($cols)) {
            $this->forge->addColumn('users', $cols);
            log_message('info','AddAcademicFieldsToUsers added: '.implode(',', array_keys($cols)));
        } else {
            log_message('info','AddAcademicFieldsToUsers: nothing to add');
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        if (! $db->tableExists('users')) return;
        $existing = $db->getFieldNames('users');
        $drop = [];
        foreach ([
            'academic_rank','date_of_first_appointment','current_appointment_date','current_appointment_grade',
            'courses_taught','teaching_load','research_areas','supervisions_count','postgraduate_supervisor','grants',
            'publications','dissertation','articles','books_monographs','number_pub_accepted','number_of_points',
            'conference_taken','contributions_to_knowledge','professional_experience','participation','completed_profile','completed_at'
        ] as $c) {
            if (in_array($c, $existing)) $drop[] = $c;
        }
        if (! empty($drop)) {
            $this->forge->dropColumn('users', $drop);
            log_message('info','AddAcademicFieldsToUsers down: dropped '.implode(',',$drop));
        }
    }
}
