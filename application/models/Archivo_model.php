<?php

/*
 * Generated by CRUDigniter v3.2
 * www.crudigniter.com
 */
 
class Archivo_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get estadousuario by nombre
     */
    public function get_recurso($nombre)
    {
        return $this->db->get_where('recurso', array('nombre'=>$nombre))->row_array();
    }
        
    /*
     * Get all estadousuario
     */
    public function get_all_recurso()
    {
        $this->db->order_by('nombre', 'desc');
        return $this->db->get('recurso')->result_array();
    }
        
    /*
     * function to add new estadousuario
     */
    public function add_archivo($params)
    {
        $this->db->insert('archivo',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update estadousuario
     */
    public function update_estadousuario($nombre,$params)
    {
        $this->db->where('nombre', $nombre);
        return $this->db->update('estadoUsuario', $params);
    }
    
    /*
     * function to delete estadousuario
     */
    public function delete_estadousuario($nombre)
    {
        return $this->db->delete('estadoUsuario', array('nombre'=>$nombre));
    }
}
