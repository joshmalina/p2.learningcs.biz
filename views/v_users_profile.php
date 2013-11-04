<?php if($user): ?>
    <div class = "left">
        <div id = "left_top">
            I am <?=$user->first_name?>.<br>
            I have grunted <?=$post_num?> times.<br>
        </div>
        <div id = "left_mid">
            <!-- toggle follow/unfollow -->
            <?php foreach($users as $user): ?>

                <!-- Print this user's name -->
                <?=$user['first_name']?> <?=$user['last_name']?>

                <!-- If there exists a connection with this user, show a unfollow link -->
                <?php if(isset($connections[$user['user_id']])): ?>
                    <a href='/posts/unfollow/<?=$user['user_id']?>'>Unfollow</a>

                    <!-- Otherwise, show the follow link -->
                <?php else: ?>
                    <a href='/posts/follow/<?=$user['user_id']?>'>Follow</a>
                <?php endif; ?>

                <br><br>

            <?php endforeach; ?>
        </div>
    </div>
    <div class = "center">

        <!-- add post box -->
        <div id = "center_top">
            <form method='POST' action='/posts/p_add'>
                    <textarea name='content' id='content' placeholder="Grunt Here" rows="8" cols="83"></textarea>
                    <input type='submit' value='GRUNT' style="width:100px;margin:auto;">

            </form>
        </div>
        <div id = "center_mid">
            <!-- grunts -->
            <?php foreach($posts as $post): ?>

                <article>

                    <h1><?=$post['first_name']?> <?=$post['last_name']?>: <label = "post"><?=$post['content']?></label></h1>

                    <time datetime="<?=Time::display($post['created'],'Y-m-d G:i')?>">
                        <?=Time::display($post['created'])?>
                    </time>

                </article>

            <?php endforeach; ?>
        </div>
    </div>



<?php else:?>
    <h1>This shouldn't be seen.</h1>
<?php endif;?>

