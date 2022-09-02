<div class="text-center">
	<div class="paginator">
		<ul class="pagination">
			<?= $this->Paginator->prev('< ' . __('Anterior')) ?>
			<?= $this->Paginator->numbers() ?>
			<?= $this->Paginator->next(__('Siguiente') . ' >') ?>
		</ul>
		<p><?= $this->Paginator->counter(__('P&aacute;gina {{page}} de {{pages}}, mostrando {{current}} registro(s) de un total de {{count}}')) ?></p>
	</div>
</div>