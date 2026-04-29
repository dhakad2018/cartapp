<?php

class Common_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    } 
  public function insertData($tbl_name = false, $data_array = false)
    {
        $ins_data = $this->db->insert($tbl_name, $data_array);
        if($ins_data){
            return $last_id = $this->db->insert_id();
        }
        else{
            return false;
        }
    }

    public function updateData($table,$data,$where_array)
    { 
        $this->db->where($where_array);
        if($this->db->update($table,$data)){
            return true;
        }
        else{
            return false;
        }
    }

    public function getData($table,$where='', $order_by = false, $order = false, $join_array = false, $limit = false)
    {
        $this->db->from($table);

        if(!empty($where))
        {
            $this->db->where($where);
        }
        if(!empty($order_by))
        {
            $this->db->order_by($order_by, $order);     
        }



        if(!empty($join_array))
        {
            foreach ($join_array as $key => $value) {

                $this->db->join($key, $value);  
            }
            
        }

        if(!empty($limit))
        {
            $this->db->limit($limit);   
        }
        $result = $this->db->get();
               
        return $result->result();
        
    }

    public function getDataField($table, $field = '*', $where='', $order_by = false, $order = false, $join_array = false, $limit = false, $join_type = false )
    {
        $this->db->select($field);
        $this->db->from($table);

        if(!empty($where))
        {
            $this->db->where($where);
        }
        
        if(!empty($order_by))
        {
            $this->db->order_by($order_by, $order);     
        }
        if(!empty($join_array))
        {
            foreach ($join_array as $key => $value) {
                if(!empty($join_type))
                    $this->db->join($key, $value, 'left');
                else
                    $this->db->join($key, $value);  
               }
            
        }
        if(!empty($limit))
        {
            $this->db->limit($limit);   
        }

        $result = $this->db->get();
        return $result->result();
    }

    public function getRowData($tbl_name = false, $where = false, $join_array = false)
    {
        $this->db->select('*');
        $this->db->from($tbl_name);
        
        if(isset($where) && !empty($where))
        {
            $this->db->where($where);   
        }
        if(!empty($join_array))
        {
            foreach($join_array as $key=>$value){
                $this->db->join($key,$value);
            }   
        }
        $query = $this->db->get();
        $data_array = $query->row();

        if($data_array)
        {
            return $data_array;
        }
        else{
            return false;
        }
    }

            public function userAuthentication($post)
                {
                    $query = $this->db->where('email', $post['email'])->get('login');
                    $result = $query->row();

                    if (empty($result)) {
                            return 'Email address not registered';
                    }

                    $pwd = $result->password;
                    // decrypt stored password if encryption library used
                    $old_pwd = $this->encryption->decrypt($pwd);

                    if ($old_pwd !== $post['password']) {
                            return 'Invalid password';
                    } elseif ($result->user_status != 1) {
                            return 'Your account is not active';
                    } else {
                            $uid = $result->uid;
                            if ($this->loginTime($uid)) {
                                    return $result;
                            }
                    }
                }
        public function loginTime($uid)
          {
              $this->db->where('uid', $uid);
              return $this->db->update('login', array('user_last_login' => date('Y-m-d H:i:s')));
          }
      public function updateToken($uid)
        {   
            $token = 0; 
            $modified_at=date('Y-m-d h:i:s');
            $verifiedData = array('activation_code'=>$token,'password'=>' ','modified_at'=>$modified_at);
            $this->db->where(array('uid'=>$uid));
            $this->db->update('login',$verifiedData);
            return $uid;
        } 

    public function get_selected_rows($conditions) {
    $this->db->select($conditions);
    $this->db->from('login'); // Replace with your table name
    $query = $this->db->get();
    return $query->result_array(); // Returns an array
}   

}