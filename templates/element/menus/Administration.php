<div class="card">
    <div class="card-header" id="pacientes">
        <h2 class="mb-0">
            <button class="btn btn-link  btn-principal" type="button" data-toggle="collapse" data-target="#collapsePacientes" aria-expanded="true" aria-controls="collapsePacientes">
                <i class="far fa-user"></i>
                Administraci&oacute;n
            </button>
        </h2>
    </div>
    <div id="collapsePacientes" class="collapse show" aria-labelledby="pacientes" data-parent="#menuHome">
        <div class="card-body">
            <ul class="sub-menu">
                <li>
                    <a href="<?= $this->Url->build($redirect . '/listado-resultados', ['fullBase' => true]); ?>" class="btn btn-link" data-togle="pill">Lista de Auditorías Realizadas</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build($redirect . '/listado-sin-resultados', ['fullBase' => true]); ?>" class="btn btn-link" data-togle="pill">Auditorías Pendientes</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build($redirect . '/listado', ['fullBase' => true]); ?>" class="btn btn-link" data-togle="pill">Listado de Agentes</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build($redirect . '/nuevo-ausente', ['fullBase' => true]); ?>" class="btn btn-link" data-togle="pill">Nueva Auditoría</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build($redirect . '/nuevo-agente', ['fullBase' => true]); ?>" class="btn btn-link" data-togle="pill">Nuevo Agente</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build($redirect . '/empresas', ['fullBase' => true]); ?>" class="btn btn-link" data-togle="pill">Listado de empresas</a>
                </li>
                <li>
                    <a href="<?= $this->Url->build($redirect . '/scrapper', ['fullBase' => true]); ?>" class="btn btn-link" data-togle="pill">Scrapper</a>
                </li>
            </ul>
        </div>
    </div>
</div>
