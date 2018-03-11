<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $patient->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $patient->id)]
            )
        ?></li>
    </ul>
</nav>
<div class="patients form large-9 medium-8 columns content">
    <?= $this->Form->create($patient) ?>
    <fieldset>
        <legend><?= __('Add Patient') ?></legend>
        <?php
            echo $this->Form->control('PatientID', ['required' => true]);
            echo $this->Form->control('FirstName');
            echo $this->Form->control('LastName');
            echo $this->Form->control('DateOfBirth', ['minYear' => date('Y') - 70, 'maxYear' => date('Y')]) ;
            echo $this->Form->control('Phone');
            echo $this->Form->control('Phone2');
            echo $this->Form->control('Address');
            echo $this->Form->control('City');
            echo $this->Form->control('ZIP');
            echo $this->Form->control('DateEntered');
            echo $this->Form->control('InsuranceGroup');
            echo $this->Form->control('CaseNumber');
            echo $this->Form->control('PCP');
            echo $this->Form->control('PrimaryTherapist', ['options' => $ather, 'default' => 99]);
            echo $this->Form->control('Office', ['options' => $aoff, 'default' => 3]);
            echo $this->Form->control('DateEntered');
            echo $this->Form->control('Diagnostic');
            echo $this->Form->control('Status', ['options' => $astat]);
            echo $this->Form->input('Add a diagnostic session', ['type' => 'checkbox']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
