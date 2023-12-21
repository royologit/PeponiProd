<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Table_Order_Detail extends CI_Migration
{
    protected $table = 'order_detail';
    protected $fields = [
        'order_detail_id' => [
            'type'           => 'INT',
            'constraint'     => '11',
            'unsigned'       => true,
            'auto_increment' => true
        ],
        'order_id' => [
            'type'       => 'INT',
            'constraint' => '11',
            'null'       => false,
            'unsigned'   => true,
        ],
        'age_group_id' => [
            'type'       => 'INT',
            'constraint' => '11',
            'null'       => false,
            'unsigned'   => true
        ],
        'order_detail_quantity' => [
            'type'       => 'INT',
            'constraint' => '11',
            'null'       => false,
        ],
        'order_detail_price' => [
            'type'       => 'INT',
            'constraint' => '11',
            'null'       => true,
        ],
        'created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP',
        'updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
    ];

    public function up()
    {
        echo "====:: Creating Table {$this->table} ::===\n";

        $this->dbforge->add_field($this->fields);
        $this->dbforge->add_key('order_detail_id', true);
        $this->dbforge->create_table($this->table, true);

        foreach ($this->fields as $key => $config) {
            if (is_array($config) && $key !== 'order_detail_id') {
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
