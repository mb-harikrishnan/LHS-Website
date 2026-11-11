<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Fees whose amount varies per student (e.g. conveyance by stop / distance)
 *  - fee_master.fmAmountType      FIXED | VARIABLE
 *  - fee_master_rate              rate list of a VARIABLE fee (stop / distance slab + amount)
 *  - student_fee_allocation.sfaFmrId  rate chosen for the student (NULL = amount typed manually)
 */
class Migration_Fee_variable_rates extends CI_Migration
{
    public function up()
    {
        if (!$this->db->field_exists('fmAmountType', 'fee_master')) {
            $this->db->query("ALTER TABLE fee_master ADD COLUMN fmAmountType VARCHAR(10) NOT NULL DEFAULT 'FIXED' AFTER fmAmount");
        }

        $this->db->query("CREATE TABLE IF NOT EXISTS fee_master_rate (
            fmrId     INT NOT NULL AUTO_INCREMENT,
            fmrFmId   INT NOT NULL,
            fmrName   VARCHAR(100) NOT NULL,
            fmrAmount DECIMAL(10,2) NOT NULL DEFAULT 0,
            fmrOrder  INT NOT NULL DEFAULT 0,
            PRIMARY KEY (fmrId),
            KEY ix_fmr_fee (fmrFmId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        if (!$this->db->field_exists('sfaFmrId', 'student_fee_allocation')) {
            $this->db->query("ALTER TABLE student_fee_allocation ADD COLUMN sfaFmrId INT NULL DEFAULT NULL AFTER sfaAmount");
        }
    }

    public function down()
    {
        if ($this->db->field_exists('sfaFmrId', 'student_fee_allocation')) {
            $this->dbforge->drop_column('student_fee_allocation', 'sfaFmrId');
        }
        $this->db->query("DROP TABLE IF EXISTS fee_master_rate");
        if ($this->db->field_exists('fmAmountType', 'fee_master')) {
            $this->dbforge->drop_column('fee_master', 'fmAmountType');
        }
    }
}
