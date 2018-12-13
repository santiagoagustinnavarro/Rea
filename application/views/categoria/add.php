
	

<?php if(isset($mensaje)){
	echo $mensaje;
}?>
<br/>
<div class="container box box-info">
		<h1>Agregar Seccion</h1>
		<h3>Seleccione una Categoria</h3>
		<?php echo form_open("categoria/add")?>
		<div class="form-group col-md-offset-2 col-md-8">
			<select class="form-control text-center" id="categoria" name="categoria">
				<?php 
				$selected=false;
					foreach ($categorias as $cat) {
				?>
				<option value="<?php echo $cat["nombre"]; ?>" <?php if(set_value('categoria')==$cat["nombre"]){echo "selected";$selected=true;}?>><?php echo $cat["nombre"];?></option><?php }?>
				<option value="" <?php if(!$selected){
                    echo "selected";
                }?>>Agregar Categoria</option>
				
			</select>
			<br/>
			<br/><input type="text"  class="form-control" id="nuevaCategoria" placeholder="ingrese el nombre de la nueva categoria" name="nuevaCategoria">
			<?php echo form_error('nuevaCategoria'); ?>
			<br/><textarea rows="6" class="form-control" id="descNuevaCategoria" placeholder="ingrese una descripcion de la nueva categoria" name="descNuevaCategoria"></textarea>
			<h3>Ingrese un nuevo tema</h3>
		
			<select style="visibility:hidden" class="form-control text-center" id="tema" name="tema">
				
				<?php 
					foreach ($temas as $tema) {
				?>
				<option value="<?php echo $tema["nombre"]; ?>" ><?php echo $tema["nombre"];?></option>
				<?php
				}
				?>
				
				
			</select>
			
			
			<input type="text" value="<?php echo set_value('nuevoTema'); ?>"  id="nuevoTema" name="nuevoTema" class="form-control" placeholder="ingrese el nombre del nuevo tema" >
			<?php echo form_error('nuevoTema'); ?>
			<br/><textarea rows="6" class="form-control" id="descNuevoTema" placeholder="ingrese una descripcion del tema" name="descNuevoTema"></textarea>
			<br>	
			<div class="form-group">
				<?php 
				echo form_submit("envio","Agregar","class=\"btn btn-success\"");
				?>
			</div>
		</div>
		<?php echo form_close();?>	
	</div>
</div>
