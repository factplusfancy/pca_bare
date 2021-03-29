<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This class is to get and display information from t0history, etc.

class HistoryGetZfpf {

    // Returns array of all t0history records on a single row (with $PrimaryKey) in the database table named $TableName.
    // $Zfpf must have CoreZfpf (so it is an object that is an instance of at least the CoreZfpf class).
    public function one_row_h($Zfpf, $TableName, $PrimaryKey) {
        $Conditions[0] = array('c2table_name', '=', $TableName, 'AND');
        $Conditions[1] = array('k0_1st_in_row_affected', '=', $PrimaryKey);
        return $Zfpf->one_shot_select_1s('t0history', $Conditions);
    }

    // Returns array of all t0history records created by a k0user
    // between $TimeStart and $TimeEnd.
    // $Zfpf must have CoreZfpf
    public function by_user_h($Zfpf, $k0user) {
        $Conditions[0] = array('k0user', '=', $k0user);
        return $Zfpf->one_shot_select_1s('t0history', $Conditions);
    }
    /*
    // TO DO draft/incomplete code for adding a time condition
        if ($TimeStart and $TimeEnd) { // TO DO convert string to time verify is numeric, error otherwise?
            $Conditions[] = array('c1ts_changed', '>=', $TimeStart, 'AND', 'AND');
            $Conditions[] = array('c1ts_changed', '<=', $TimeEnd);
        }
        elseif ($TimeStart)
            $Conditions[] = array('c1ts_changed', '>=', $TimeStart, '', 'AND');
        elseif ($TimeEnd)
            $Conditions[] = array('c1ts_changed', '<=', $TimeEnd, '', 'AND');
    */

