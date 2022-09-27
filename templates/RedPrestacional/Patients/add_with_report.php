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
<?php echo $this->Html->css('uploadFiles/styleUploadFile', ['block' => 'script']); ?>
<?php echo $this->Html->script('uploadFiles/uploadFile', ['block' => 'script']); ?>
<script>

    $( document ).ready(function() {
        $params = getUrlVars();
        if ($params.dni) {
            $('#document-search').val($params.dni);
            $("#search").click();
        }

        $("#document-search").on('keyup', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                $("#search").click();
            }
        });
    });

    function getUrlVars() {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }



    $("#search, #new").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        let dni = $('#document-search').val();
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

