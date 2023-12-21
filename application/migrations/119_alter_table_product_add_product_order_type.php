<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Table_Product_Add_Product_Order_Type extends CI_Migration
{
    protected $table = 'product';

    public function up()
    {
        echo "====:: Altering Table {$this->table} ::===\n";

        $this->dbforge->add_column($this->table, [
            'product_order_type' => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => true,
                'null'       => false,
                'after'      => 'product_cover_image'
            ],
            'product_highlight' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'product_name'
            ]
        ]);
        $this->db->query(create_index_query($this->table, 'product_order_type', ['product_order_type']));

        echo ":: Table {$this->table} Done::\n\n";
    }

    public function down()
    {
        echo "====:: Rolling Back Table {$this->table} ::===\n";

        $this->dbforge->drop_column($this->table, 'product_order_type');
        $this->dbforge->drop_column($this->table, 'product_highlight');

        echo ":: Table {$this->table} Rolled Back::\n\n";
    }
}