<!-- src/Template/Users/add.ctp -->
<div class="users form">
	<?= $this->Form->create($user) ?>
	<fieldset>
		<legend><?= __('Add User') ?></legend>
		<?= $this->Form->control('username') ?>
		<?= $this->Form->control('password') ?>
		<?= $this->Form->control('role', [
		'options' => ['0' => 'Admin', '1' => 'Therapy Director', '2' => 'Therapist']
		]) ?>
		<?= $this->Form->control('FirstName') ?>
		<?= $this->Form->control('LastName') ?>
		<?= $this->Form->control('PhoneNumber') ?>
		<?= $this->Form->control('LicenceNumber') ?>
		<?= $this->Form->control('LicenceExpiration') ?>
	</fieldset>
<?= $this->Form->button(__('Submit')); ?>
<?= $this->Form->end() ?>
</div> 