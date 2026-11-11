
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_am_id_columns extends CI_Migration
{
    public function up()
    {
        //Employee Master
        $this->dbforge->add_column('employee_master', [
            'emAmId' => [
                'type' => 'INT',
                'null' => TRUE,
                'default' => NULL
            ]
        ]);

        // Student Master
        $this->dbforge->add_column('students_master', [
            'smAmId' => [
                'type' => 'INT',
                'null' => TRUE,
                'default' => NULL
            ]
        ]);

        // Exam Master
        $this->dbforge->add_column('exam_master', [
            'emAmId' => [
                'type' => 'INT',
                'null' => TRUE,
                'default' => NULL
            ]
        ]);

        // Exam Summary
        $this->dbforge->add_column('exam_summary', [
            'esamId' => [
                'type' => 'INT',
                'null' => TRUE,
                'default' => NULL
            ]
        ]);
        $this->dbforge->add_column('academic_master', [
            'c_status' => [
                'type' => 'INT',
                'default' => 0
            ]
        ]);

         $this->dbforge->add_column('academic_master', [
            'amActive' => [
                'type' => 'INT',
                'default' => 0
            ]
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('employee_master', 'emAmId');

        $this->dbforge->drop_column('student_master', 'smAmId');

        $this->dbforge->drop_column('exam_master', 'emAmId');

        $this->dbforge->drop_column('exam_summary', 'esamId');
        $this->dbforge->drop_column('academic_master', 'c_status');
    }
}
