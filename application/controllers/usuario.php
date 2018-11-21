<?php
/*
 * Generated by CRUDigniter v3.2
 * www.crudigniter.com
 */

class Usuario extends CI_Controller
{
    public function __construct()
    {
		parent::__construct();
		$this->load->helper('url');
    }
    
    /*
     * Lista a los usuarios
     */
    public function index()
    {
        $usuario = $this->Usuario_model->get_all_usuario();
        $this->load->view("header", ["title" => "Administrar usuarios"]);
        $this->load->view('usuario/index', ['usuario' => $usuario]);
        $this->load->view("footer");
    }
    public function actualizarClave($nombreUsuario="")
    {
        echo $nombreUsuario;
        if ($nombreUsuario!="") {
            $clave=$this->input->post("clave");
            $clave2=$this->input->post("clave2");

            $user=$this->Usuario_model->get_usuario($nombreUsuario);
            if ($user!=null) {
                if ($clave==$clave2) {
                    $update= $this->Usuario_model->update_usuario($nombreUsuario, array("clave"=>hash('sha224', $clave2)));
                    if ($update) {
                        $this->load->view("header", array("title"=>"Clave actualizada"));
                        $this->load->view("restablecerClave",["mensaje"=>"actualizado"]);
                        $this->load->view("footer");
                    } else {
                        echo "algo fallo en la actualizacion";
                    }
                } else {
                    $this->load->view("header", array("title"=>"Clave actualizada"));
                        $this->load->view("restablecerClave", ["mensaje"=>"las claves no coinciden"]);
                        $this->load->view("footer");

                }
            }
        }
    }

    /*
     * Añadiendo a nuevo usuario
     */
    public function registro()
    {
        if (isset($_POST) && count($_POST) > 0) {
            $params = array(
                'clave' => hash('sha224', $this->input->post('clave')),
                'dni' => $this->input->post('dni'),
                'apellido' => $this->input->post('apellido'),
                'nombre' => $this->input->post('nombre'),
                'domicilio' => $this->input->post('domicilio'),
                'email' => $this->input->post('email'),
                'nombreUsuario' => $this->input->post('nombreUsuario'),
            );
            $insercion = $this->Usuario_model->add_usuario($params);
            if ($insercion) {
                $fecha = (getdate()["year"]) . "-" . (getdate()["mon"]) . "-" . (getdate()["mday"]);
                $hora = (getdate()["hours"]) . ":" . (getdate()["minutes"]) . ":" . (getdate()["seconds"]);
                $nombreEstadoUsuario = "pendiente";
                $nombreRol="Profesor";
                $nombreUsuario = $params["nombreUsuario"];
                $datosEstado = array("fechaInicio"=>$fecha,"hora"=>$hora,"nombreUsuario"=>$nombreUsuario,"nombreEstadoUsuario"=>$nombreEstadoUsuario);
                $datosRol=array("fechaInicio"=>$fecha,"nombreUsuario"=>$nombreUsuario,"nombreRol"=>$nombreRol);
                $insercionEstado = $this->TenerEstadoUsuario_model->add_tenerEstadoUsuario($datosEstado);
                $insercionProfesor = $this->Tienerol_model->add_tienerol($datosRol);
                $this->load->view("header", ["title" => "Registro"]);
                $this->load->view('inicio/registrarse', array("mensaje" => '<div class="alert alert-success text-center"><h4>'."Registrado con exito".'</h4></div>'));
                $this->load->view("footer");
            } else {
                $this->load->view("header", ["title" => "Registro"]);
                $this->load->view('inicio/registrarse', array("mensaje" => '<div class="alert alert-info text-center"><h4>'."El usuario ya existe".'</h4></div>'));
                $this->load->view("footer");
            }
            // redirect('usuario/index');
        } else {
            $this->load->view("header", ["title" => "Registro","scripts"=>["validacion.js"]]);
            $this->load->view('inicio/registrarse');
            $this->load->view("footer");
        }
    }

