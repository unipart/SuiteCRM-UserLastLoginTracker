<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/**
 * Class for after user login "hook" to update last login date (custom field)
 */
class UserLastLoginTracker
{
    /**
     * @return bool
     */
    function updateLastLogin()
    {
        try {
            global $current_user, $db, $dictionary, $timedate;
            //error_log(__METHOD__);

            // assert we have a user object
            if (empty($current_user) or !($current_user instanceof User) or empty($current_user->id)) {
                //error_log(__METHOD__ . ' user object not found');
                return false;
            }

            // assert we have table users_cstm
            // @see cache/modules/Users/Uservardefs.php
            if (! $dictionary['User']['custom_fields']) {
                //error_log(__METHOD__ . ' table users_cstm not found');
                return false;
            }

            // assert we have field users_cstm.last_login_date_c
            // @see custom/modules/Users/Ext/Vardefs/vardefs.ext.php
            if (empty($dictionary['User']['fields']['last_login_date_c'])) {
                //error_log(__METHOD__ . ' field last_login_date_c not found');
                return false;
            }

            $id  = $current_user->id;

            //$now = date('Y-m-d H:i:s');
            $now       = $timedate->getNow($userTz = true);
            $nowDb     = $timedate->asDb($now);// using db date time format
            $nowQuoted = $db->quote($nowDb);

            // assert we have a record
            try {
                $sql1 = "INSERT INTO users_cstm (id_c) VALUES ('$id')";
                //error_log(__METHOD__ . ' query: ' . $sql1);
                $qry1 = $db->query($sql1);
                //$result1 = $qry1 ? 'inserted' : 'insert failed';
                //error_log(__METHOD__ . ' result: ' . $result1);
            } catch (Exception $ex1) {
                //error_log(__METHOD__ . ' error: ' . $ex1->getMessage());
            }

            try {
                $sql2 = "UPDATE users_cstm SET last_login_date_c = '$nowQuoted' WHERE id_c = '$id' LIMIT 1";
                //error_log(__METHOD__ . ' query: ' . $sql2);
                $qry2 = $db->query($sql2);
                //$result2 = $qry2 ? 'updated' : ' update failed';
                //error_log(__METHOD__ . ' result: ' . $result2);
                return true;
            } catch (Exception $ex2) {
                error_log(__METHOD__ . ' error: ' . $ex2->getMessage());
            }
        } catch (Exception $ex) {
            error_log(__METHOD__ . ' error: ' . $ex->getMessage());
            // ok continue, do not disturb the delicate balance of the universe!
        }
    }
}
