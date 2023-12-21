<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order_type_model extends CI_Model
{
    public $table = 'order_type';

    public function getProductOrderTypeOption()
    {
        $orderTypes = $this->db->get($this->table)->result();

        $options    = [];
        $options[0] = 'None';
        foreach($orderTypes as $orderType) {
            $options[$orderType->order_type_id] = $orderType->order_type_name;
        }

        $options[3] = 'Both Trip';

        return $options;
    }

    public function getOrderTypeFromProductOrderType($productOrderType)
    {
        if (is_null($productOrderType)) {
            return [];
        }

        $orderTypeIds = $this->getOrderTypeIdsFromProductOrderType($productOrderType);

        if (!$orderTypeIds) {
            return [];
        }

        $this->db->where_in('order_type_id', $orderTypeIds);

        $orderTypes = $this->db->get($this->table)->result();
        $orderTypes = $this->attachOrderUrl($orderTypes);

        return $orderTypes;
    }

    public function hasOrderTypeFromProductOrderType($productOrderType, $orderTypeId)
    {
        if (!$orderTypeId || is_null($productOrderType)) {
            return [];
        }

        $orderTypeIds = $this->getOrderTypeIdsFromProductOrderType($productOrderType);

        return in_array($orderTypeId, $orderTypeIds);
    }

    private function getOrderTypeIdsFromProductOrderType($productOrderType)
    {
        switch ($productOrderType) {
            case 1:
                $orderTypeIds = [1];
                break;
            case 2:
                $orderTypeIds = [2];
                break;
            case 3:
                $orderTypeIds = [1, 2];
                break;
            default:
                $orderTypeIds = [];
        }

        return $orderTypeIds;
    }

    private function attachOrderUrl($orderTypes)
    {
        foreach($orderTypes as $orderType) {
            switch($orderType->order_type_id) {
                case 1:
                    $orderType->url = 'order-open-trip';
                    break;
                case 2:
                    $orderType->url = 'order-private-trip';
                    break;
                default:
                    $orderType->url = '';
                    break;
            }
        }

        return $orderTypes;
    }
}