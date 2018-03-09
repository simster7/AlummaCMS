<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'Alumma San Diego';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>

    <!-- <?= $this->Html->css('bootstrap.css') ?> -->

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area large-3 medium-4 columns">
            <li class="name">
                <h1><a href=""><?= $this->fetch('title') ?></a></h1>
            </li>
        </ul>
        <div class="top-bar-section">
            <ul class="left">
                <?php if($loggedIn) : ?>
                    <li><?= $this->Html->link('Patients', ['controller' => 'patients']); ?></li>
                    <li><?= $this->Html->link('My Week', ['controller' => 'MyWeek']); ?></li>
                   <!-- <li><?= $this->Html->link('My Schedule', ['controller' => 'calendars', 'action' => 'schedule']); ?></li> -->
                    <li><?= $this->Html->link('Office Schedules', ['controller' => 'calendars', 'action' => 'offices']); ?></li>
                    <?php if($this->request->session()->read('Auth.User.role') < 1): ?>
                        <li><?= $this->Html->link('Sessions', ['controller' => 'sessions']); ?></li>
                        <li><?= $this->Html->link('Users', ['controller' => 'users']); ?></li>
                    <?php endif;?>
                <?php endif;?>
            </ul>
            <ul class="right">
                <?php if($loggedIn) : ?>
                    <li><?= $this->Html->link('My Account', ['controller' => 'users', 'action' => 'edit', $this->request->session()->read('Auth.User.id')]) ?></li>
                    <li><?= $this->Html->link('Log Out', ['controller' => 'users', 'action' => 'logout']); ?></li>
                <?php endif;?>
            </ul>
        </div>
    </nav>
    <?= $this->Flash->render() ?>
    <div class="container clearfix">
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
</body>
</html>
