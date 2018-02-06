<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
    </ul>
</nav>
<div class="sessions form large-9 medium-8 columns content">
    <?= $this->Form->create($session) ?>
    <fieldset>
        <legend><?= __('Edit Session') ?></legend>
        <?php
            if($this->request->session()->read('Auth.User.role') < 2) {
                echo $this->Form->control('Therapist', ['options' => $ather]);
                echo $this->Form->control('AuthorizedDate');
                echo $this->Form->control('Status', ['options' => $astat]);
                echo $this->Form->control('SessionDate', ['empty' => true]);
                echo $this->Form->control('Office', ['options' => $aoff]);
            } else {
                // If approved
                if ($session->Status == 0) {
                    echo $this->Form->control('Status', ['options' => [0 => $astat[0], 1 => $astat[1]]]);
                    echo $this->Form->control('SessionDate', ['empty' => true]);
                    echo $this->Form->control('Office', ['options' => $aoff]);
                    echo "Please double check that you entered the information correctly. Once a session is marked as \"Scheduled\" no changes to the Date or Office can be made. If there has been an error please contact an administrator.";
                } else if ($session->Status == 1) {
                    echo $this->Form->control('Status', ['options' => [1 => $astat[1], 2 => $astat[2], 6 => $astat[6]]]);
                    echo "No other changes can be made. If you need to reschedule this session, mark it as \"Cancelled\" and wait for an administrator to reapprove it. If there has been other errors please contact an administrator.";
                } else {
                    echo "No further changes can be made. If there has been an error please contact an administrator.";
                }
            }
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
