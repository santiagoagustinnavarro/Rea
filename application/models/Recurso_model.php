<?php

/*
 * Generated by CRUDigniter v3.2
 * www.crudigniter.com
 */
 
class Recurso_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get recurso by nombre
     */
    public function get_recurso($nombre)
    {
        return $this->db->get_where('recurso', array('nombre'=>$nombre))->row_array();
    }
        
    /*
     * Get all recurso
     */
    public function get_all_recurso($filters="")
    {
        $this->db->join('archivo', 'archivo.idRecurso = recurso.idRecurso');
        if ($filters!="") {
            $this->db->where($filters);
        }
        $this->db->order_by('recurso.idrecurso', 'desc');
        $recursos=$this->db->get('recurso')->result_array();
        return $recursos;
    }
    
    public function record_count()
    {
        return $this->db->count_all("recurso");
    }

    public function fetch_recurso($limit, $start, $tema="")
    {   
        if ($tema!="") {
            $this->db->from("recurso");
            $this->db->join("tema", "tema.idRecurso=recurso.idRecurso");
            $this->db->where(array("tema.nombre"=>$tema));
           $query= $this->db->get();
        } else {
            $query = $this->db->get("recurso");
        }
        $this->db->limit($limit, $start);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
        
    /*
     * function to add new recurso
     */
    public function add_recurso($params)
    {
        $this->db->insert('recurso', $params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update recurso
     */
    public function update_recurso($nombre, $params)
    {
        $this->db->where('nombre', $nombre);
        return $this->db->update('recurso', $params);
    }
    
    /*
     * function to delete recurso
     */
    public function delete_recurso($nombre)
    {
        return $this->db->delete('recurso', array('nombre'=>$nombre));
    }
}
