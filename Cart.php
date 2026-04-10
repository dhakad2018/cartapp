<?php
class Cart extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Cart_model');
        $this->load->library(['session','form_validation']);
        $this->load->helper(['url','cart']);
    }

    public function index(){
        $data['products'] = $this->Cart_model->get_products();
        $this->load->view('layout/header');
        $this->load->view('home', $data);
        $this->load->view('layout/footer');
    }

    public function cart(){
        $this->load->view('layout/header');
        $this->load->view('cart');
        $this->load->view('layout/footer');
    }

    // AJAX: Add to Cart
    public function add(){
        $id = $this->input->post('id', TRUE);
        $product = $this->Cart_model->get_product($id);

        if(!$product){
            echo json_encode(['status'=>false]);
            return;
        }

        $cart = $this->session->userdata('cart') ?? [];

        if(isset($cart[$id])){
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                'name'=>$product->name,
                'price'=>$product->price,
                'qty'=>1
            ];
        }

        $this->session->set_userdata('cart', $cart);

        echo json_encode(['status'=>true,'count'=>count($cart)]);
    }

    // Update Quantity
    public function update(){
        $id = $this->input->post('id');
        $type = $this->input->post('type');

        $cart = $this->session->userdata('cart');

        if($type == 'inc') $cart[$id]['qty']++;
        if($type == 'dec' && $cart[$id]['qty'] > 1) $cart[$id]['qty']--;

        $this->session->set_userdata('cart', $cart);

        echo json_encode(['status'=>true]);
    }

    public function remove(){
        $id = $this->input->post('id');
        $cart = $this->session->userdata('cart');
        unset($cart[$id]);
        $this->session->set_userdata('cart', $cart);

        echo json_encode(['status'=>true]);
    }

    // Checkout
    public function checkout(){

        $this->form_validation->set_rules('name','Name','required');
        $this->form_validation->set_rules('mobile','Mobile','required|numeric');
        $this->form_validation->set_rules('email','Email','required|valid_email');

        if($this->form_validation->run() == FALSE){
            echo json_encode(['status'=>false,'errors'=>validation_errors()]);
            return;
        }

        $cart = $this->session->userdata('cart');
        if(empty($cart)){
            echo json_encode(['status'=>false,'msg'=>'Cart Empty']);
            return;
        }

        $order = [
            'name'=>$this->input->post('name', TRUE),
            'mobile'=>$this->input->post('mobile', TRUE),
            'email'=>$this->input->post('email', TRUE),
            'total'=>cart_total($cart)
        ];

        $order_id = $this->Cart_model->create_order($order,$cart);

        $this->session->unset_userdata('cart');

        echo json_encode(['status'=>true,'order_id'=>$order_id]);
    }

    public function success($id){
        $data['order_id'] = $id;
        $this->load->view('layout/header');
        $this->load->view('success',$data);
        $this->load->view('layout/footer');
    }
}