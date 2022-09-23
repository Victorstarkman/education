<div class="card">
    <div class="card-header" id="pacientes">
        <h2 class="mb-0">
            <button class="btn btn-link  btn-principal" type="button" data-toggle="collapse" data-target="#collapsePacientes" aria-expanded="true" aria-controls="collapsePacientes">
                <i class="far fa-user"></i>
                Red Prestacional
            </button>
        </h2>
    </div>
    <div id="collapsePacientes" class="collapse show" aria-labelledby="pacientes" data-parent="#menuHome">
        <div class="card-body">
            <ul class="sub-menu">
                <li>
                    <a href="<?= $this->Url->build($redirect . '/listado-resultados', ['fullBase' => true]); ?>" class="btn btn-link" data-togle="pill">Ausentes con resultados</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build($redirect . '/listado', ['fullBase' => true]); ?>" class="btn btn-link" data-togle="pill">Listado personas</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build($redirect . '/nuevo-ausente', ['fullBase' => true]); ?>" class="btn btn-link" data-togle="pill">Nuevo ausente</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build($redirect . '/nuevo-paciente', ['fullBase' => true]); ?>" class="btn btn-link" data-togle="pill">Nueva persona</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build($redirect . '/empresas', ['fullBase' => true]); ?>" class="btn btn-link" data-togle="pill">Listado de empresas</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build($redirect . '/empresas/crear', ['fullBase' => true]); ?>" class="btn btn-link" data-togle="pill">Nueva empresas</a>
                </li>
            </ul>
        </div>
    </div>
</div>
