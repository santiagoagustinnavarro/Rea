<?php if(!$this->session->iniciada ||strtolower($this->session->rol=="profesor")){?>
<div class="col">
    <!-- /.box-header -->
  <div class="box-body">
    <div id="carousel" class="carousel slide" data-ride="carousel">
			<!-- El carrusel de imagenes -->
  		<ol class="carousel-indicators">
  			<li data-target="#carousel" data-slide-to="0" class="active"></li>
    		<li data-target="#carousel" data-slide-to="1"></li>
				<li data-target="#carousel" data-slide-to="2"></li>
				<li data-target="#carousel" data-slide-to="3"></li>
				<li data-target="#carousel" data-slide-to="4"></li>
				<li data-target="#carousel" data-slide-to="5"></li>
  		</ol>
    	<div class="carousel-inner" id="carousel">
			<div class="item active">
				<img class="d-block w-100" src="<?php echo base_url(); ?>assets/estilo/imagenes/1.png" alt="Recursos Educativos Abiertos">
			</div> <!-- cierra la imagen 1 de carousel -->
			<div class="item">
				<img class="d-block w-100" src="<?php echo base_url(); ?>assets/estilo/imagenes/2.png" alt="Es una plataforma educativa">
			</div> <!-- cierra la imagen 2 de carousel -->
			<div class="item">
				<img class="d-block w-100" src="<?php echo base_url(); ?>assets/estilo/imagenes/3.png" alt="Para integrar en las escuelas secundarias">
			</div> <!-- cierra la imagen 3 de carousel -->
			<div class="item">
				<img class="d-block w-100" src="<?php echo base_url(); ?>assets/estilo/imagenes/4.png" alt="Brindar herramientas a los adolescentes">
  			</div> <!-- cierra la imagen 4 de carousel -->
			<div class="item">
  	  			<img class="d-block w-100" src="<?php echo base_url(); ?>assets/estilo/imagenes/5.png" alt="En esta pagina es de ambito colaborativo">
  			</div> <!-- cierra la imagen 5 de carousel -->
			<div class="item">
    			<img class="d-block w-100" src="<?php echo base_url(); ?>assets/estilo/imagenes/6.png" alt="Se puede descargar, comentar, valorar y intercambiar informacion">
    		</div> <!-- cierra la imagen 6 de carousel -->
		</div> <!-- cierra las imagenes de carousel -->
			<!-- anterior -->
		<a class="left carousel-control" href="#carousel" data-slide="prev">
			<span class="fa fa-angle-left"></span>
		</a>
			<!-- siguiente -->
		<a class="right carousel-control" href="#carousel" data-slide="next">
			<span class="fa fa-angle-right"></span>
    	</a>
    </div>
  </div> <!-- /.box-body -->
</div> <!-- /.col -->

<?php 
}elseif(strtolower($this->session->rol=="administrador de usuarios")){
	redirect("usuario");
}elseif(strtolower($this->session->rol=="administrador de recursos")){
	redirect("recurso");}
