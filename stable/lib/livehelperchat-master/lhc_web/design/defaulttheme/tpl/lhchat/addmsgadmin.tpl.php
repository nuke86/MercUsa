<div class="message-row message-admin"><div class="msg-date"><?php echo date(erLhcoreClassModule::$dateDateHourFormat,$msg->time);?></div><span class="usr-tit op-tit"><?php echo htmlspecialchars($msg->name_support)?></span><?php echo erLhcoreClassBBCode::make_clickable(htmlspecialchars($msg->msg))?></div>