    // $Zfpf must have CoreZfpf
    public function by_user_process_h($Zfpf, $k0user, $k0process) {
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Conditions[0] = array('k0user', '=', $k0user, 'AND (');
        $Conditions[] = array('c2table_name', '=', 't0user_process', 'AND', '(');
        $Conditions[] = array('k0_3rd_in_row_affected', '=', $k0process, ')');
        $Conditions[] = array('c2table_name', '=', 't0process', 'AND', 'OR (');
        $Conditions[] = array('k0_1st_in_row_affected', '=', $k0process, ')');
        $Conditions[] = array('c2table_name', '=', 't0user_practice', 'AND', 'OR (');
        $Conditions[] = array('k0_7th_in_row_affected', '=', $k0process, ')');
        $Conditions[] = array('c2table_name', '=', 't0change_management', 'AND', 'OR (');
        $Conditions[] = array('k0_2nd_in_row_affected', '=', $k0process, ')');
        $Conditions[] = array('c2table_name', '=', 't0action', 'AND', 'OR (');
        $Conditions[] = array('k0_2nd_in_row_affected', '=', $k0process, ')');
        $Conditions[] = array('c2table_name', '=', 't0training_form', 'AND', 'OR (');
        $Conditions[] = array('k0_2nd_in_row_affected', '=', $k0process, '))'); // '))' second one closes very first 'AND ('
        $SR = array(); // In case nothing returned from first select below.
        list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
        if ($RRA)
            $SR = array_merge($SR, $SRA);
        // Loop through results from tables with subordinate tables.
        // t0process_practice...
        unset($Conditions);
        $Conditions[0] = array('k0process', '=', $k0process);
        list($SRB, $RRB) = $Zfpf->select_sql_1s($DBMSresource, 't0process_practice', $Conditions); // Returns t0process_practice rows associated with passed-in k0process. Needed to catch t0process_practice that the user hasn't changed.
        $Conditions[0] = array('k0user', '=', $k0user, 'AND');
        $Conditions[] = array('c2table_name', '=', 't0process_practice', 'AND');
        $Conditions[] = array('k0_2nd_in_row_affected', '=', $k0process);
        list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions); // Returns t0history rows for changes user made to t0process_practice rows with passed-in k0process. Needed to catch t0process_practice rows that have been deleted by the user (or deleted by others but which the user changed before they were deleted.)
        if ($RRA or $RRB) {
            unset($Conditions);
            $Conditions[0] = array('k0user', '=', $k0user, 'AND');
            $Conditions[] = array('c2table_name', '=', 't0practice', 'AND (');
            $KeyArray = array();
            if ($RRA) {
                $SR = array_merge($SR, $SRA);
                foreach ($SRA as $VA)
                    if (!in_array($VA['k0_3rd_in_row_affected'], $KeyArray)) {
                        $KeyArray[] = $VA['k0_3rd_in_row_affected'];
                        $Conditions[] = array('k0_1st_in_row_affected', '=', $VA['k0_3rd_in_row_affected'], 'OR');
                    }
            }
            if ($RRB) foreach ($SRB as $VB)
                if (!in_array($VB['k0practice'], $KeyArray))
                    $Conditions[] = array('k0_1st_in_row_affected', '=', $VB['k0practice'], 'OR');
            $LastArrayKey = count($Conditions) - 1;
            $Conditions[$LastArrayKey][3] = ')';
            list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions); // Returns t0history rows for changes user made to t0practice rows associated via t0process_practice (current or deleted) with passed-in k0process.
            if ($RRA)
                $SR = array_merge($SR, $SRA);
        }
        // t0pha...
        unset($Conditions);
        $Conditions[0] = array('k0process', '=', $k0process);
        list($SRB, $RRB) = $Zfpf->select_sql_1s($DBMSresource, 't0pha', $Conditions);
        $Conditions[0] = array('k0user', '=', $k0user, 'AND');
        $Conditions[] = array('c2table_name', '=', 't0pha', 'AND');
        $Conditions[] = array('k0_2nd_in_row_affected', '=', $k0process);
        list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
        if ($RRA or $RRB) { // t0subprocess
            unset($Conditions);
            $Conditions[0] = array('k0user', '=', $k0user, 'AND');
            $Conditions[] = array('c2table_name', '=', 't0subprocess', 'AND (');
            $KeyArray = array();
            if ($RRA) {
                $SR = array_merge($SR, $SRA);
                foreach ($SRA as $VA)
                    if (!in_array($VA['k0_1st_in_row_affected'], $KeyArray)) {
                        $KeyArray[] = $VA['k0_1st_in_row_affected'];
                        $Conditions[] = array('k0_2nd_in_row_affected', '=', $VA['k0_1st_in_row_affected'], 'OR');
                    }
            }
            if ($RRB) foreach ($SRB as $VB)
                if (!in_array($VB['k0pha'], $KeyArray)) {
                    $KeyArray[] = $VB['k0pha'];
                    $Conditions[] = array('k0_2nd_in_row_affected', '=', $VB['k0pha'], 'OR');
                }
            $LastArrayKey = count($Conditions) - 1;
            $Conditions[$LastArrayKey][3] = ')';
            list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
            if ($KeyArray) {
                unset($Conditions);
                foreach ($KeyArray as $V)
                    $Conditions[] = array('k0pha', '=', $V, 'OR');
                $LastArrayKey = count($Conditions) - 1;
                unset($Conditions[$LastArrayKey][3]);

                list($SRB, $RRB) = $Zfpf->select_sql_1s($DBMSresource, 't0subprocess', $Conditions);
            } // t0scenario
            if ($RRA or $RRB) {
                unset($Conditions);
                $Conditions[0] = array('k0user', '=', $k0user, 'AND');
                $Conditions[] = array('c2table_name', '=', 't0scenario', 'AND (');
                $KeyArray = array();
                if ($RRA) {
                    $SR = array_merge($SR, $SRA);
                    foreach ($SRA as $VA)
                        if (!in_array($VA['k0_1st_in_row_affected'], $KeyArray)) {
                            $KeyArray[] = $VA['k0_1st_in_row_affected'];
                            $Conditions[] = array('k0_2nd_in_row_affected', '=', $VA['k0_1st_in_row_affected'], 'OR');
                        }
                }
                if ($RRB) foreach ($SRB as $VB)
                    if (!in_array($VB['k0subprocess'], $KeyArray)) {
                        $KeyArray[] = $VB['k0subprocess'];
                        $Conditions[] = array('k0_2nd_in_row_affected', '=', $VB['k0subprocess'], 'OR');
                    }
                $LastArrayKey = count($Conditions) - 1;
                $Conditions[$LastArrayKey][3] = ')';
                list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
                if ($KeyArray) {
                    unset($Conditions);
                    foreach ($KeyArray as $V)
                        $Conditions[] = array('k0subprocess', '=', $V, 'OR');
                    $LastArrayKey = count($Conditions) - 1;
                    unset($Conditions[$LastArrayKey][3]);
                    list($SRB, $RRB) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario', $Conditions);
                } // t0scenario_cause...
                if ($RRA or $RRB) {
                    $ScenarioCCSConditions[0] = array('k0user', '=', $k0user, 'AND');
                    $ScenarioCCSConditions[1] = array('c2table_name', '=', 't0scenario_cause', 'AND (');
                    $KeyArray = array();
                    if ($RRA) {
                        $SR = array_merge($SR, $SRA);
                        foreach ($SRA as $VA)
                            if (!in_array($VA['k0_1st_in_row_affected'], $KeyArray)) {
                                $KeyArray[] = $VA['k0_1st_in_row_affected'];
                                $ScenarioCCSConditions[] = array('k0_2nd_in_row_affected', '=', $VA['k0_1st_in_row_affected'], 'OR');
                            }
                    }
                    if ($RRB) foreach ($SRB as $VB)
                        if (!in_array($VB['k0scenario'], $KeyArray)) {
                            $KeyArray[] = $VB['k0scenario'];
                            $ScenarioCCSConditions[] = array('k0_2nd_in_row_affected', '=', $VB['k0scenario'], 'OR');
                        }
                    $LastArrayKey = count($ScenarioCCSConditions) - 1;
                    $ScenarioCCSConditions[$LastArrayKey][3] = ')';
                    list($SRB, $RRB) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $ScenarioCCSConditions);
                    $RRC = 0;
                    if ($KeyArray) {
                        foreach ($KeyArray as $V)
                            $ScenarioConditions[] = array('k0scenario', '=', $V, 'OR');
                        $LastArrayKey = count($ScenarioConditions) - 1;
                        unset($ScenarioConditions[$LastArrayKey][3]);
                        list($SRC, $RRC) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario_cause', $ScenarioConditions);
                    } // t0cause
                    if ($RRB or $RRC) {
                        unset($Conditions);
                        $Conditions[0] = array('k0user', '=', $k0user, 'AND');
                        $Conditions[] = array('c2table_name', '=', 't0cause', 'AND (');
                        $KeyArray = array();
                        if ($RRB) {
                            $SR = array_merge($SR, $SRB);
                            foreach ($SRB as $VB)
                                if (!in_array($VA['k0_3rd_in_row_affected'], $KeyArray)) {
                                    $KeyArray[] = $VA['k0_3rd_in_row_affected'];
                                    $Conditions[] = array('k0_1st_in_row_affected', '=', $VB['k0_3rd_in_row_affected'], 'OR');
                                }
                        }
                        if ($RRC) foreach ($SRC as $VC)
                                if (!in_array($VC['k0cause'], $KeyArray))
                                    $Conditions[] = array('k0_1st_in_row_affected', '=', $VC['k0cause'], 'OR');
                        $LastArrayKey = count($Conditions) - 1;
                        $Conditions[$LastArrayKey][3] = ')';
                        list($SRB, $RRB) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
                        if ($RRB)
                            $SR = array_merge($SR, $SRB);
                    } // t0scenario_consequence
                    $ScenarioCCSConditions[1] = array('c2table_name', '=', 't0scenario_consequence', 'AND (');
                    list($SRB, $RRB) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $ScenarioCCSConditions);
                    if (isset($ScenarioConditions))
                        list($SRC, $RRC) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario_consequence', $ScenarioConditions);
                    if ($RRB or $RRC) { // t0consequence
                        unset($Conditions);
                        $Conditions[0] = array('k0user', '=', $k0user, 'AND');
                        $Conditions[] = array('c2table_name', '=', 't0consequence', 'AND (');
                        $KeyArray = array();
                        if ($RRB) {
                            $SR = array_merge($SR, $SRB);
                            foreach ($SRB as $VB)
                                if (!in_array($VA['k0_3rd_in_row_affected'], $KeyArray)) {
                                    $KeyArray[] = $VA['k0_3rd_in_row_affected'];
                                    $Conditions[] = array('k0_1st_in_row_affected', '=', $VB['k0_3rd_in_row_affected'], 'OR');
                                }
                        }
                        if ($RRC) foreach ($SRC as $VC)
                                if (!in_array($VC['k0consequence'], $KeyArray))
                                    $Conditions[] = array('k0_1st_in_row_affected', '=', $VC['k0consequence'], 'OR');
                        $LastArrayKey = count($Conditions) - 1;
                        $Conditions[$LastArrayKey][3] = ')';
                        list($SRB, $RRB) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
                        if ($RRB)
                            $SR = array_merge($SR, $SRB);
                    } // t0scenario_safeguard
                    $ScenarioCCSConditions[1] = array('c2table_name', '=', 't0scenario_safeguard', 'AND (');
                    list($SRB, $RRB) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $ScenarioCCSConditions);
                    if (isset($ScenarioConditions))
                        list($SRC, $RRC) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario_safeguard', $ScenarioConditions);
                    if ($RRB or $RRC) { // t0safeguard
                        unset($Conditions);
                        $Conditions[0] = array('k0user', '=', $k0user, 'AND');
                        $Conditions[] = array('c2table_name', '=', 't0safeguard', 'AND (');
                        $KeyArray = array();
                        if ($RRB) {
                            $SR = array_merge($SR, $SRB);
                            foreach ($SRB as $VB)
                                if (!in_array($VA['k0_3rd_in_row_affected'], $KeyArray)) {
                                    $KeyArray[] = $VA['k0_3rd_in_row_affected'];
                                    $Conditions[] = array('k0_1st_in_row_affected', '=', $VB['k0_3rd_in_row_affected'], 'OR');
                                }
                        }
                        if ($RRC) foreach ($SRC as $VC)
                                if (!in_array($VC['k0safeguard'], $KeyArray))
                                    $Conditions[] = array('k0_1st_in_row_affected', '=', $VC['k0safeguard'], 'OR');
                        $LastArrayKey = count($Conditions) - 1;
                        $Conditions[$LastArrayKey][3] = ')';
                        list($SRB, $RRB) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
                        if ($RRB)
                            $SR = array_merge($SR, $SRB);
                    } // t0scenario_action
                    $ScenarioCCSConditions[1] = array('c2table_name', '=', 't0scenario_action', 'AND (');
                    list($SRB, $RRB) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $ScenarioCCSConditions);
                    if ($RRB)
                        $SR = array_merge($SR, $SRB);
                } // t0action history is queried directly, via k0affected_entity (k0_2nd_in_row_affected), in the first t0history query above.
            }
        }
        // t0audit...
        unset($Conditions);
        $Conditions[0] = array('k0process', '=', $k0process);
        list($SRAuB, $RRAuB) = $Zfpf->select_sql_1s($DBMSresource, 't0audit', $Conditions);
        $Conditions[0] = array('k0user', '=', $k0user, 'AND');
        $Conditions[] = array('c2table_name', '=', 't0audit', 'AND');
        $Conditions[] = array('k0_2nd_in_row_affected', '=', $k0process);
        list($SRAuA, $RRAuA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
        if ($RRAuA or $RRAuB) {
            unset($Conditions);
            $Conditions[0] = array('k0user', '=', $k0user, 'AND');
            $Conditions[] = array('c2table_name', '=', 't0audit_obstopic', 'AND (');
            $KeyArray = array();
            if ($RRAuA) {
                $SR = array_merge($SR, $SRAuA); // t0audit history
                foreach ($SRAuA as $VA)
                    if (!in_array($VA['k0_1st_in_row_affected'], $KeyArray)) {
                        $KeyArray[] = $VA['k0_1st_in_row_affected'];
                        $Conditions[] = array('k0_2nd_in_row_affected', '=', $VA['k0_1st_in_row_affected'], 'OR');
                    }
            }
            if ($RRAuB) foreach ($SRAuB as $VB)
                if (!in_array($VB['k0audit'], $KeyArray)) {
                    $KeyArray[] = $VB['k0audit'];
                    $Conditions[] = array('k0_2nd_in_row_affected', '=', $VB['k0audit'], 'OR');
                }
            $LastArrayKey = count($Conditions) - 1;
            $Conditions[$LastArrayKey][3] = ')';
            list($SRAuOtA, $RRAuOtA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
            if ($KeyArray) {
                unset($Conditions);
                foreach ($KeyArray as $V)
                    $Conditions[] = array('k0audit', '=', $V, 'OR');
                $LastArrayKey = count($Conditions) - 1;
                unset($Conditions[$LastArrayKey][3]);
                list($SRAuOtB, $RRAuOtB) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_obstopic', $Conditions);
            }
            if ($RRAuOtA or $RRAuOtB) {
                unset($Conditions);
                $Conditions[0] = array('k0user', '=', $k0user, 'AND');
                $Conditions[] = array('c2table_name', '=', 't0obstopic', 'AND (');
                $KeyArray = array();
                if ($RRAuOtA) {
                    $SR = array_merge($SR, $SRAuOtA); // t0audit_obstopic history
                    foreach ($SRAuOtA as $VA)
                        if (!in_array($VA['k0_3rd_in_row_affected'], $KeyArray)) {
                            $KeyArray[] = $VA['k0_3rd_in_row_affected'];
                            $Conditions[] = array('k0_1st_in_row_affected', '=', $VA['k0_3rd_in_row_affected'], 'OR');
                        }
                }
                if ($RRAuOtB) foreach ($SRAuOtB as $VB)
                    if (!in_array($VB['k0obstopic'], $KeyArray)) {
                        $KeyArray[] = $VB['k0obstopic'];
                        $Conditions[] = array('k0_1st_in_row_affected', '=', $VB['k0obstopic'], 'OR');
                    }
                $LastArrayKey = count($Conditions) - 1;
                $Conditions[$LastArrayKey][3] = ')';
                list($SROtA, $RROtA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
                if ($KeyArray) {
                    unset($Conditions);
                    foreach ($KeyArray as $V)
                        $Conditions[] = array('k0obstopic', '=', $V, 'OR');
                    $LastArrayKey = count($Conditions) - 1;
                    unset($Conditions[$LastArrayKey][3]);
                    list($SROtB, $RROtB) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic', $Conditions);
                }
                if ($RROtA or $RROtB) {
                    unset($Conditions);
                    $Conditions[0] = array('k0user', '=', $k0user, 'AND');
                    $Conditions[] = array('c2table_name', '=', 't0obstopic_obsmethod', 'AND (');
                    $KeyArray = array();
                    if ($RROtA) {
                        $SR = array_merge($SR, $SROtA); // t0obstopic history
                        foreach ($SROtA as $VA)
                            if (!in_array($VA['k0_1st_in_row_affected'], $KeyArray)) {
                                $KeyArray[] = $VA['k0_1st_in_row_affected'];
                                $Conditions[] = array('k0_2nd_in_row_affected', '=', $VA['k0_1st_in_row_affected'], 'OR');
                            }
                    }
                    if ($RROtB) foreach ($SROtB as $VB)
                        if (!in_array($VB['k0obstopic'], $KeyArray)) {
                            $KeyArray[] = $VB['k0obstopic'];
                            $Conditions[] = array('k0_2nd_in_row_affected', '=', $VB['k0obstopic'], 'OR');
                        }
                    $LastArrayKey = count($Conditions) - 1;
                    $Conditions[$LastArrayKey][3] = ')';
                    list($SROtOmA, $RROtOmA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
                    if ($KeyArray) {
                        unset($Conditions);
                        foreach ($KeyArray as $V)
                            $Conditions[] = array('k0obsmethod', '=', $V, 'OR');
                        $LastArrayKey = count($Conditions) - 1;
                        unset($Conditions[$LastArrayKey][3]);
                        list($SROtOmB, $RROtOmB) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic_obsmethod', $Conditions);
                    }
                    if ($RROtOmA or $RROtOmB) {
                        unset($Conditions);
                        $Conditions[0] = array('k0user', '=', $k0user, 'AND');
                        $Conditions[] = array('c2table_name', '=', 't0obsmethod', 'AND (');
                        $KeyArray = array();
                        if ($RROtOmA) {
                            $SR = array_merge($SR, $SROtOmA); // t0obstopic_obsmethod history
                            foreach ($SROtOmA as $VA)
                                if (!in_array($VA['k0_3rd_in_row_affected'], $KeyArray)) {
                                    $KeyArray[] = $VA['k0_3rd_in_row_affected'];
                                    $Conditions[] = array('k0_1st_in_row_affected', '=', $VA['k0_3rd_in_row_affected'], 'OR');
                                }
                        }
                        if ($RROtOmB) foreach ($SROtOmB as $VB)
                            if (!in_array($VB['k0obsmethod'], $KeyArray))
                                $Conditions[] = array('k0_1st_in_row_affected', '=', $VB['k0obsmethod'], 'OR'); // No juntion table, so don't need to add to $KeyArray here.
                        $LastArrayKey = count($Conditions) - 1;
                        $Conditions[$LastArrayKey][3] = ')';
                        list($SROmA, $RROmA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
                        if ($RROmA)
                            $SR = array_merge($SR, $SROmA); // t0obsmethod history
                        // No junction table so don't need to $SROmB
                    }
                }
            }
            unset($Conditions);
            $Conditions[0] = array('k0user', '=', $k0user, 'AND');
            $Conditions[] = array('c2table_name', '=', 't0audit_fragment', 'AND (');
            $KeyArray = array();
            if ($RRAuA) {
                // t0audit history already merged in.
                foreach ($SRAuA as $VA)
                    if (!in_array($VA['k0_1st_in_row_affected'], $KeyArray)) {
                        $KeyArray[] = $VA['k0_1st_in_row_affected'];
                        $Conditions[] = array('k0_2nd_in_row_affected', '=', $VA['k0_1st_in_row_affected'], 'OR');
                    }
            }
            if ($RRAuB) foreach ($SRAuB as $VB)
                if (!in_array($VB['k0audit'], $KeyArray)) {
                    $KeyArray[] = $VB['k0audit'];
                    $Conditions[] = array('k0_2nd_in_row_affected', '=', $VB['k0audit'], 'OR');
                }
            $LastArrayKey = count($Conditions) - 1;
            $Conditions[$LastArrayKey][3] = ')';
            list($SRAuFA, $RRAuFA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
            if ($KeyArray) {
                unset($Conditions);
                foreach ($KeyArray as $V)
                    $Conditions[] = array('k0audit', '=', $V, 'OR');
                $LastArrayKey = count($Conditions) - 1;
                unset($Conditions[$LastArrayKey][3]);
                list($SRAuFB, $RRAuFB) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment', $Conditions);
            }
            if ($RRAuFA or $RRAuFB) {
                unset($Conditions);
                $Conditions[0] = array('k0user', '=', $k0user, 'AND');
                $Conditions[] = array('c2table_name', '=', 't0audit_fragment_obsmethod', 'AND (');
                $KeyArray = array();
                if ($RRAuFA) {
                    $SR = array_merge($SR, $SRAuFA); // t0audit_fragment history
                    foreach ($SRAuFA as $VA)
                        if (!in_array($VA['k0_1st_in_row_affected'], $KeyArray)) {
                            $KeyArray[] = $VA['k0_1st_in_row_affected'];
                            $Conditions[] = array('k0_2nd_in_row_affected', '=', $VA['k0_1st_in_row_affected'], 'OR');
                        }
                }
                if ($RRAuFB) foreach ($SRAuFB as $VB)
                    if (!in_array($VB['k0audit_fragment'], $KeyArray))
                        $Conditions[] = array('k0_2nd_in_row_affected', '=', $VB['k0audit_fragment'], 'OR');
                $LastArrayKey = count($Conditions) - 1;
                $Conditions[$LastArrayKey][3] = ')';
                list($SRAuFOmA, $RRAuFOmA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
                if ($RRAuFOmA)
                    $SR = array_merge($SR, $SRAuFOmA); // t0audit_fragment_obsmethod history
            }
            unset($Conditions);
            $Conditions[0] = array('k0user', '=', $k0user, 'AND');
            $Conditions[] = array('c2table_name', '=', 't0obsresult', 'AND (');
            $KeyArray = array();
            if ($RRAuA) {
                // t0audit history already merged in.
                foreach ($SRAuA as $VA)
                    if (!in_array($VA['k0_1st_in_row_affected'], $KeyArray)) {
                        $KeyArray[] = $VA['k0_1st_in_row_affected'];
                        $Conditions[] = array('k0_2nd_in_row_affected', '=', $VA['k0_1st_in_row_affected'], 'OR');
                    }
            }
            if ($RRAuB) foreach ($SRAuB as $VB)
                if (!in_array($VB['k0audit'], $KeyArray)) {
                    $KeyArray[] = $VB['k0audit'];
                    $Conditions[] = array('k0_2nd_in_row_affected', '=', $VB['k0audit'], 'OR');
                }
            $LastArrayKey = count($Conditions) - 1;
            $Conditions[$LastArrayKey][3] = ')';
            list($SROrA, $RROrA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
            if ($KeyArray) {
                unset($Conditions);
                foreach ($KeyArray as $V)
                    $Conditions[] = array('k0audit', '=', $V, 'OR');
                $LastArrayKey = count($Conditions) - 1;
                unset($Conditions[$LastArrayKey][3]);
                list($SROrB, $RROrB) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult', $Conditions);
            }
            if ($RROrA or $RROrB) {
                unset($Conditions);
                $Conditions[0] = array('k0user', '=', $k0user, 'AND');
                $Conditions[] = array('c2table_name', '=', 't0obsresult_action', 'AND (');
                $KeyArray = array();
                if ($RROrA) {
                    $SR = array_merge($SR, $SROrA); // t0obsresult history
                    foreach ($SROrA as $VA)
                        if (!in_array($VA['k0_1st_in_row_affected'], $KeyArray)) {
                            $KeyArray[] = $VA['k0_1st_in_row_affected'];
                            $Conditions[] = array('k0_2nd_in_row_affected', '=', $VA['k0_1st_in_row_affected'], 'OR');
                        }
                }
                if ($RROrB) foreach ($SROrB as $VB)
                    if (!in_array($VB['k0obsresult'], $KeyArray))
                        $Conditions[] = array('k0_2nd_in_row_affected', '=', $VB['k0obsresult'], 'OR');
                $LastArrayKey = count($Conditions) - 1;
                $Conditions[$LastArrayKey][3] = ')';
                list($SROrAA, $RROrAA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
                if ($RROrAA)
                    $SR = array_merge($SR, $SROrAA); // t0obsresult_action history
            }
        } // t0action history is queried directly... above.        
        // t0incident...
        unset($Conditions);
        $Conditions[0] = array('k0process', '=', $k0process);
        list($SRB, $RRB) = $Zfpf->select_sql_1s($DBMSresource, 't0incident', $Conditions);
        $Conditions[0] = array('k0user', '=', $k0user, 'AND');
        $Conditions[] = array('c2table_name', '=', 't0incident', 'AND');
        $Conditions[] = array('k0_2nd_in_row_affected', '=', $k0process);
        list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
        if ($RRA or $RRB) {
            unset($Conditions);
            $Conditions[0] = array('k0user', '=', $k0user, 'AND');
            $Conditions[] = array('c2table_name', '=', 't0incident_action', 'AND (');
            $KeyArray = array();
            if ($RRA) {
                $SR = array_merge($SR, $SRA);
                foreach ($SRA as $VA)
                    if (!in_array($VA['k0_1st_in_row_affected'], $KeyArray)) {
                        $KeyArray[] = $VA['k0_1st_in_row_affected'];
                        $Conditions[] = array('k0_2nd_in_row_affected', '=', $VA['k0_1st_in_row_affected'], 'OR');
                    }
            }
            if ($RRB) foreach ($SRB as $VB)
                if (!in_array($VB['k0incident'], $KeyArray))
                    $Conditions[] = array('k0_2nd_in_row_affected', '=', $VB['k0incident'], 'OR');
            $LastArrayKey = count($Conditions) - 1;
            $Conditions[$LastArrayKey][3] = ')';
            list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
            if ($RRA)
                $SR = array_merge($SR, $SRA);
        } // t0action history is queried directly... above.
        $Zfpf->close_connection_1s($DBMSresource);
        $RR = count($SR);
        return array($SR, $RR);
    }
    
    public function by_user_facility_h($Zfpf, $k0user, $k0facility, $k0lepc) {
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Conditions[0] = array('k0user', '=', $k0user, 'AND (');
        $Conditions[] = array('c2table_name', '=', 't0user_practice', 'AND', '(');
        $Conditions[] = array('k0_6th_in_row_affected', '=', $k0facility, ')');
        $Conditions[] = array('c2table_name', '=', 't0change_management', 'AND', 'OR (');
        $Conditions[] = array('k0_2nd_in_row_affected', '=', $k0facility, ')');
        $Conditions[] = array('c2table_name', '=', 't0action', 'AND', 'OR (');
        $Conditions[] = array('k0_2nd_in_row_affected', '=', $k0facility, '))'); // '))' second one closes very first 'AND ('
        $SR = array(); // In case nothing returned from first select below.
        list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
        if ($RRA)
            $SR = array_merge($SR, $SRA);
        // Loop through results from tables with subordinate tables.
        // Query t0facility separately to get any k0lepc keys recorded with t0facility changes in t0history.
        unset($Conditions);
        $Conditions[0] = array('k0user', '=', $k0user, 'AND');
        $Conditions[] = array('c2table_name', '=', 't0facility', 'AND');
        $Conditions[] = array('k0_1st_in_row_affected', '=', $k0facility);
        list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
        // t0lepc
        unset($Conditions);
        $Conditions[0] = array('k0user', '=', $k0user, 'AND');
        $Conditions[] = array('c2table_name', '=', 't0lepc', 'AND (');
        $KeyArray = array($k0lepc); // Check LEPC hisotry with the current k0lepc, passed in.
        $Conditions[] = array('k0_1st_in_row_affected', '=', $k0lepc, 'OR');
        if ($RRA) {
            $SR = array_merge($SR, $SRA);
            foreach ($SRA as $VA) // The LEPC may have changed over the facility's history in the app.
                if (!in_array($VA['k0_2nd_in_row_affected'], $KeyArray)) {
                    $KeyArray[] = $VA['k0_2nd_in_row_affected'];
                    $Conditions[] = array('k0_1st_in_row_affected', '=', $VA['k0_2nd_in_row_affected'], 'OR');
                }
        }
        $LastArrayKey = count($Conditions) - 1;
        $Conditions[$LastArrayKey][3] = ')';
        list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
        if ($RRA)
            $SR = array_merge($SR, $SRA);
        // t0facility_process
        unset($Conditions);
        $Conditions[0] = array('k0user', '=', $k0user, 'AND');
        $Conditions[] = array('c2table_name', '=', 't0facility_process', 'AND');
        $Conditions[] = array('k0_2nd_in_row_affected', '=', $k0facility);
        $KeyArray = array();
        list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
        if ($RRA) {
            $SR = array_merge($SR, $SRA);
            foreach ($SRA as $VA) // Loop through processes associated with the facility in t0history for this user.
                if (!in_array($VA['k0_3rd_in_row_affected'], $KeyArray)) {
                    $KeyArray[] = $VA['k0_3rd_in_row_affected'];
                    list($SRB, $RRB) = $this->by_user_process_h($Zfpf, $k0user, $VA['k0_3rd_in_row_affected']);
                    if ($RRB)
                        $SR = array_merge($SR, $SRB);
                }
        }
        unset($Conditions);
        $Conditions[0] = array('k0facility', '=', $k0facility); // Check currently associated processes not caught in above t0history query.
        list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0facility_process', $Conditions);
        if ($RRA) foreach ($SRA as $VA) {
            if (!in_array($VA['k0process'], $KeyArray)) {
                list($SRB, $RRB) = $this->by_user_process_h($Zfpf, $k0user, $VA['k0process']);
                if ($RRB)
                    $SR = array_merge($SR, $SRB);
            }
        }
        // t0facility_union
        unset($Conditions);
        $Conditions[0] = array('k0facility', '=', $k0facility);
        list($SRB, $RRB) = $Zfpf->select_sql_1s($DBMSresource, 't0facility_union', $Conditions);
        $Conditions[0] = array('k0user', '=', $k0user, 'AND');
        $Conditions[] = array('c2table_name', '=', 't0facility_union', 'AND');
        $Conditions[] = array('k0_2nd_in_row_affected', '=', $k0facility);
        list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
        if ($RRA or $RRB) {
            unset($Conditions);
            $Conditions[0] = array('k0user', '=', $k0user, 'AND');
            $Conditions[] = array('c2table_name', '=', 't0union', 'AND (');
            $KeyArray = array();
            if ($RRA) {
                $SR = array_merge($SR, $SRA);
                foreach ($SRA as $VA)
                    if (!in_array($VA['k0_3rd_in_row_affected'], $KeyArray)) {
                        $KeyArray[] = $VA['k0_3rd_in_row_affected'];
                        $Conditions[] = array('k0_1st_in_row_affected', '=', $VA['k0_3rd_in_row_affected'], 'OR');
                    }
            }
            if ($RRB) foreach ($SRB as $VB)
                if (!in_array($VB['k0union'], $KeyArray))
                    $Conditions[] = array('k0_1st_in_row_affected', '=', $VB['k0union'], 'OR');
            $LastArrayKey = count($Conditions) - 1;
            $Conditions[$LastArrayKey][3] = ')';
            list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
            if ($RRA)
                $SR = array_merge($SR, $SRA);
        }
        // t0user_facility and t0contractor_priv
        unset($Conditions);
        $Conditions[0] = array('k0facility', '=', $k0facility);
        list($SRB, $RRB) = $Zfpf->select_sql_1s($DBMSresource, 't0user_facility', $Conditions);
        $Conditions[0] = array('k0user', '=', $k0user, 'AND');
        $Conditions[] = array('c2table_name', '=', 't0user_facility', 'AND');
        $Conditions[] = array('k0_3rd_in_row_affected', '=', $k0facility);
        list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
        if ($RRA or $RRB) {
            unset($Conditions);
            $Conditions[0] = array('k0user', '=', $k0user, 'AND');
            $Conditions[] = array('c2table_name', '=', 't0contractor_priv', 'AND (');
            $KeyArray = array();
            if ($RRA) {
                $SR = array_merge($SR, $SRA);
                foreach ($SRA as $VA)
                    if (!in_array($VA['k0_1st_in_row_affected'], $KeyArray)) {
                        $KeyArray[] = $VA['k0_1st_in_row_affected'];
                        $Conditions[] = array('k0_2nd_in_row_affected', '=', $VA['k0_1st_in_row_affected'], 'OR');
                    }
            }
            if ($RRB) foreach ($SRB as $VB)
                if (!in_array($VB['k0user_facility'], $KeyArray))
                    $Conditions[] = array('k0_2nd_in_row_affected', '=', $VB['k0user_facility'], 'OR');
            $LastArrayKey = count($Conditions) - 1;
            $Conditions[$LastArrayKey][3] = ')';
            list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
            if ($RRA)
                $SR = array_merge($SR, $SRA);
        } // Don't check t0user, because the app doesn't allow facility admins to update t0user.
        $Zfpf->close_connection_1s($DBMSresource);
        $RR = count($SR);
        return array($SR, $RR);
    }
    
    // Returns array of t0history records relating to a k0user in tables
    // t0user, t0user_owner, t0user_contractor, t0user_facility, t0user_process, t0user_practice
    // $Zfpf must have CoreZfpf
    public function to_user_h($Zfpf, $k0user) {
        $Conditions = array(
            0 => array('c2table_name', '=', 't0user', 'AND', '('),
            1 => array('k0_1st_in_row_affected', '=', $k0user, ')'),
            2 => array('c2table_name', '=', 't0user_owner', 'AND', 'OR ('),
            3 => array('k0_2nd_in_row_affected', '=', $k0user, ')'),
            4 => array('c2table_name', '=', 't0user_contractor', 'AND', 'OR ('),
            5 => array('k0_2nd_in_row_affected', '=', $k0user, ')'),
            6 => array('c2table_name', '=', 't0user_facility', 'AND', 'OR ('),
            7 => array('k0_2nd_in_row_affected', '=', $k0user, ')'),
            8 => array('c2table_name', '=', 't0user_process', 'AND', 'OR ('),
            9 => array('k0_2nd_in_row_affected', '=', $k0user, ')'),
            10 => array('c2table_name', '=', 't0user_practice', 'AND', 'OR ('),
            11 => array('k0_2nd_in_row_affected', '=', $k0user, ')')
        );
        return $Zfpf->one_shot_select_1s('t0history', $Conditions);
    }
    
    // Returns HTML showing all records in $SR
    // $SR and $RR are output of CoreZfpf::select_sql_1s on t0history, see above functions.
    // $Zfpf must have ConfirmZfpf
    public function selected_changes_html_h($Zfpf, $SR, $RR, $Heading = 'History Records', $FormActionBack = FALSE, $BackInputName = FALSE, $Intro = '') {
        $HTML = '<h2>
        '.$Heading.'</h2>
        '.$Intro;
        if (!$RR)
            $HTML .= '<p>
            <b>None found</b></p>';
        else {
            foreach ($SR as $V)
                $c1ts_changed[] = $V['c1ts_changed'];
            array_multisort($c1ts_changed, $SR); // Sort $SR oldest to newest. Not needed if history inserts are done chronologically.
            foreach ($SR as $VA) {
                $CRhtmlFormArray = $Zfpf->decrypt_decode_1c($VA['c6html_form_array']);
                $ChangedRow = $Zfpf->decrypt_decode_1c($VA['c6changed_row']);
                if ($CRhtmlFormArray and $ChangedRow) {
                    foreach ($ChangedRow as $KB => $VB)
                        if (substr($KB, 1, 2) >= '5')
                            $ChangedRow[$KB] = $Zfpf->encrypt_1c($VB); // Have to encrypt again before passing to ConfirmZfpf::select_to_o1_html_1e
                    $HTML .= '<p class="topborder">
                    <i>Time of the change:</i> '.$Zfpf->timestamp_to_display_1c($VA['c1ts_changed']).'<br />
                    <i>Database table changed:</i> '.$VA['c2table_name'].'<br />
                    <i>Primary key of row changed:</i> '.$VA['k0_1st_in_row_affected'].'<br />
                    <i>Who made the change (information when change made):</i> '.$Zfpf->decrypt_1c($VA['c5ntewe_at_time']).'<br />
                    <i>Who made the change (current information):</i> '.$Zfpf->user_job_info_1c($VA['k0user'])['NameTitleEmployerWorkEmail'].'<br />
                    <i>Only the fields that changed and their post-change values are listed below.</i></p>
                    '.$Zfpf->select_to_o1_html_1e($CRhtmlFormArray, FALSE, $ChangedRow); // FALSE means no c6bfn downloads allowed. Could write code to allow.
                }
                else
                    $HTML .= '<p class="topborder">
                    An error occurred when decoding this history record.</p>';
            }
        }
        if ($FormActionBack) {
            $HTML .= '
            <form action="'.$FormActionBack.'" method="post"><p>
                <input type="submit"';
            if ($BackInputName)
                $HTML .= ' name="'.$BackInputName.'"';
            $HTML .= ' value="Go back" /></p>
            </form>';
        }
        echo $Zfpf->xhtml_contents_header_1c('History').$HTML.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

}

