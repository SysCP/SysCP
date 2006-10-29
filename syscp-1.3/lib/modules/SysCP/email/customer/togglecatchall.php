<?php
$result = $this->DatabaseHandler->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");

if(isset($result['email'])
   && $result['email'] != '')
{
    if($result['iscatchall'] == '1')
    {
        $this->DatabaseHandler->query("UPDATE `".TABLE_MAIL_VIRTUAL."` SET `email` = '".$result['email_full']."', `iscatchall` = '0' WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$result['id']."'");
    }
    else
    {
        $email_parts = explode('@', $result['email_full']);
        $email = '@'.$email_parts[1];
        $email_check = $this->DatabaseHandler->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `email`='$email' AND `customerid`='".$this->User['customerid']."'");

        if($email_check['email'] == $email)
        {
            $this->TemplateHandler->showError('SysCP.email.error.youhavealreadyacatchallforthisdomain');
            return false;
        }
        else
        {
            $this->DatabaseHandler->query("UPDATE `".TABLE_MAIL_VIRTUAL."` SET `email` = '$email' , `iscatchall` = '1' WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$result['id']."'");
        }
    }

    $this->redirectTo(array(
        'module' => 'email',
        'action' => 'edit',
        'id' => $this->ConfigHandler->get('env.id')
    ));
}

