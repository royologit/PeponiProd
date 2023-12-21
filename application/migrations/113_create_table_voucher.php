<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Table_Voucher extends CI_Migration
{
    protected $table = 'voucher';
    protected $fields = [
        'voucher_id' => [
            'type'           => 'INT',
            'constraint'     => '11',
            'unsigned'       => true,
            'auto_increment' => true
        ],
        'voucher_code' => [
            'type'       => 'VARCHAR',
            'constraint' => '45',
            'null'       => false,
        ],
        'voucher_amount' => [
            'type'       => 'INT',
            'constraint' => '11',
            'null'       => false,
            'unsigned'   => true
        ],
        'voucher_quota' => [
            'type'       => 'INT',
            'constraint' => '11',
            'null'       => true,
            'unsigned'   => true,
        ],
        'voucher_expiration_date' => [
            'type'  => 'DATETIME',
            'null'  => true,
        ],
        'voucher_deactivated_at' => [
            'type'  => 'DATETIME',
            'null'  => true,
        ],
        'created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP',
        'updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
    ];

    public function up()
    {
        echo "====:: Creating Table {$this->table} ::===\n";

        $this->dbforge->add_field($this->fields);
        $this->dbforge->add_key('voucher_id', true);
        $this->dbforge->create_table($this->table, true);

        foreach ($this->fields as $key => $config) {
            if (is_array($config) && $key !== 'voucher_id') {
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
