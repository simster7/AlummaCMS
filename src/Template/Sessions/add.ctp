<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
    </ul>
</nav>
<div class="sessions form large-9 medium-8 columns content">
    <?= $add_text ?>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Add Session') ?></legend>
        <?php
            echo $this->Form->control('PatientID', ['default' => $PatientID]);
            echo $this->Form->control('Therapist', ['options' => $ather, 'default' => ($def_ther == '-1') ? 99 : $def_ther]);
            echo $this->Form->control('SessionDate', ['type' => 'date', 'empty' => true]);
            echo $this->Form->control('AuthorizedDate', ['type' => 'date']);
            echo $this->Form->control('Status', ['options' => $astat]);
            echo $this->Form->control('Office', ['options' => $aoff]);
            echo $this->Form->control('AuthorizationNumber');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
