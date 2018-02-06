<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sesion'), ['action' => 'edit', $session->id]) ?> </li>
        <li><?= $this->Html->link(__('New Session'), ['action' => 'add']) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Session'), ['action' => 'delete', $session->id], ['confirm' => __('Are you sure you want to delete # {0}?', $session->id)]) ?> </li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($session->PatientID) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Patient ID') ?></th>
            <td><?= h($session->PatientID) ?></td>
        </tr>
        <tr>
            <th><?= __('Patient Name') ?></th>
                <?php
                    $pat = $patient->find('all', ['conditions' => ['PatientID =' => $session->PatientID]])->first();
                    $fullName = $pat->FirstName . " " . $pat->LastName;
                ?>
            <td><?= h($fullName) ?></td>
        </tr>
        <tr>
            <th><?= __('Therapist') ?></th>
            <td><?= h($therapist->get($session->Therapist)->LastName) ?></td>
        </tr>
        <tr>
            <th><?= __('SessionDate') ?></th>
            <td><?= h($session->SesionDate) ?></td>
        </tr>
        <tr>
            <th><?= __('AuthorizedDate') ?></th>
            <td><?= h($session->AuthorizedDate) ?></td>
        </tr>
        <tr>
            <th><?= __('Office') ?></th>
            <td><?= h($office->get($session->Office)->Name) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= h($session->PatientID) ?></td>
        </tr>
    </table>
</div>
