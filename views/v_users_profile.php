<?php if($user): ?>
    <div class = "left">
        <div id = "left_top">
            I am <?=$user->first_name?>.<br>
            I have been grunting since x.<br>
            I have grunted x many times.<br>
            I follow x, y, and z.<br>
        </div>
        <div id = "left_mid">
            // toggle follow/unfollow
        </div>
    </div>
    <div class = "center">

        <!-- add post box -->
        <div id = "center_top">
            <form method='POST' action='/posts/p_add'>
                    <textarea name='content' id='content' placeholder="<?php for ($i = 0; $i < 1; $i++) { echo 'Grunt Here' ;}?>" rows="8" cols="80"></textarea>
                    <div id = "blank" style="float:left;">I'd like to push this =></div>
                    <input type='submit' value='GRUNT' style="margin-left:0px;">

            </form>
        </div>
        <div id = "center_mid">
            // grunts
            <?php foreach($posts as $post): ?>

                <article>

                    <h1><?=$post['first_name']?> <?=$post['last_name']?> posted:</h1>

                    <p><?=$post['content']?></p>

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

