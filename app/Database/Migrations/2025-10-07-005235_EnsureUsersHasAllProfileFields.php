<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnsureUsersHasAllProfileFields extends Migration
{
    protected $columns = [
        // basic / auth
        'fullname' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'email' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'password' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'staffid' => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],        
        'phonenumber' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
        'alternatephonenumber' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],             
        // reporting / periods
        'period_from' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'period_to' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'dob' => ['type'=>'DATE','null'=>true],
        // personal data

        // org / affiliation
        'faculty' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'department' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],



        // academic / appointment / dates
        'appointmentConfirm' => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],
        'appointmentdate' => ['type'=>'DATE','null'=>true],
        'firstappointmentdate' => ['type'=>'DATE','null'=>true],
        'firstappgrade' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
        'lastpromotiondate' => ['type'=>'DATE','null'=>true],
        'lastpromotiongrade' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
        'currentappointmentdate' => ['type'=>'DATE','null'=>true],
        'currentappointmentgrade' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
        // publications / research
        'publication' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'dissertation' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'article' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'books_monographs' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'con_to_knowledge' => ['type'=>'TEXT','null'=>true],
        'conference_taken' => ['type'=>'TEXT','null'=>true],
        // unpublished papers (grouped fields)
        'unpub_paper_date1' => ['type'=>'DATE','null'=>true],
        'unpub_paper_title1' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'unpub_paper_TakenInstitution1' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'unpub_paper_duration1' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
        'unpub_paper_TakenAward1' => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],
        'unpub_paper_date2' => ['type'=>'DATE','null'=>true],
        'unpub_paper_title2' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'unpub_paper_TakenInstitution2' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'unpub_paper_duration2' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
        'unpub_paper_TakenAward2' => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],
        'unpub_paper_date3' => ['type'=>'DATE','null'=>true],
        'unpub_paper_title3' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'unpub_paper_TakenInstitution3' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'unpub_paper_duration3' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
        'unpub_paper_TakenAward3' => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],
        // alternative/unified naming (if duplicates exist)
        'unpub_paper1_tilte' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'unpub_paper1_conference' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'unpub_paper1_date' => ['type'=>'DATE','null'=>true],
        'unpub_paper2_tilte' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'unpub_paper2_conference' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'unpub_paper2_date' => ['type'=>'DATE','null'=>true],
        
        // personal data
        'date' => ['type'=>'DATE','null'=>true],
        'years_present_grade' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'number_pub_accepted' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'number_of_points' => ['type'=>'INT','constraint'=>11,'null'=>true],
        
        // professional awards & qualifications
        'prof_qualification1' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'prof_award_body1' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'prof_award_date1' => ['type'=>'DATE','null'=>true],
        'prof_qualification2' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'prof_award_body2' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'prof_award_date2' => ['type'=>'DATE','null'=>true],
        'prof_qualification3' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'prof_award_body3' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'prof_award_date3' => ['type'=>'DATE','null'=>true],
        // external experience entries (1..2)
        'exp_out_institution_name1' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'exp_out_designation1' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'exp_out_specialization1' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'exp_out_date1' => ['type'=>'DATE','null'=>true],
        'exp_out_institution_name2' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'exp_out_designation2' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'exp_out_specialization2' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'exp_out_date2' => ['type'=>'DATE','null'=>true],
        // job info
        'position_institution' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'main_job' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'adhoc_job' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'activities_within_university' => ['type'=>'TEXT','null'=>true],
        'activities_outside_university' => ['type'=>'TEXT','null'=>true],
        'professional_experience' => ['type'=>'TEXT','null'=>true],
        'conference_undertaken' => ['type'=>'TEXT','null'=>true],
        // evaluation scores/fields (many)
        'query' => ['type'=>'TEXT','null'=>true],
        'commendation' => ['type'=>'TEXT','null'=>true],
        'job_agreement' => ['type'=>'TEXT','null'=>true],
        'performance' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'work_output' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'reliability' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'competence' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'innovation' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'responsibilty' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'punctuality' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'relationship' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'criticisms' => ['type'=>'TEXT','null'=>true],
        'organizational_ability' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'foresight' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'motivation' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'self_control' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'honesty' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'loyalty' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'total' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'overall_assesment' => ['type'=>'TEXT','null'=>true],
        // promotion / serving / grading
        'normal_promotion' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'normal_promotion_grade' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
        'com_on_recommendation' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'served_under_me' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'served_under_me_grade' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
        'served_under_me_date' => ['type'=>'DATE','null'=>true],
        // teaching & research quality
        'quality_of_teaching' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'teaching_load' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'quality_of_research' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'quality_of_publication' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'postgraduate_supervisor' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'participation' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'other_remark' => ['type'=>'TEXT','null'=>true],
        'conduct_rating' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'relation_with_olleagues' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'responsility_level' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'quality_work' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'initiative' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'adaptability' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'with_minimal_supervision' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'expression_on_paper' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'oral_expression' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'personality' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'loyalty_to_authory' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'organisation_to_work' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'self_improvement' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'regularity_at_work' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'attitude_to_work' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'human_relation' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'degree_reliability' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'knowledge_of_departmental_rules' => ['type'=>'INT','constraint'=>11,'null'=>true],
        
        // qualifications (repeatable set; here up to 5)
        'qualification_subject1' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'qualification_institution1' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'qualification_grade1' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
        'qualification_date_awareded1' => ['type'=>'DATE','null'=>true],
        'qualification_subject2' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'qualification_institution2' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'qualification_grade2' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
        'qualification_awareded_date2' => ['type'=>'DATE','null'=>true],
        'qualification_subject3' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'qualification_grade3' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
        'qualification_institution3' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'qualification_date_awareded3' => ['type'=>'DATE','null'=>true],
        'qualification_subject4' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'qualification_grade4' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
        'qualification_institution4' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'qualification_date_awareded4' => ['type'=>'DATE','null'=>true],
        'qualification_subject5' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'qualification_grade5' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
        'qualification_institution5' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'qualification_date_awareded5' => ['type'=>'DATE','null'=>true],
        // other profile metadata
        'currentqualification' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'classofdegree' => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],
        'graduationyear' => ['type'=>'INT','constraint'=>11,'null'=>true],
        'reg_date' => ['type'=>'DATETIME','null'=>true],
        'last_updated' => ['type'=>'DATETIME','null'=>true],
        'contiss' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'employ_step' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'accommodationfee' => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],
        'presentsalary' => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],
        'changeinemolument' => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],
        'decision_of_a_pb' => ['type'=>'TEXT','null'=>true],
        'recommedation_of_sub_committe' => ['type'=>'TEXT','null'=>true],
        'admitted_by' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
        'admission_log' => ['type'=>'TEXT','null'=>true],
        'screened' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'staff_evaluation_comment' => ['type'=>'TEXT','null'=>true],
        'ripe_for_promotion' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'ripe_for_confirmation' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'satisfactory_perfomance' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'increment' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'recent_appointed' => ['type'=>'DATE','null'=>true],
        'qualifications_experience' => ['type'=>'TEXT','null'=>true],
        'recommeded_next_year_promotion' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'careerstricter' => ['type'=>'TEXT','null'=>true],
        'transferred_different_job' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'unsatisfactory' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'to_be_advised' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'to_be_reprimanded' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'to_lose_increment' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'grossly_unsatisfactory' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'appointment_to_terminated' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'to_be_dismissed' => ['type'=>'TINYINT','constraint'=>1,'null'=>true],
        'numlogin' => ['type'=>'INT','constraint'=>11,'null'=>true],
    ];

    public function up()
    {
        $db = \Config\Database::connect();

        if (! $db->tableExists('users')) {
            throw new \RuntimeException('users table not found. Create users first or adjust migration.');
        }

        $existing = $db->getFieldNames('users');
        $toAdd = [];
        foreach ($this->columns as $name => $def) {
            if (! in_array($name, $existing)) {
                $toAdd[$name] = $def;
            }
        }

        if (! empty($toAdd)) {
            $this->forge->addColumn('users', $toAdd);
            log_message('info', 'EnsureUsersHasAllProfileFields: added '.count($toAdd).' columns to users');
        } else {
            log_message('info', 'EnsureUsersHasAllProfileFields: no columns to add');
        }
    }

    public function down()
    {
        // intentionally left empty to avoid accidental destructive rollback.
        // If you want to remove added columns on rollback, implement carefully here.
    }
}
