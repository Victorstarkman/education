<?= $this->Form->control('city_id', ['label' => 'Ciudad',
	'class' => 'form-control form-control-blue m-0 col-12', 'empty' => 'Seleccione', 'options' => $cities, 'data-county-id' => $county_id , 'data-state-id' => $state_id, 'value' => $city_id]); ?>
