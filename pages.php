<?php
require_once 'functions.php';
?>

<?php function recent_poll_list() { ?>
    <?php $active_polls = get_active_poll() ?>
    <?php foreach ($active_polls as $poll) : ?>
        <div class="margin-20">
            <h3><?= $poll->question ?></h3>
            <p>ID: <?= $poll->id ?></p>
            <p>Created at: <?= $poll->createdAt ?></p>
            <p style="color: green">Deadline: <?= $poll->deadline ?></p>
            <!-- <ul>
                <?php foreach ($poll->options as $opt) : ?>
                    <li><?= $opt->value ?></li>
                <?php endforeach ?>
            </ul> -->
            <form action="<?= "vote_page.php" ?>" method="post">
                <input type="hidden" name="vote_id" value="<?= $poll->id ?>">
                <button type="submit">VOTE</button>
            </form>
            <hr>
        </div>
    <?php endforeach ?>
<?php } ?>

<?php function past_poll_list() { ?>
    <?php $past_polls = get_past_poll() ?>
    <?php foreach ($past_polls as $poll) : ?>
        <div class="margin-20">
            <p><?= $poll->question ?></p>
            <ul>
                <?php foreach ($poll->options as $opt) : ?>
                    <li><?= $opt->value . ' => ' . $opt->percent ?></li>
                <?php endforeach ?>
            </ul>
            <hr>
        </div>
    <?php endforeach ?>
<?php } ?>

<?php function page_login($origin){ ?>
    <h2>Login</h2>
    <form action="query_login.php" method="POST">
        <input type="hidden" name="origin" value="<?=$origin?>">
        Username: <input name="uname"> <br>
        Password: <input name="pword" type="password"> <br>
        <input type="submit" value="Login">
    </form>
<?php } //end page_login ?>

<?php function page_register($origin, $kept_data){ ?>
    <?php
        $is_kept = $kept_data != null;
    ?>
    <h2>Register</h2>
    <form action="query_register.php" method="POST">
        <input type="hidden" name="origin" value="<?=$origin?>">

        Username: <input name="uname" value="<?=$is_kept ? $kept_data->uname : ''?>"> <br>
        
        Email: <input name="email" value="<?=$is_kept ? $kept_data->email : ''?>"> <br>
        
        Password: <input name="pword1" type="password"> <br>
        
        Password again: <input name="pword2" type="password"> <br>

        <input type="submit" value="Register">
    </form>
<?php } //end page_register ?>

<?php function page_profile($user) { ?>
    <p style="padding: 2px;color:blue;"><?= 'USERNAME: '.$user->uname ?> &nbsp | &nbsp<?= 'ROLE: '.$user->role ?></p>
<?php } ?>

<?php function page_errors($errors){ ?>
    <?php if(count($errors ?? []) == 0) return ?>

    <?php $error_dict = json_read('database/errors.json') ?>
    <h2>Error!</h2>
    <ul>
        <?php foreach($errors as $error): ?>
            <li><?=$error_dict->$error?></li>
        <?php endforeach ?>
    </ul>
<?php } // end page_errors ?>