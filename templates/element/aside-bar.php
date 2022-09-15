<div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 menu-column vh-100 hide-md">
    <button class="show-md close-menu">
        X
    </button>
    <div class="mx-auto col-12 mt-5 logoContainer">
		<?= $this->Html->image('logo-white.png', ['alt' => 'Logo Dienst', 'class' => 'logo']); ?>
    </div>
    <?php
        $group = $this->Identity->get('groupIdentity');
        $prefix = (!empty($group['prefix'])) ? $group['prefix'] : 'default';
        $redirect = (!empty($group)) ?$group['redirect'] : '';
    ?>
    <div class="mx-auto col-12 mt-5 pt-3 menu-left-column">
        <div class="menu" id="menuHome">
            <?php if (!empty($group)) : ?>
			<?= $this->element('menus/' . $prefix, ['redirect' => $redirect]); ?>
            <?php endif;?>
	        <?php if (!empty($group)) : ?>
                <a href="<?= $this->Url->build(  '/salir', ['fullBase' => true]); ?>" class="btn btn-link">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar sesi&oacute;n
                </a>
            <?php else : ?>
                <a href="<?= $this->Url->build(  '/', ['fullBase' => true]); ?>" class="btn btn-link">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </a>
	        <?php endif;?>

        </div>
    </div>
</div>