<div class="mx-auto col-lg-8 col-md-12">
    <h1 class="pb-5">LOGIN</h1>
	<?= $this->Flash->render() ?>
</div>
<?= $this->Form->create($user, ['class' => 'col-lg-12 col-md-12']) ?>
<div class="form-group row">
    <div class="mx-auto col-lg-8 col-md-12">
        <div class="input-group" id="form-group-email">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="icon icon-email-blue"></i>
                </div>
            </div>
            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="email@email.com">
            <div class="invalid-feedback"></div>
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="mx-auto col-lg-8 col-md-12">
        <div class="input-group" id="form-group-password">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="icon icon-padlock-blue"></i>
                </div>
            </div>
            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="********">
            <div class="invalid-feedback"></div>
        </div>
    </div>
</div>
<div class="mx-auto form-group row col-lg-8 col-md-12">
    <div class="pl-0 col-12">
        <button type="submit" class="btn btn-outline-primary col-12"><i class="fas fa-sign-in-alt"></i> Ingresar</button>
    </div>
</div>
<a href="<?= $this->Url->build('/manual', ['fullBase' => true]); ?>">Ver manual de uso</a>


<?= $this->Form->end() ?>