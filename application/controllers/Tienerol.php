<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Tienerol extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tienerol_model');
    } 

    /*
     * Listing of tienerol
     */
    function index()
    {
        $data['tienerol'] = $this->Tienerol_model->get_all_tienerol();
        
        $data['_view'] = 'tienerol/index';
        $this->load->view('layouts/main',$data);
    }

    /*
     * Adding a new tienerol
     */
    function add()
    {   
        if(isset($_POST) && count($_POST) > 0)     
        {   
            $params = array(
				'fechaInicio' => $this->input->post('fechaInicio'),
				'fechaFin' => $this->input->post('fechaFin'),
            );
            
            $tienerol_id = $this->Tienerol_model->add_tienerol($params);
            redirect('tienerol/index');
        }
        else
        {            
            $data['_view'] = 'tienerol/add';
            $this->load->view('layouts/main',$data);
        }
    }  

    /*
     * Editing a tienerol
     */
    function edit($nombreUsuario)
    {   
        // check if the tienerol exists before trying to edit it
        $data['tienerol'] = $this->Tienerol_model->get_tienerol($nombreUsuario);
        
        if(isset($data['tienerol']['nombreUsuario']))
        {
            if(isset($_POST) && count($_POST) > 0)     
            {   
                $params = array(
					'fechaInicio' => $this->input->post('fechaInicio'),
					'fechaFin' => $this->input->post('fechaFin'),
                );

                $this->Tienerol_model->update_tienerol($nombreUsuario,$params);            
                redirect('tienerol/index');
            }
            else
            {
                $data['_view'] = 'tienerol/edit';
                $this->load->view('layouts/main',$data);
            }
        }
        else
            show_error('The tienerol you are trying to edit does not exist.');
    } 

    /*
     * Deleting tienerol
     */
    function remove($nombreUsuario)
    {
        $tienerol = $this->Tienerol_model->get_tienerol($nombreUsuario);

        // check if the tienerol exists before trying to delete it
        if(isset($tienerol['nombreUsuario']))
        {
            $this->Tienerol_model->delete_tienerol($nombreUsuario);
            redirect('tienerol/index');
        }
        else
            show_error('The tienerol you are trying to delete does not exist.');
    }
    
}