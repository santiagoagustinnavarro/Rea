<?php
/*
 * Generated by CRUDigniter v3.2
 * www.crudigniter.com
 */
 
class TenerEstadoUsuario_model extends CI_Model
{
    public function __construct()
    {
        $this->load->model("Estadousuario_model");
        parent::__construct();
    }
    
    /*
     * Get tenerEstadoUsuario by hora
     */
    public function get_tenerEstadoUsuario($nombreUsuario, $params=array())
    {
        if (count($params)>0) {
            $params['nombreUsuario']=$nombreUsuario;
            $tenerUser=$this->db->get_where('tenerEstadoUsuario', $params)->row_array();
        } else {
            $tenerUser= $this->db->get_where('tenerEstadoUsuario', array('nombreUsuario'=>$nombreUsuario,'fechaFin'=>null))->row_array();
        }
        return $tenerUser;
    }
        
    /*
     * Get all tenerEstadoUsuario
     */
    public function get_all_tenerEstadoUsuario()
    {
        $this->db->order_by('hora', 'desc');
        return $this->db->get('tenerEstadoUsuario')->result_array();
    }
        
    /*
     * function to add new tenerEstadoUsuario
     */
    public function add_tenerEstadoUsuario($params)
    {
        return $this->db->insert('tenerEstadoUsuario', $params);
        //return $this->db->insert_id();
    }
    
    /*
     * function to update tenerEstadoUsuario
     */
    public function update_tenerEstadoUsuario($params=array(), $where=array())
    {
        foreach($where as $key=>$value){
            $this->db->where($key, $value);
        }
        return $this->db->update('tenerEstadoUsuario', $params);
    }
    
    /*
     * function to delete tenerEstadoUsuario
     */
    public function delete_tenerEstadoUsuario($hora)
    {
        return $this->db->delete('tenerEstadoUsuario', array('hora'=>$hora));
    }
}