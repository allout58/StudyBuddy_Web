<?php

function getFriendsForUser($uid, PDO $dbo)
{
    $friends = array();
    $friends['connected'] = array();
    $friends['my_requests'] = array();
    $friends['their_requests'] = array();

    $prep_sel_friends1 = $dbo->prepare("SELECT realName, firebase_uid, locationID, subID, blurb, endTime, imageURL, confirmed FROM Users INNER JOIN Friends ON Users.firebase_uid = Friends.requester WHERE Friends.requestee=:uid");
    $prep_sel_friends1->bindValue(":uid", $uid);
    $prep_sel_friends1->execute();
    while (($row = $prep_sel_friends1->fetch(PDO::FETCH_ASSOC))) {
        if ($row['confirmed'] == 1) {
            array_push($friends['connected'], $row);
        } else {
            unset($row['locationID']);
            unset($row['subID']);
            unset($row['blurb']);
            unset($row['endTime']);
            array_push($friends['their_requests'], $row);
        }
    }

    $prep_sel_friends2 = $dbo->prepare("SELECT realName, firebase_uid, locationID, subID, blurb, endTime, imageURL, confirmed FROM Users INNER JOIN Friends ON Users.firebase_uid = Friends.requestee WHERE Friends.requester=:uid");
    $prep_sel_friends2->bindValue(":uid", $uid);
    $prep_sel_friends2->execute();
    while (($row = $prep_sel_friends2->fetch(PDO::FETCH_ASSOC))) {
        if ($row['confirmed'] == 1) {
            array_push($friends['connected'], $row);
        } else {
            unset($row['locationID']);
            unset($row['subID']);
            unset($row['blurb']);
            unset($row['endTime']);
            array_push($friends['my_requests'], $row);
        }
    }
    return $friends;
}