    /*
     * Editando a el estado y el rol del usuario
     */
    public function edit($nombreUsuario)
    {
        // check if the usuario exists before trying to edit it
        $data['usuario'] = $this->Usuario_model->get_usuario($nombreUsuario);
        if (isset($data['usuario']['nombreUsuario'])) {
            if (isset($_POST) && count($_POST) > 0) {
                $estadoActual=$this->input->post('estadoActual');
                $rolActual=$this->input->post('rolActual');
                $rolNuevo=$this->input->post('nuevoRol');
                $estadoNuevo = $this->input->post('nuevoEstado');
                $datosEstado=['tabla'=>'EstadoUsuario','antiguo'=>$estadoActual,'nuevo'=>$estadoNuevo,'nombreUsuario'=>$nombreUsuario];
                $datosRol=['tabla'=>'Rol','antiguo'=>$rolActual,'nuevo'=>$rolNuevo,'nombreUsuario'=>$nombreUsuario];
                $actualizarEstado=$this->actualizar($datosEstado);
                if (!$actualizarEstado) {
                    $this->load->view("header", ["title" => "Editar Usuario"]);
                    $this->load->view('usuario/edit', ['usuario'=>$data['usuario'],'mensaje'=>'<div class="offset-md-4 col-md-4 alert alert-info text-center"><h4>'.'Intente actualizar los datos en unos instantes'.'</h4></div>']);
                    $this->load->view("footer");
                } else {//Solo actualizaremos el rol si se pudo actualizar el estado para no generar conflictos
                    $actualizarRol=$this->actualizar($datosRol);
                    if (!$actualizarEstado) {
                        $this->load->view("header", ["title" => "Editar Usuario"]);
                        $this->load->view('usuario/edit', ['usuario'=>$data['usuario'],'mensaje'=>'<div class="offset-md-4 col-md-4 alert alert-info text-center"><h4>'.'Intente actualizar el rol en unos instantes'.'</h4></div>']);
                        $this->load->view("footer");
                    }

                    $this->load->view("header", ["title" => "Editar Usuario"]);
                    $this->load->view('usuario/edit', ['usuario'=>$data['usuario'],'mensaje'=>'<div class="offset-md-4 col-md-4 alert alert-success text-center"><h4>'.'Datos actualizados'.'</h4></div>']);
                    $this->load->view("footer");
                }
            } else {
                $this->load->view("header", ["title" => "Editar Usuario"]);
                $this->load->view('usuario/edit', ['usuario'=>$data['usuario']]);
                $this->load->view("footer");
            }
        } else {
            show_error('The usuario you are trying to edit does not exist.');
        }
    }
    /**
     * Funcion auxiliar encargada de modificar el estado o el rol segun corresponda
     * donde $datos corresponde a un array asociativo donde entraran las claves tabla,nombreUsuario,antiguo,nuevo
     */
    private function actualizar($datos)
    {
        $fechaActual=getdate()["year"]."-".getdate()["mon"]."-".getdate()["mday"];
        $horaActual=getdate()["hours"].":".getdate()["minutes"].":".getdate()["seconds"];
        $actualizacion=true;
        if ($datos["antiguo"]!=$datos["nuevo"]) {
            $params = array(
                    'nombreUsuario'=>$datos["nombreUsuario"],
                    'nombre'.$datos["tabla"]=>$datos["nuevo"],
                    'fechaInicio'=>$fechaActual,
                    'hora'=>$horaActual,
                );
            switch ($datos["tabla"]) {
                    case "EstadoUsuario":
                    $setAntiguoEst=$this->TenerEstadoUsuario_model->update_tenerEstadoUsuario(array('fechaFin'=>$fechaActual), array('nombreEstadoUsuario'=>$datos["antiguo"],'nombreUsuario'=>$datos["nombreUsuario"],'fechaFin'=>null));
                    if ($setAntiguoEst) {
                        $insertEstado=$this->TenerEstadoUsuario_model->add_tenerEstadoUsuario($params);
                        if (!$insertEstado) {//Si por algun motivo fallo la insercion vuelve a null la fecha fin del ultimo estado
                            $this->TenerEstadoUsuario_model->update_tenerEstadoUsuario(array('fechaFin'=>null), array('nombreEstadoUsuario'=>$datos["antiguo"],'nombreUsuario'=>$nombreUsuario,'fechaFin'=>$fechaActual));
                            $actualizacion=false;
                        }
                    } else {
                        $actualizacion=false;
                    }
                    break;
                    case "Rol":
                     $setAntiguoRol=$this->Tienerol_model->update_tienerol(array('fechaFin'=>$fechaActual), array('nombreRol'=>$datos["antiguo"],'nombreUsuario'=>$datos["nombreUsuario"],'fechaFin'=>null));
                    if ($setAntiguoRol) {
                        $insertRol=$this->Tienerol_model->add_tienerol($params);
                        if (!$insertRol) {//Si por algun motivo fallo la insercion vuelve a null la fecha fin del ultimo rol
                            $this->Tienerol_model->update_tienerol(array('fechaFin'=>null), array('nombreRol'=>$datos["antiguo"],'nombreUsuario'=>$datos["nombreUsuario"],'fechaFin'=>$fechaActual));
                            $actualizacion=false;
                        }
                    } else {
                        $actualizacion=false;
                    }
                    break;
                }
        }
        return $actualizacion;
    }
    public function test()
    {
    }
    /**
     * Editar perfil del usuario
     * El usuario va a poder modificar sus datos, por si quiere modificar o agregar alguno de estos.
     */
    public function editarPerfil()
    {
        $cant=count($_POST);
        if ($cant>0) {//Se enviaron datos desde el formulario
            $nombUser=$this->input->post("nombreUsuario");
            $nombre=$this->input->post("nombre");
            $apellido=$this->input->post("apellido");
            $domicilio=$this->input->post("domicilio");
            $dni=$this->input->post("dni");
            $email=$this->input->post("email");
            $clave=$this->input->post("clave");
            $claveNueva=$this->input->post("clave1");
            $claveNuevaRep=$this->input->post("clave2");
            $usuarioBuscado=$this->Usuario_model->get_usuario($nombUser);
            if ($usuarioBuscado!=null) {
                if (hash('sha224', $clave)==$usuarioBuscado["clave"]) {
                    $datos=["nombre"=>$nombre,"apellido"=>$apellido,"domicilio"=>$domicilio,"dni"=>$dni,"email"=>$email,"clave"=>$clave];
                    $res=$this->Usuario_model->update_usuario($nombUser, array("nombre"=>$nombre,"apellido"=>$apellido,"domicilio"=>$domicilio,"dni"=>$dni,"email"=>$email,"clave"=>hash('sha224', $this->input->post('clave'))));
                    if ($res) {
                        $this->actualizarSesion($datos);
                        $this->load->view("header", ["title" => "Editar Perfil"]);
                        $this->load->view('usuario/editarPerfil', ['mensaje'=>'<div class="offset-md-3 col-md-6 alert alert-success text-center"><h4>'.'Datos Actualizados Correctamente'.'</h4></div>']);
                        $this->load->view("footer");
                    } else {
                        $this->load->view("header", ["title" => "Editar Perfil"]);
                        $this->load->view('usuario/editarPerfil', ['mensaje'=>'<div class="offset-md-3 col-md-6 alert alert-danger text-center"><h4>'.'Error al tratar de cargar los datos'.'</h4></div>']);
                        $this->load->view("footer");
                    }
                } else {
                    $this->load->view("header", ["title" => "Editar Perfil"]);
                    $this->load->view('usuario/editarPerfil', ['mensaje'=>'<div class="offset-md-3 col-md-6 alert alert-danger text-center"><h4>'.'La clave actual es invalida'.'</h4></div>']);
                    $this->load->view("footer");
                }
            } else {
                $this->load->view("header", ["title" => "Editar Perfil"]);
                $this->load->view('usuario/editarPerfil', ['mensaje'=>'<div class="offset-md-3 col-md-6 alert alert-danger text-center"><h4>'.'El usuario no existe'.'</h4></div>']);
                $this->load->view("footer");
            }
        } else {
            $this->load->view("header", ["title" => "Editar Perfil"]);
            $this->load->view('usuario/editarPerfil');
            $this->load->view("footer");
        }
    }
    private function actualizarSesion($datos)
    {
        session_start();
        $_SESSION["nombre"]=$datos["nombre"];
        $_SESSION["apellido"]=$datos["apellido"];
        $_SESSION["domicilio"]=$datos["domicilio"];
        $_SESSION["dni"]=$datos["dni"];
        $_SESSION["email"]=$datos["email"];
        $_SESSION["clave"]=$datos["clave"];
    }
    /*
     * Eliminar un usuario
     */
    public function eliminarCuenta($nombUser="")
    {
        if ($nombUser=="") {
            $this->load->view("header", ["title" => "Eliminar Cuenta"]);
            $this->load->view('usuario/eliminarCuenta');
            $this->load->view("footer");
        } else {
            $hora=date("H:i:s");
            $fecha=date("Y-m-d");
            $paramsUpdate=array("fechaFin"=>date("Y-m-d"));
            $paramsNew=array("nombreEstadoUsuario"=>"baja","fechaFin"=>null,"fechaInicio"=>$fecha,"nombreUsuario"=>$nombUser,"hora"=>$hora);
            $where=array("fechaFin"=>null,"nombreUsuario"=>$nombUser,"nombreEstadoUsuario"=>"alta");
            $actualizarEstado=$this->TenerEstadoUsuario_model->update_tenerestadousuario($paramsUpdate, $where);
            if ($actualizarEstado) {
                $nuevoEstado=$this->TenerEstadoUsuario_model->add_tenerEstadoUsuario($paramsNew);
                if ($nuevoEstado) {
                    session_destroy();
                    redirect('login/cerrarSession');
                } else {
                    $this->load->view("header", ["title" => "Eliminar Cuenta"]);
                    $this->load->view('usuario/eliminarCuenta', ['mensaje'=>'<div class="offset-md-3 col-md-6 alert alert-danger text-center"><h4>'.'Ah ocurrido un error'.'</h4></div>']);
                    $this->load->view("footer");
                }
            } else {
                $paramsNew["fechaFin"]=null;
                $reestablecerEstado=$this->TenerEstadoUsuario_model->add_tenerEstadoUsuario($paramsNew);
            }
        }
        /*  $usuario = $this->Usuario_model->post_usuario($nombreUsuario);
         if (isset($usuario['nombreUsuario'])) {
             if ($this->Usuario_model->darBaja_usuario($usuario['nombreUsuario'])) {
             } else {
                 echo "No se ha podido dar de baja";
             };
         //redirect("usuario/index");
         } else {
             show_error('El usuario no existe');
         } */
	}
	public function subirRecurso()
	{
        if (count($_POST)>0) {
			$nombreRec=$this->input->post("nombre");
			$desc=$this->input->post("textarea");
			$archivos=$_FILES["archivo"];
            if ($nombreRec!="" && count($archivos)>0 && $desc!="") {
                $recurso=$this->subida($nombreRec, $archivos, $desc);
                if ($recurso) {
                    $this->load->view("header", ["title" => "Subir Recurso"]);
                    $this->load->view('usuario/subirRecurso', ["mensaje"=>"<div class='col-md-12 alert alert-success text-center'><h4>".'Recurso subido con exito'."</h4></div>"]);
                    $this->load->view("footer");
                }
            } else {
                $this->load->view("header", ["title" => "Subir Recurso"]);
                $this->load->view('usuario/subirRecurso', ["mensaje"=>"<div class='col-md-12 alert alert-danger text-center'><h4>".'Faltan completar campos'."</h4></div>"]);
                $this->load->view("footer");
            }
        } else {
            $this->load->view("header", ["title" => "Subir Recurso"]);
            $this->load->view('usuario/subirRecurso');
            $this->load->view("footer");
        }
    }

    private function subida($nombreRec, $archivos, $textarea)
    {
        require "login.php";
        $login=new Login();
        $recurso=array("nombreUsuario"=>$_SESSION["nombreUsuario"],"titulo"=>$nombreRec,"descripcion"=>$textarea);
		$idRecurso=$this->Recurso_model->add_recurso($recurso);
        if ($idRecurso>0) {
            $res=true;
            foreach ($archivos as $etiqueta=>$valor) {
				if($etiqueta=="name"){
					$ruta=base_url()."assets/recurso/archivo/".$valor;
                	$idArchivo=$this->Archivo_model->add_archivo(array("nombre"=>$valor,"ruta"=>$ruta,"idRecurso"=>$idRecurso));
                	if ($idArchivo<=0) {
                    	$res=false;
					}
				}
			}	
        } else {
            $res=false;
        }
        return $res;
    } 
}
