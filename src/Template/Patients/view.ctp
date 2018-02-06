<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Patient'), ['action' => 'edit', $patient->id]) ?> </li>
        <li><?= $this->Html->link(__('All Patients'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Patient'), ['action' => 'add']) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Patient'), ['action' => 'delete', $patient->id], ['confirm' => __('Are you sure you want to delete # {0}?', $patient->id)]) ?> </li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($patient->FirstName . " " . $patient->LastName) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Patient ID') ?></th>
            <td><?= h($patient->PatientID) ?></td>
        </tr>
        <tr>
            <th><?= __('Date Entered') ?></th>
            <td><?= h($patient->DateEntered) ?></td>
        </tr>
        <tr>
            <th><?= __('First Name') ?></th>
            <td><?= h($patient->FirstName) ?></td>
        </tr>
        <tr>
            <th><?= __('Last Name') ?></th>
            <td><?= h($patient->LastName) ?></td>
        </tr>
        <tr>
            <th><?= __('Date of Birth') ?></th>
            <td><?= h($patient->DateOfBirth) ?></td>
        </tr>
        <tr>
            <th><?= __('Phone') ?></th>
            <td><?= h($patient->Phone) ?></td>
        </tr>
        <tr>
            <th><?= __('Address') ?></th>
            <td><?= h($patient->Address) ?></td>
        </tr>
        <tr>
            <th><?= __('City') ?></th>
            <td><?= h($patient->City) ?></td>
        </tr>
        <tr>
            <th><?= __('ZIP') ?></th>
            <td><?= h($patient->ZIP) ?></td>
        </tr>
        <tr>
            <th><?= __('Insurance Group') ?></th>
            <td><?= h($patient->InsuranceGroup) ?></td>
        </tr>
        <tr>
            <th><?= __('Case Number') ?></th>
            <td><?= h($patient->CaseNumber) ?></td>
        </tr>
        <tr>
            <th><?= __('PCP') ?></th>
            <td><?= h($patient->PCP) ?></td>
        </tr>
        <tr>
            <th><?= __('Primary Therapist') ?></th>
            <td><?= h($therapist->get($patient->PrimaryTherapist)->LastName) ?></td>
        </tr>
        <tr>
            <th><?= __('Diagnostic') ?></th>
            <td><?= h($patient->Diagnostic) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= h($patientstats->get($patient->Status)->Status) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Sessions') ?></h4>

        <?php
            echo $this->Html->link(__('Add 1 session'), ['controller' => 'Sessions', 'action' => 'add', $patient->PatientID, 1]);
            echo "\n";
            echo $this->Html->link(__('Add 2 sessions'), ['controller' => 'Sessions', 'action' => 'add', $patient->PatientID, 2]);
            echo "\n";
            echo $this->Html->link(__('Add 3 sessions'), ['controller' => 'Sessions', 'action' => 'add', $patient->PatientID, 3]);
            echo "\n";
            echo $this->Html->link(__('Add 4 sessions'), ['controller' => 'Sessions', 'action' => 'add', $patient->PatientID, 4]);
            echo "\n";
            echo $this->Html->link(__('Add 5 sessions'), ['controller' => 'Sessions', 'action' => 'add', $patient->PatientID, 5]);
            echo "\n";
            echo $this->Html->link(__('Add 6 sessions'), ['controller' => 'Sessions', 'action' => 'add', $patient->PatientID, 6]);
            echo "\n";
        ?> 

        <?php if (!empty($patient->sessions)): ?>
        Total Sessions: <?= $session->find('all', [ 'conditions' => ['PatientId =' => $patient->PatientID]])->count() ?>
        Available Sessions: <?= $session->find('all', [ 'conditions' => ['PatientId =' => $patient->PatientID, 'Status =' => 0]])->count() ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Session ID') ?></th>
                <th><?= __('Therapist') ?></th>
                <th><?= __('SessionDate') ?></th>
                <th><?= __('AuthorizedDate') ?></th>
                <th><?= __('Office') ?></th>
                <th><?= __('Status') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($patient->sessions as $session): ?>
            <tr>
                <td><?= h($session->SessionID) ?></td>
                <td><?= h($therapist->get($session->Therapist)->LastName) ?></td>
                <td><?= h($session->SessionDate) ?></td>
                <td><?= h($session->AuthorizedDate) ?></td>
                <td><?= h($office->get($session->Office)->Name) ?></td>
                <td><?= h($sessionstat->get($session->Status)->Status) ?></td>
                <td class="actions">
                    <!-- <?= $this->Html->link(__('View'), ['controller' => 'Sessions', 'action' => 'view', $session->SessionID]) ?> -->
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Sessions', 'action' => 'edit', $session->SessionID]) ?>
                    <?php if($this->request->session()->read('Auth.User.role') < 1): ?>
                        <?= $this->Form->postLink(__('Delete'), ['controller' => 'Sessions', 'action' => 'delete', $session->SessionID], ['confirm' => __('Are you sure you want to delete #{0}?', $session->SessionID)]) ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
