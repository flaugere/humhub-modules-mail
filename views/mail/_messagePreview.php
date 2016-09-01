<?php

/**
 * Shows a  preview of given $userMessage (UserMessage).
 *
 * This can be the notification list or the message navigation
 */
use yii\helpers\Html;
use humhub\widgets\TimeAgo;
use humhub\libs\Helpers;
use humhub\widgets\MarkdownView;

$message = $userMessage->message;
$userId = \Yii::$app->user->id;
$isLastEntryOwner = $message->getLastEntry()->user->id == Yii::$app->user->id;
?>

<?php if ($message->getLastEntry() != null) : ?>
    <li class="messagePreviewEntry_<?php echo $message->id; ?> messagePreviewEntry entry">
        <a href="javascript:loadMessage('<?php echo $message->id; ?>');">
            <div class="media">
                <img class="media-object img-rounded pull-left" data-src="holder.js/32x32" alt="32x32" style="width: 32px; height: 32px;" src="<?php echo $message->getInterlocutorFor($userId)->getProfileImage()->getUrl(); ?>">
                <div class="media-body text-break">
                    <h4 class="media-heading"><?php echo Html::encode($message->getInterlocutorFor($userId)->displayName); ?> <small><?php echo TimeAgo::widget(['timestamp' => $message->updated_at]); ?></small></h4>
                    <h5><?php echo Html::encode(Helpers::truncateText($message->title, 75)); ?></h5>
                    <?php if ($isLastEntryOwner) {?><i class="fa fa-reply"></i> <?php } ?><?php echo Helpers::truncateText(MarkdownView::widget(['markdown' => $message->getLastEntry()->content, 'parserClass' => '\humhub\libs\MarkdownPreview', 'returnPlain' => true]), 200); ?>
                    <?php
                    // show the new badge, if this message is still unread
                    if ($message->updated_at > $userMessage->last_viewed && !$isLastEntryOwner) {
                        echo '<span class="label label-danger">' . Yii::t('MailModule.views_mail_index', 'New') . '</span>';
                    }
                    ?>
                </div>
            </div>
        </a>
    </li>
<?php endif; ?>
