<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Patient'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="users index large-9 medium-8 columns content">
    <h3><?= __('All Patients') ?></h3>
    <table>
        <tr>
            <?= $this->Form->create(null,['url' => ['action' => 'search']]) ?>
                <?php if($this->request->session()->read('Auth.User.role') < 2): ?>
                    <th><?= $this->Form->select('ther', $ather, ['empty' => 'Search by Therapist']) ?></th>
                <?php endif;?>
            <th><?= $this->Form->text('query') ?></th>
            <th><?= $this->Form->button('Search') ?></th>
            <?= $this->Form->end() ?>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('PatientID') ?></th>
                <th><?= $this->Paginator->sort('FirstName') ?></th>
                <th><?= $this->Paginator->sort('LastName') ?></th>
                <th><?= $this->Paginator->sort('DateOfBirth') ?></th>
                <th><?= $this->Paginator->sort('PrimaryTherapist') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($patients as $patient): ?>
            <tr>
                <td><?= h($patient->PatientID) ?></td>
                <td><?= h($patient->FirstName) ?></td>
                <td><?= h($patient->LastName) ?></td>
                <td><?= h($patient->DateOfBirth) ?></td>
                <td><?= h($therapist->get($patient->PrimaryTherapist)->LastName) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $patient->id]) ?>
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
