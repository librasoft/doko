<?php
use Cake\Core\Configure;
?>
<?php if (empty($_COOKIE['cookie-policy'])): ?>
<div class="cookie-policy">
    <div class="policy container">
        <p class="policy-statement"><?php echo __d('Doko', 'By using this site you agree to the use of cookies for analysis and personalized content.'); ?></p>
        <ul class="policy-actions">
            <li class="learnmore"><a class="btn" href="<?php echo $this->Url->build([
                'action' => 'view',
                'slug' => __d('Doko', 'privacy'),
            ]); ?>"><?php echo __d('Doko', 'Learn more'); ?></a></li>
            <li class="accept"><button class="btn"><?php echo __d('Doko', 'I understand'); ?></button></li>
        </ul>
    </div>
</div>
<?php endif;
