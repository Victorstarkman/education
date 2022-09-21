<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Patient $patient
 */
?>
<div class="mx-auto mt-5 col-12">
    <div class="col-12 title-section">
        <h4>Nuevo ausente</h4>
    </div>
    <div class="results">
        <div class="container mx-auto row">
            <div class="container mx-auto row">
                <div class="pt-0 col-lg-8 col-sm-12">
                    <div class="form-group">
				        <?= $this->Form->control('document_search', ['label' => false,
					        'class' => 'form-control form-control-blue m-0 col-12', 'placeholder' => 'DNI *']); ?>
                    </div>
                </div>
                <div class="pl-0 col-lg-2 col-sm-12 ">
                    <button type="button" id="search" class="btn btn-outline-primary col-12" name="search">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                </div>
                <div class="pl-0 col-lg-2 col-sm-12 ">
                    <button type="button" id="new" class="btn btn-outline-primary col-12" name="new">
                        <i class="fa fa-plus"></i> Nueva
                    </button>
                </div>
                <div class="msgSearch"></div>
            </div>
            <div class="col-12 showForm" style="display: none;">
                <p class="title-results">Datos Personales<br/>
                    <small>Los campos indicados con
                        <span style="color:red">*</span> son de llenado obligatorio
                    </small>
                </p>
            </div>
            <?= $this->Flash->render() ?>
            <div class="patientForm container mx-auto row">

            </div>
        </div>
    </div>
</div>

<?php $this->start('scriptBottom');
$group = $this->Identity->get('groupIdentity');
$prefix = !empty($group['prefix']) ? $group['prefix'] : 'default';
$redirect = !empty($group) ? $group['redirect'] : ''; ?>

<script>
    $("#search, #new").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        let dni = $('#document-search').val();
        console.log(dni);
        $.ajax({
            type: "GET",
            url: '<?= $this->Url->build($redirect . 'patients/search/', ['fullBase' => true]); ?>',
            dataType: "html",
            data: {dni: dni, type: $(this).attr('id')},
            success: function (response) {
                $('.showForm').show();
                $('.patientForm').html(response)
            }
        });
    });
</script>
<?php $this->end(); ?>

