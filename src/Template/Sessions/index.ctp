<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Session'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="users index large-9 medium-8 columns content">
    <h3><?= __('All Sessions') ?></h3>
    <table>
    <tr>
    <?= $this->Form->create(null,['url' => ['action' => 'search']]) ?>
    <th><?= $this->Form->select('status', $astat, ['empty' => 'Search by Status']) ?></th>
    <th><?= $this->Form->button('Search') ?></th>
    <?= $this->Form->end() ?>
    </tr>
    </table>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('SessionID') ?></th>
                <th><?= $this->Paginator->sort('PatientID') ?></th>
                <th><?= $this->Paginator->sort('Patient Name') ?></th>
                <th><?= $this->Paginator->sort('Therapist') ?></th>
                <th><?= $this->Paginator->sort('SessionDate') ?></th>
                <th><?= $this->Paginator->sort('AuthorizedDate') ?></th>
                <th><?= $this->Paginator->sort('Office') ?></th>
                <th><?= $this->Paginator->sort('Status') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sessions as $session): ?>
            <tr>
                <?php
                    $pat = $patient->find('all', ['conditions' => ['PatientID =' => $session->PatientID]])->first();
                    $fullName = $pat->FirstName . " " . $pat->LastName;
                ?>
                <td><?= h($session->SessionID) ?></td>
                <td><?= h($session->PatientID) ?></td>
                <td><?= h($fullName) ?></td>
                <td><?= h($therapist->get($session->Therapist)->LastName) ?></td>
                <td><?= h($session->SessionDate) ?></td>
                <td><?= h($session->AuthorizedDate) ?></td>
                <td><?= h($session->Office) ?></td>
                <td><?= h($sessionstat->get($session->Status)->Status) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $session->SessionID]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $session->SessionID]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $session->SessionID], ['confirm' => __('Are you sure you want to delete #{0}?', $session->SessionID)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
