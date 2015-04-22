<?php

class HelsingborgAlarmModel {

    /**
     * Load all alarms from database
     * @return object All rows from the database as an object
     */
    public static function load_alarms() {
        global $wpdb;

        // Query the alarms
        $alarms = $wpdb->get_results("
            SELECT DISTINCT
                a.CaseId,
                a.IDnr,
                a.SentTime,
                a.PresGrp,
                a.HtText,
                a.Address,
                a.AddressDescription,
                a.Name,
                a.Zone,
                a.Position,
                a.Comment,
                a.MoreInfo,
                a.Place,
                a.BigDisturbance,
                a.SmallDisturbance,
                a.ChangeDate,
                a.Station,
                a.Cities
            FROM
                alarm_alarms a
            ORDER BY a.SentTime DESC
        ", OBJECT);

        // Return the alarms object
        return $alarms;
    }

}