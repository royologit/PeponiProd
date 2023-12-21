<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Table_Tnc extends CI_Migration
{
    protected $table = 'tnc';
    protected $fields = [
        'tnc_id'      => [
            'type'           => 'INT',
            'constraint'     => '11',
            'unsigned'       => true,
            'auto_increment' => true
        ],
        'tnc_content'    => [
            'type'       => 'TEXT',
            'null'       => false
        ],
        'created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP',
        'updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
    ];

    public function up()
    {
        echo "====:: Creating Table {$this->table} ::===\n";

        $this->dbforge->add_field($this->fields);
        $this->dbforge->add_key('tnc_id', true);
        $this->dbforge->create_table($this->table, true);

        foreach ($this->fields as $key => $config) {
            if (is_array($config) && $key !== 'tnc_id' && $config['type'] != 'TEXT') {
                $this->db->query(create_index_query($this->table, $key, [$key]));
            }
        }

        $this->db->query(create_index_query($this->table, 'created_at_INDEX', ['created_at']));
        $this->db->query(create_index_query($this->table, 'updated_at_INDEX', ['updated_at']));

        echo ":: Table {$this->table} Created ::\n\n";
    }

    public function down()
    {
        echo "====:: Dropping Back Table {$this->table} ::===\n";

        $this->dbforge->drop_table($this->table);

        echo ":: Table {$this->table} Dropped ::\n\n";
    }
}
