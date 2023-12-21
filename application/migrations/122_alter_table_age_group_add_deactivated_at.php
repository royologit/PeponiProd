<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Table_Age_Group_Add_Deactivated_At extends CI_Migration
{
    protected $table = 'age_group';

    public function up()
    {
        echo "====:: Altering Table {$this->table} ::===\n";

        $this->dbforge->add_column($this->table, [
            'age_group_deactivated_at' => [
                'type'  => 'DATETIME',
                'null'  => true,
            ]
        ]);
        $this->db->query(create_index_query($this->table, 'age_group_deactivated_at', ['age_group_deactivated_at']));

        echo ":: Table {$this->table} Done::\n\n";
    }

    public function down()
    {
        echo "====:: Rolling Back Table {$this->table} ::===\n";

        $this->dbforge->drop_column($this->table, 'age_group_deactivated_at');

        echo ":: Table {$this->table} Rolled Back::\n\n";
    }
}
