<?= $this->Html->css(
	[  		'general/general_manual'
	]) ?>
<div class="mx-auto mt-5 col-12">
	<div class="col-12 title-section">
		<h4>Manual del Usuario para la Gesti&oacute;n de Control de Ausentismo por Red Prestacional</h4>
	</div>
	<div class="results">
		<div class="col-9 col-md-9">
			<article id="general">
				<header>
					<h2>General</h2>
				</header>
				<p>El objetivo del sistema es proveer un soporte de gesti&oacute;n y manejo de data para la realizaci&oacute;n de m&eacute;tricas inherentes al servicio de Control de Ausentismo.
					El proceso consiste en recibir la informaci&oacute;n filiatoria de las n&oacute;minas de los empleados de las empresas a auditar, sus patolog&iacute;as y los d&iacute;as asignados. Red Prestacional asigna un Auditor a los casos. El auditor genera un diagn&oacute;stico y otorga o deniega la cantidad de d&iacute;as recomendados. El informe se env&iacute;a a los clientes que requirieron el control de ausentismo.
					Los actores de este servicio son por tanto la Red Prestacional, El Auditor y el Cliente (a&uacute;n no instrumentado).
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
								La p&aacute;gina principal de la aplicaci&oacute;n se encuentra en el link <a href='https:\\dienstausentismo.com.ar\red'>https:\\dienstausentismo.com.ar\red</a>  para entrar en la plataforma utilizando el navegador de su preferencia. Para esto debe disponer de un mail registrado
								por el administrador del sistema e introducirlo en el campo se&ntilde;alado por el &iacute;cono <?= $this->Html->image('icons/blue/email-blue.png',['alt'=>'mail'])?> para usuario y de una contraseña para introducirla en el campo se&ntilde;alado por el &iacute;cono <?= $this->Html->image('icons/blue/lockpad-blue.png',['alt'=>'candado'])?> .
							</p>
						</div>
					</div>

				</article>
			</div>
			<div >
				<article id="red">
					<header>
						<h2>Red Prestacional</h2>
					</header>
					<p>
						Si el usuario corresponde a un administrador registrado de Red Prestacional se abre un men&uacute; con las siguientes funcionalidades
					</p>
					<div class="row">
						<div class="col-4">
							<?= $this->Html->image('manual/menured.jpg',['alt'=>'menu_red','class'=>'menu_centro_medico', 'style' => 'width:100%'])?>
							<p><small>Fig 2</small></p>
						</div>
						<div class="col-8">
							<p>
								<span>Lista de Auditor&iacute;as Realizadas:</span> Listado de personas auditadas.<br>
								<span>Auditor&iacute;as Pendientes: </span> Listado de personas por auditar.<br>
								<span>Listado de Agentes:  </span> Listado de todas las personas de la empresa y estado de reportes.<br>
								<span>Nueva Auditor&iacute;a: </span> Entrada de pedido de auditor&iacute;a referido al listado de personas. En este se elige el auditor y el auditor recibir&aacute;en su casilla un mail que le advierte la entrada de un nuevo auditado<br>
								<span>Nuevo Agente: </span> formulario de datos filiatorios de la persona.<br>
								<span>Lista de empresas:  </span> Listado de empresas que envían personas a auditar.<br>
								<span>Nueva empresa: </span> Alta de empresa.<br>

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
								<span>Auditor&iacute;s Pendientes: </span> : Listado de personas atribuidas al auditor.
								En este se encuentra la entrada al formulario de diagn&oacute;stico el cual culmina con el resultado y firmado del Auditor. Para esto la primera vez que entra un nuevo auditor a la aplicación debe registrar una firma gr&aacute;fica que junto con su licencia rubricar&aacute;n la documentaci&oacute;n.<br>
								<span>Lista de Auditor&iacute;as:  </span>Listado de todos los auditados. <br>
							</p>
						</div>
					</div>
				</article>
			</div>
			<div >
				<article id="firma">
					<header>
						<h2>Registro de Firma de  Auditor</h2>
					</header>
					<p>
						La primera entrada del auditor le pedirá el sistema un registro de firma.
					</p>
					<div class="row">
						<div class="col-12">
							<?= $this->Html->image('manual/auditorFirma.jpg',['alt'=>'firma_auditor','class'=>'menu_centro_medico', 'style' => 'width:100%'])?>
							<p><small>Fig 4</small></p>
						</div>
					</div>
				</article>
			</div>
			<div >
				<article id="documento">
					<header>
						<h2>Documento de Auditor&iacute;a</h2>
					</header>
					<p>
						En Red Prestacional tendr&aacute; a disposici&oacute;n el documento de la auditor&iacute;a correspondiente al personal auditado
					</p>
					<div class="row">
						<div class="col-12">
							<?= $this->Html->image('manual/documento.jpg',['alt'=>'documento','class'=>'menu_centro_medico', 'style' => 'width:100%'])?>
							<p><small>Fig 5</small></p>
						</div>
					</div>
				</article>
			</div>

		</div>
	</div>
</div>
