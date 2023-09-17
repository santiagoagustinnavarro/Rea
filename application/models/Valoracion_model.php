<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Valoracion_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function promedio($idRecurso){
        $this->db->select("avg(puntaje)");
       $this->db->where(array("idRecurso"=>$idRecurso));
    $prom=$this->db->get('valoracion')->row();
    if ($prom!=null) {
        foreach ($prom as $key => $value) {
            $promedio=$value;
        }
    }else{
        $promedio=0;
    }
    return $promedio;
        
    }
    function ranking(){
        $this->db->select("avg(v.puntaje) as estrellas,v.idRecurso,r.descripcion,r.titulo");
        $this->db->from("valoracion as v");
        $this->db->join("recurso as r","v.idRecurso=r.idRecurso");
        $this->db->join("tenerEstadoRecurso as tr","tr.idRecurso=r.idRecurso");
        $this->db->order_by("avg(v.puntaje)","DESC");
        $this->db->limit(5);
        $this->db->group_by("v.idRecurso");
        $this->db->where(["tr.fechaFin"=>null,"tr.nombreEstadoRecurso"=>"alta"]);
    $ranking=$this->db->get()->result_array();
   
    
    return $ranking;
        
    }

    /*
     * Get valoracion by idValoracion
     */
    function get_valoracion($idValoracion,$nombreUsuario="",$idRecurso="")
    {
        if ($nombreUsuario!="" && $idRecurso!="") {
            return $this->db->get_where('valoracion', array('nombreUsuario'=>$nombreUsuario,'idRecurso'=>$idRecurso))->row_array();
        }else{
            return $this->db->get_where('valoracion', array('idValoracion'=>$idValoracion))->row_array();
           
        }
    }
    
    /*
     * Get all valoracion count
     */
    function get_all_valoracion_count()
    {
        $this->db->from('valoracion');
        return $this->db->count_all_results();
    }
        
    /*
     * Get all valoracion
     */
    function get_all_valoracion($params = array())
    {
        $this->db->order_by('idValoracion', 'desc');
        if(isset($params) && !empty($params))
        {
            $this->db->limit($params['limit'], $params['offset']);
        }
        return $this->db->get('valoracion')->result_array();
    }
        
    /*
     * function to add new valoracion
     */
    function add_valoracion($params)
    {
       return $this->db->insert('valoracion',$params);
       
    }
    
    /*
     * function to update valoracion
     */
    function update_valoracion($idRecurso,$nombreUsuario,$params)
    {
        $this->db->where(['idRecurso'=>$idRecurso,'nombreUsuario'=>$nombreUsuario]);
        return $this->db->update('valoracion',$params);
    }
    
    /*
     * function to delete valoracion
     */
    function delete_valoracion($idValoracion)
    {
        return $this->db->delete('valoracion',array('idValoracion'=>$idValoracion));
    }
}
