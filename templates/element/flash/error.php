<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<div class="alert alert-danger col-lg-12" role="alert">
    <div class="message error" onclick="this.classList.add('hidden');"><?= $message ?></div>
</div>