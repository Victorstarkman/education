<?= $this->Html->css(
	[  		'general/general_manual'
	]) ?>
<div class="mx-auto mt-5 col-12">
	<div class="col-12 title-section">
		<h4>Manual del Usuario para la Gesti&oacute;n  de Servicios de Educaci&oacute;n</h4>
	</div>
	<div class="results">
		<div class="col-9 col-md-9">
			<article id="general">
				<header>
					<h2>General</h2>
				</header>
				<p>El objetivo del sistema es proveer un soporte de gesti&oacute;n y manejo de data para los ex&aacute;menes de medicina laboral destinado a 
					los agentes del sistema educativo de la provincia de Buenos Aires.<br>
					El proceso incluye la recepci&oacute;n de datos filiatorios y de pedidos de servicios de los agentes en sus distintintas modalidades,
					sea ex&aacute;menes ambulatorios presenciales, virtuales, domiciliarios y juntas m&eacute;dicas.<br>
					El sistema asigna en forma
					autom&aacute;tica o manual  las auditorias seg&uacute;n los centros m&eacute;dicos relevantes, permitiendo la gesti&oacute;n de la informaci&oacute;n
					en los centros grandes a los administradores espec&iacute;ficos o en los centros pequeños por los mismos auditores.&nbsp;
					Luego de finalizada la auditor&iacute;a el sistema genera la documentaci&oacute;n relevante con los campos completos y quedando preparado para la 
					firma de los agentes y el auditor. <br>
					Los actores de este servicio son  la administraci&oacute;n y El Auditor .
				</p>
			</article>
			<div >
				<article >
					<header>
						<h2>Principal</h2>
					</header>
					<div class="row">
						<div class="col-4 col-md-4">
							<?php echo $this->Html->image('manual/imagen de entrada.jpg', ['alt' => 'portal','class'=>'admin-foto', 'style' => 'width:100%']);?>
							<p><small class="text-center">Fig 1</small></p>
						</div>
						<div class="col-8">
							<p class="admin_p">
								La p&aacute;gina principal de la aplicaci&oacute;n se encuentra en el link <a href='https:\\dienstedu.com.ar\education'>https:\\dienstedu.com.ar\education</a>  para entrar en la plataforma utilizando el navegador de su preferencia. Para esto debe disponer de un mail registrado
								por el administrador del sistema e introducirlo en el campo se&ntilde;alado por el &iacute;cono <?= $this->Html->image('icons/blue/email-blue.png',['alt'=>'mail'])?> para usuario y de una contraseña para introducirla en el campo se&ntilde;alado por el &iacute;cono <?= $this->Html->image('icons/blue/lockpad-blue.png',['alt'=>'candado'])?> .
								Esto le otorgar&aacute; los permisos relevantes al usuario dependiendo del centro m&eacute;dico al que pertenezca y de su funci&oacute;n
							</p>
						</div>
					</div>

				</article>
			</div>
			<div >
				<article id="red">
					<header>
						<h2>Administraci&oacute;n</h2>
					</header>
					<p>
						Si el usuario corresponde a un administrador registrado de Administraci&oacute;n se abrir&aacute; un men&uacute; con las siguientes funcionalidades
					</p>
					<div class="row">
						<div class="col-4">
							<?= $this->Html->image('manual/menuadministracionedu.png',['alt'=>'menu_administracion','class'=>'menu_centro_medico', 'style' => 'width:100%'])?>
							<p><small>Fig 2</small></p>
						</div>
						<div class="col-8">
							<p>
								<span>Lista de Auditor&iacute;as Realizadas:</span> Listado de personas auditadas.<br>
								<span>Auditor&iacute;as Pendientes: </span> Listado de personas por auditar.<br>
								<span>Listado de Agentes:  </span> Listado de todas los agentes con sus reportes realizados y pendientes.<br>
								<span>Procesamiento de Datos: </span> Generaci&oacute;n y procesamiento de la informaci&oacute;n.<br>

							</p>
						</div>
					</div>
				</article>
			</div>
			<div >
				<article id="medico">
					<header>
						<h2>M&eacute;dico Auditor</h2>
					</header>
					<p>
						Al entrar con el mail y la contrase&ntilde;a del auditor se obtiene la pagina de lista de agentes sin diagnosticar.
					</p>
					<div class="row">
						<div class="col-4">
							<?= $this->Html->image('manual/menuauditor.jpg',['alt'=>'menu_auditor','class'=>'menu_centro_medico', 'style' => 'width:100%'])?>
							<p><small>Fig 3</small></p>
						</div>
						<div class="col-8">
							<p>
								<span>Auditor&iacute;as Pendientes: </span> : Listado de personas atribuidas al auditor.
								En este se encuentra la entrada al formulario de diagn&oacute;stico el cual culmina con el resultado y firmado del Auditor. <br>
								<span>Lista de Auditor&iacute;as:  </span>Listado de todos los auditados pendientes y realizados. <br>
							</p>
						</div>
					</div>
				</article>
			</div>
			</div>

		</div>
	</div>
</div>
