<?php
class Cart_model extends CI_Model {

    public function get_products() {
        return $this->db->get('products')->result();
    }

    public function get_product($id) {
        return $this->db->where('id', $id)->get('products')->row();
    }

    public function create_order($data, $items) {
        $this->db->trans_start();

        $this->db->insert('orders', $data);
        $order_id = $this->db->insert_id();

        foreach($items as $id => $item){
            $this->db->insert('order_items', [
                'order_id' => $order_id,
                'product_id' => $id,
                'quantity' => $item['qty'],
                'price' => $item['price']
            ]);
        }

        $this->db->trans_complete();

        return $order_id;
    }
}