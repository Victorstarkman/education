<div class="pt-0 col-lg-4 col-sm-12">
    <div class="form-group statesSelect">
        <?= $this->Form->control('state_id', ['label' => 'Provincia',
            'class' => 'form-control form-control-blue m-0 col-12', 'empty' => 'Seleccione']); ?>
    </div>
</div>
<div class="pt-0 col-lg-4 col-sm-12">
    <div class="form-group countySelect">
        <?= $this->Form->control('county_id', ['label' => 'Municipio',
            'class' => 'form-control form-control-blue m-0 col-12', 'empty' => 'Seleccione provincia']); ?>
    </div>
</div>
<div class="pt-0 col-lg-4 col-sm-12">
    <div class="form-group citiesSelect">
        <?= $this->Form->control('city_id', ['label' => 'Ciudad',
            'class' => 'form-control form-control-blue m-0 col-12', 'empty' => 'Seleccione municipio']); ?>
    </div>
</div>


<script>
    let city = <?= (!is_null($city)) ? $city : 0; ?>,
        dataState;
    searching('states');
    if (city > 0) {
        searching('cities_by_id', city);
    }


    $(".statesSelect").on("change", 'select', function (e, bySystem) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        if (typeof  bySystem == "object" && bySystem.isTriggeredBySystem) {
            searching('counties', $(this).val(), dataState.countyId);
        } else {
            searching('counties', $(this).val());
        }
    });

    $(".countySelect").on("change", 'select', function (e, bySystem) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        console.log(bySystem);
        if (typeof  bySystem == "object" && bySystem.isTriggeredBySystem) {

        } else {
            searching('cities', $(this).val());
        }
    });

    function searching(type, id, selectedID) {
        let url,
            selector,
            data = {},
            selected = false;
        switch (type) {
            case 'states':
                url = '<?= $this->Url->build('/buscar/provincias', ['fullBase' => true]); ?>';
                selector = '.statesSelect';
                break;
            case 'counties':
                url = '<?= $this->Url->build('/buscar/municipios', ['fullBase' => true]); ?>';
                selector = '.countySelect';
                data = {state_id: id}
                break;
            case 'cities':
                url = '<?= $this->Url->build('/buscar/ciudades', ['fullBase' => true]); ?>';
                selector = '.citiesSelect';
                data = {county_id: id}
                break;
            case 'cities_by_id':
                url = '<?= $this->Url->build('/buscar/ciudades-por-id', ['fullBase' => true]); ?>';
                selector = '.citiesSelect';
                data = {city_id: id}
                selected = true;
                break;
        }

        $.ajax({
            type: "GET",
            url: url,
            dataType: "html",
            data: data,
            success: function (response) {
                $(selector).html(response);
            }
        }).then(function() {
            if (selected) {
                dataState = $('.citiesSelect select').data();
                $(".statesSelect select")
                    .val(dataState.stateId)
                    .trigger('change', {'isTriggeredBySystem': true});
            }

            if (selectedID > 0) {
                $(selector + ' select').val(selectedID).trigger('change', {'isTriggeredBySystem': true});
            }
        });
    }
</script>
