<?php
/*
  Custom class for getting and setting events
*/

class HelsingborgEventModel {

  /*

    -- USEFUL STUFF --

  $mydb = new wpdb('username','password','database','localhost');
  $rows = $mydb->get_results("select Name from my_table");
  echo "<ul>";
    foreach ($rows as $obj) :
      echo "<li>".$obj->Name."</li>";
    endforeach;
  echo "</ul>";

  */


  public static function get_happy_event_table(){
      return 'happy_event';
  }

  public static function get_happy_administration_unit_table(){
      return 'happy_administration_unit';
  }

  public static function get_happy_event_administration_unit_table(){
      return 'happy_event_administration_unit';
  }

  public static function get_happy_event_times_table(){
      return 'happy_event_times';
  }

  public static function get_happy_event_types_table(){
      return 'happy_event_types';
  }

  public static function get_happy_event_types_group_table(){
      return 'happy_event_types_group';
  }

  public static function get_happy_event_external_event_table(){
      return 'happy_event_external_event';
  }

  public static function get_happy_images_table(){
      return 'happy_images';
  }

  public static function get_happy_event_organizers_table(){
      return 'happy_event_organizers';
  }

  public static function get_happy_event_roles_table(){
      return 'happy_event_roles';
  }

  public static function get_happy_event_user_table(){
      return 'happy_event_user';
  }

  public static function get_happy_event_user_administration_unit_table(){
      return 'happy_event_user_administration_unit';
  }

  public static function load_events() {
    global $wpdb;

    /*
    SELECT DISTINCT hE.EventID, hE.Name, hE.Description, hETid.Date FROM
            happy_event hE,
            happy_event_times hETid,
            happy_event_administration_unit hEFE,
            happy_administration_unit hFE
            WHERE hE.Approved = 1
             AND hE.EventID = hETid.EventID
             AND hETid.Date >= CURDATE()
             AND hE.EventID = hEFE.EventID
             AND hEFE.AdministrationUnitID = hFE.AdministrationUnitID
             ORDER BY hETid.Date LIMIT 30


             SELECT DISTINCT hE.EventID, hE.Name, hE.Description, hETid.Date, hIM.ImagePath FROM
            happy_event hE,
            happy_event_times hETid,
            happy_event_administration_unit hEFE,
            happy_administration_unit hFE,
            happy_images hIM
            WHERE hE.Approved = 1
             AND hE.EventID = hETid.EventID
             AND hIM.EventID = he.EventID
             AND hETid.Date >= CURDATE()
             AND hE.EventID = hEFE.EventID
             AND hEFE.AdministrationUnitID = hFE.AdministrationUnitID
             ORDER BY hETid.Date LIMIT 30
    */

    $events = $wpdb->get_results('SELECT DISTINCT hE.EventID, hE.Name, hE.Description, hETid.Date, hIM.ImagePath FROM '
            . self::get_happy_event_table() . ' hE,'
            . self::get_happy_event_times_table() . ' hETid,'
            . self::get_happy_event_administration_unit_table() . ' hEFE,'
            . self::get_happy_administration_unit_table() . ' hFE, '
            . self::get_happy_images_table() . ' hIM ' .
             'WHERE hE.Approved = 1
             AND hE.EventID = hETid.EventID
             AND hETid.Date >= CURDATE()
             AND hE.EventID = hEFE.EventID
             AND hE.EventID = hIM.EventID
             AND hEFE.AdministrationUnitID = hFE.AdministrationUnitID
             ORDER BY hETid.Date LIMIT 30', OBJECT
            );

    return $events;
            // if (!String.IsNullOrEmpty(forvaltningsEnheter))
            // {
            //     commandString += "AND hFE.Namn in (" + forvaltningsEnheter + ") ";
            // }

  }

  public static function load_unpublished_events($happy_user_id = -1) {
    if ($happy_user_id == -1)
      return; // Escape

    global $wpdb;

//      string commandString = "SELECT DISTINCT hE.EvenemangsID, hE.Namn, hE.Beskrivning, hETid.Datum ";
//             commandString += "FROM Happy_Evenemang hE, ";
//             commandString += "Happy_Evenemangstider hETid, ";
//             commandString += "Happy_EvenemangstypsGruppering hETG, ";
//             commandString += "Happy_EvenemangsForvaltningsenheter hEFE, ";
//             commandString += "Happy_Forvaltningsenheter hFE ";
//             commandString += "WHERE hE.Godkannt = 1 ";
//             commandString += "AND hE.EvenemangsID = hETG.EvenemangsID ";
//             if (!String.IsNullOrEmpty(typeOfEventSearch))
//             {
//                 // The '+' have been replased by ' ' when getting the string with Request.QueryString
//                 commandString += "AND hETG.Evenemangstypsnamn in (" + typeOfEventSearch.Replace(' ', ',') + ") ";
//             }
//             if (!String.IsNullOrEmpty(placeSearch))
//             {
//                 commandString += "AND hE.Plats LIKE '%" + placeSearch + "%' ";
//             }
//             commandString += "AND hE.EvenemangsID = hETid.EvenemangsID ";
//             commandString += "AND hETid.Datum >= convert(VARCHAR(10), GETDATE(), 120) ";
//             if (!String.IsNullOrEmpty(fromDateSearch))
//             {
//                 commandString += "AND hETid.Datum >= ('" + fromDateSearch + "') ";
//             }
//             if (!String.IsNullOrEmpty(toDateSearch))
//             {
//                 commandString += "AND hETid.Datum <= ('" + toDateSearch + "') ";
//             }
//             if (!String.IsNullOrEmpty(allEventsSearch) && allEventsSearch.Equals("SelectedUnits"))
//             {
//                 commandString += "AND hE.EvenemangsID = hEFE.EvenemangsID ";
//                 commandString += "AND hEFE.ForvaltningsenhetsID = hFE.ForvaltningsenhetsID ";
//                 if (!String.IsNullOrEmpty(forvaltningsEnheter))
//                 {
//                     commandString += "AND hFE.Namn in (" + forvaltningsEnheter + ") ";
//                 }
//             }
//             if (!String.IsNullOrEmpty(freeTextSearch))
//             {
//                 commandString += "AND hE.Namn LIKE '%" + freeTextSearch + "%' ";
//                 commandString += "AND hE.Beskrivning LIKE '%" + freeTextSearch + "%' ";
//             }
//             commandString += "ORDER BY hETid.Datum";
//
//
//
// ID->3 + 6037 == 4
    // 1. Get all AdministrationUnitIDs connected to happy_user_id
    // SELECT `AdministrationUnitID` FROM `happy_user_administration_unit`WHERE `UserID`=4

    // 2. Get all EventIDs connected to the AdministrationUnitIDs
    // SELECT `AdministrationUnitID` FROM `happy_event_administration_unit` WHERE `AdministrationUnitID`=47 OR `AdministrationUnitID`=48

    // 3. Get all happy_event_times where EventIDs are used

    // 4. Get all EventIDs where:
    // $sql = 'SELECT *
    //       FROM `table`
    //      WHERE `id` IN (' . implode(',', array_map('intval', $array)) . ')';
    //                          EventID exist
    //                          Approved == false
    //                          ExternalEventID == NULL
    //                          happy_event_time > today
    //

    // 5. Got the list with EventIDs



    // 6. Build events from these EventIDs

    // 7. Return the list !

    // string commandString1 = "SELECT hE.Namn, hE.Beskrivning, hE.Plats, hETid.Datum, hETid.Tid ";
    //         commandString1 += "FROM Happy_Evenemangstider hETid, Happy_Evenemang hE ";
    //         commandString1 += "WHERE hETid.Datum >= convert(VARCHAR(10), GETDATE(), 120) ";
    //         commandString1 += "AND hE.EvenemangsID = hETid.EvenemangsID ";
    //         commandString1 += "AND hE.EvenemangsID = " + ChoosenHappyEventID;
    //         commandString1 += " ORDER BY hETid.Datum";
    //         string commandString2 = "SELECT hArr.Webbadress ";
    //         commandString2 += "FROM Happy_Evenemang hE, Happy_Arrangorer hArr ";
    //         commandString2 += "WHERE hE.ArrangorsID = hArr.ArrangorsID ";
    //         commandString2 += "AND hE.EvenemangsID = " + ChoosenHappyEventID;

    $result_events = $wpdb->get_results('SELECT he.Name, he.Description, he.Location, heT.Date, heT.Time
                                    FROM ' . $happy_event_times . ' heT, ' . $happy_event . ' he
                                    WHERE heT.Date >= convert(VARCHAR(10), GETDATE(), 120)
                                    AND he.EventID = heT.EventID
                                    AND he.EventID = ' . $event_id . '
                                    ORDER BY heT.Date',
                                    OBJECT);
  }

  public static function load_event($event_id = -1) {

    // Event ID wasn't included -> just escape
    if ($event_id == -1)
      return;

    // TODO Connect to proper DB !
    global $wpdb;

    $happy_event = self::get_happy_event_table();
    $happy_event_times = self::get_happy_event_times_table();
    $happy_event_organizers = self::get_happy_event_organizers_table();

    $result_events = $wpdb->get_results( 'SELECT he.Name, he.Description, he.Location, heT.Date, heT.Time
                                    FROM ' . $happy_event_times . ' heT, ' . $happy_event . ' he
                                    WHERE heT.Date >= convert(VARCHAR(10), GETDATE(), 120)
                                    AND he.EventID = heT.EventID
                                    AND he.EventID = ' . $event_id . '
                                    ORDER BY heT.Date',
                                    OBJECT
                                 );

    $result_web_addresses = $wpdb->get_results( 'SELECT heO.WebAddress
                                    FROM ' . $happy_event . ' he, ' . $happy_event_organizers . ' heO
                                    WHERE he.OrganizerID = heO.OrganizerID
                                    AND he.EventID = ' . $event_id,
                                    OBJECT
                                 );
  }

  public static function get_event_image() {
            // System.Byte[] bildID = null;
            // string externalImagePath = string.Empty;
            // string externalEventID = string.Empty;
            // // We do not delete any BildID from Happy_Bilder => Use TOP 1 to select correct image.
            // string commandString = "SELECT TOP 1 hB.BildID, hB.Sokvag, hE.ExterntEvenemangsID ";
            // commandString += "FROM Happy_Evenemang hE, Happy_Bilder hB ";
            // commandString += "WHERE hE.EvenemangsID = hB.EvenemangsID ";
            // commandString += "AND hE.EvenemangsID = " + ChoosenHappyEventID;
            // commandString += " ORDER BY hB.BildID DESC";
  }

  public static function load_event_types() {
    // TODO Connect to proper DB !
    global $wpdb;
    $happy_event_types = self::get_happy_event_types_table();
    $result_event_types = $wpdb->get_results( 'SELECT Name
                                               FROM ' . $happy_event_types . '
                                               ORDER BY Name',
                                               OBJECT
                                            );
    return $result_event_types;
  }

  public static function load_administration_units() {
    // TODO Connect to proper DB !
    global $wpdb;
    $happy_administration_unit_table = self::get_happy_administration_unit_table();
    $happy_administration_units = $wpdb->get_results( 'SELECT Name
                                               FROM ' . $happy_administration_unit_table . '
                                               ORDER BY Name ASC',
                                               OBJECT
                                            );
    return $happy_administration_units;
  }

  public static function load_organizers() {
    // TODO Connect to proper DB !
    global $wpdb;
    $happy_event_organizers = self::get_happy_event_organizers_table();
    $result_event_types = $wpdb->get_results( 'SELECT Name, OrganizerID
                                               FROM ' . $happy_event_organizers . '
                                               ORDER BY Name',
                                               OBJECT
                                            );
  }

  public static function load_organizer_values($organizerId = -1) {

    // Escape
    if ($organizer == -1)
      return;

    // TODO Connect to proper DB !
    global $wpdb;
    $happy_event_organizers = self::get_happy_event_organizers_table();
    $result_event_types = $wpdb->get_results( 'SELECT heO.Phone, heO.Email, heO.WebAddress
                                               FROM ' . $happy_event_organizers . ' AS heO
                                               WHERE heO.OrganizerID = ' . $organizerId,
                                               OBJECT
                                            );
  }

  public static function create_event($event, $group, $administration, $image) {
    global $wpdb;

    $wpdb->query( $wpdb->prepare(
      'INSERT INTO ' . self::get_happy_event_table() . ' (Name, Description, Approved, OrganizerID, Location, ExternalEventID)
      VALUES (%s, %s, %d, %d, %s, NULL),' . $event
    ));

    $wpdb->query( $wpdb->prepare(
      'INSERT INTO ' . self::get_happy_event_types_group_table() . ' (EventTypesName, EventID)
      VALUES (%s, %d),' . $group
    ));

    $wpdb->query( $wpdb->prepare(
      'INSERT INTO ' . self::get_happy_event_administration_unit_table() . ' (AdministrationUnitID, EventID)
      VALUES (%s, %d),' . $administration
    ));

    $wpdb->query( $wpdb->prepare(
      'INSERT INTO ' . self::get_happy_event_images_table() . ' (ImageID, EventID, ImagePath, Author)
      VALUES (%s, %d, %s, %s),' . $image
    ));
  }

  public static function update_event() {
    //  if ((string)HttpContext.Current.Session["presentEventName"] != EventNameTextBox.Text)
    //         {
    //             statmentVariables += " Namn = '" + EventNameTextBox.Text + "'";
    //             countvariables++;
    //         }
    //         if ((string)HttpContext.Current.Session["presentEventDecsription"] != DescriptionTextBox.Text)
    //         {
    //             if (countvariables > 0)
    //             {
    //                 statmentVariables += "" + commasign + "" + " Beskrivning = '" + DescriptionTextBox.Text + "'";
    //             }
    //             else
    //             {
    //                 statmentVariables += " Beskrivning = '" + DescriptionTextBox.Text + "'";
    //             }
    //             countvariables++;
    //         }
    //         if (pToBeePublished)
    //         {
    //             if (countvariables > 0)
    //             {
    //                 statmentVariables += "" + commasign + "" + " Godkannt = 1";
    //             }
    //             else
    //             {
    //                 statmentVariables += " Godkannt = 1";
    //             }
    //             countvariables++;
    //         }
    //         if ((string)HttpContext.Current.Session["presentEventDestination"] != PlaceTextBox.Text)
    //         {
    //             if (countvariables > 0)
    //             {
    //                 statmentVariables += "" + commasign + "" + " Plats = '" + PlaceTextBox.Text + "'";
    //             }
    //             else
    //             {
    //                 statmentVariables += " Plats = '" + PlaceTextBox.Text + "'";
    //             }
    //             countvariables++;
    //         }
    //         bool setOrganizerNameToNull = false;
    //         if ((string)HttpContext.Current.Session["presentEventOrganizer"] != OrganizerNamesddl.SelectedValue)
    //         {
    //             if (countvariables > 0)
    //             {
    //                 statmentVariables += "" + commasign + "";
    //             }
    //             if (OrganizerNamesddl.SelectedIndex == 0)
    //             {
    //                 statmentVariables += " ArrangorsID = @arrangorsID ";
    //                 setOrganizerNameToNull = true;
    //             }
    //             else
    //             {
    //                 statmentVariables += " ArrangorsID = '" + OrganizerNamesddl.SelectedValue + "'";
    //             }
    //             countvariables++;
    //         }
    //         #endregion Set updated values
     //
    //         if (countvariables > 0)
    //         {
    //             tableNameQuery = "UPDATE Happy_Evenemang SET ";
    //             completeQuery += tableNameQuery + statmentVariables + " WHERE EvenemangsID = " + " '" + ToPublishGv.SelectedRow.Cells[3].Text + "'";
    //             com = new SqlCommand(completeQuery, con);
    //             try
    //             {
    //                 con.Open();
    //                 // For inserting null in database
    //                 if (setOrganizerNameToNull)
    //                 {
    //                     com.Parameters.AddWithValue("@arrangorsID", SqlDbType.SmallInt);
    //                     com.Parameters["@arrangorsID"].Value = DBNull.Value;
    //                 }
    //                 com.ExecuteNonQuery();
    //             }
    //             catch (SqlException sqlE)
    //             {
    //                 logFourNet.Error(sqlE);
    //                 DbExceptionlbl.Text = "Databasproblem, kan inte uppdatera informationen!";
    //                 DbExceptionlbl.Visible = true;
    //             }
    //             finally
    //             {
    //                 con.Close();
    //             }
    //         }
  }

  public static function update_event_times() {
      // bool eventDateBool = false;
      //       bool eventFromTimeBool = false;
      //       countvariables = 0;
      //       statmentVariables = "";
      //       tableNameQuery = "";
      //       completeQuery = "";
      //       con = new SqlConnection(connectionString);
      //
      //       #region Set updated values
      //
      //       if (isPeriod)
      //       {
      //           #region Check if Period is changed
      //
      //           bool isPeriodChanged = false;
      //           if ((string)HttpContext.Current.Session["presentFromDate"] != FromDateTextBox.Text)
      //           {
      //               isPeriodChanged = true;
      //           }
      //           if ((string)HttpContext.Current.Session["presentToDate"] != ToDateTextBox.Text)
      //           {
      //               isPeriodChanged = true;
      //           }
      //           // Unnecessary if date is changed
      //           if (!isPeriodChanged)
      //           {
      //               CheckBoxList oldWeekDayList = (CheckBoxList)HttpContext.Current.Session["presentWeekDay"];
      //               for (int i = 0; i < WeekDayCheckBoxList.Items.Count; i++)
      //               {
      //                   if (oldWeekDayList.Items[i].Selected != WeekDayCheckBoxList.Items[i].Selected)
      //                   {
      //                       isPeriodChanged = true;
      //                       break;
      //                   }
      //               }
      //           }
      //           #endregion Check if Period is changed
      //
      //           if (isPeriodChanged)
      //           {
      //               // Remove all future events
      //               deleteDBHappyEvenemangstider(int.Parse(ToPublishGv.SelectedRow.Cells[3].Text));
      //               // Insert all events in selection
      //               insertDBHappyEvenemangstider(int.Parse(ToPublishGv.SelectedRow.Cells[3].Text));
      //           }
      //       }
      //       else
      //       {
      //           if ((string)HttpContext.Current.Session["presentEventDate"] != DateTextBox.Text)
      //           {
      //               statmentVariables += " Datum = '" + DateTextBox.Text + "'";
      //               eventDateBool = true;
      //               countvariables++;
      //           }
      //       }
      //
      //       if ((string)HttpContext.Current.Session["presentEventStartTime"] != TimeTextBox.Text)
      //       {
      //           if (countvariables > 0)
      //           {
      //               statmentVariables += "" + commasign + "" + " Tid = '" + TimeTextBox.Text + "'";
      //           }
      //           else
      //           {
      //               statmentVariables += " Tid = '" + TimeTextBox.Text + "'";
      //           }
      //           countvariables++;
      //           eventFromTimeBool = true;
      //       }
      //
      //       #endregion Set updated values
      //
      //       if (eventDateBool == true || eventFromTimeBool == true)
      //       {
      //           tableNameQuery = " UPDATE Happy_Evenemangstider SET";
      //           completeQuery += tableNameQuery + statmentVariables + " WHERE EvenemangsID = " + " '" + ToPublishGv.SelectedRow.Cells[3].Text + "'";
      //           com = new SqlCommand(completeQuery, con);
      //           try
      //           {
      //               con.Open();
      //               com.ExecuteNonQuery();
      //           }
      //           catch (SqlException sqlE)
      //           {
      //               logFourNet.Error(sqlE);
      //               DbExceptionlbl.Text = "Databasproblem, kan inte uppdatera informationen!";
      //               DbExceptionlbl.Visible = true;
      //           }
      //           finally
      //           {
      //               con.Close();
      //           }
      //       }
  }

  public static function update_event_groups() {
    //  DropDownCheckBoxes oldListbox = (DropDownCheckBoxes)HttpContext.Current.Session["presentEventType"];
    //         // Create Add and Delete lists.
    //         IList<string> toDeleteItems = new List<string>();
    //         IList<string> toAddItems = new List<string>();
    //         for (int i = 0; i < oldListbox.Items.Count; i++)
    //         {
    //             if (oldListbox.Items[i].Selected == true && TypeOfEventDropDownCheckBoxes.Items[i].Selected != true)
    //             {
    //                 toDeleteItems.Add(oldListbox.Items[i].Text);
    //             }
    //             else if (oldListbox.Items[i].Selected != true && TypeOfEventDropDownCheckBoxes.Items[i].Selected == true)
    //             {
    //                 toAddItems.Add(TypeOfEventDropDownCheckBoxes.Items[i].Text);
    //             }
    //         }
    //         // Update dataTable
    //         if (toAddItems.Count > 0 || toDeleteItems.Count > 0)
    //         {
    //             try
    //             {
    //                 if (toAddItems.Count > 0)
    //                 {
    //                     string insertStmt = "INSERT INTO Happy_EvenemangstypsGruppering(Evenemangstypsnamn, EvenemangsID) ";
    //                     insertStmt += "VALUES (@evenemangstypsnamn, @evenemangsID)";
    //                     con = new SqlConnection(connectionString);
    //                     com = new SqlCommand(insertStmt, con);
    //                     con.Open();
    //                     com.Parameters.Add("@evenemangstypsnamn", SqlDbType.VarChar);
    //                     com.Parameters.Add("@evenemangsID", SqlDbType.Int).Value = Convert.ToInt32(ToPublishGv.SelectedRow.Cells[3].Text);
    //                     foreach (string s in toAddItems)
    //                     {
    //                         com.Parameters["@evenemangstypsnamn"].Value = s.ToString();
    //                         com.ExecuteNonQuery();
    //                     }
    //                 }
    //                 if (toDeleteItems.Count > 0)
    //                 {
    //                     string insertStmt = "DELETE FROM Happy_EvenemangstypsGruppering ";
    //                     insertStmt += "WHERE Evenemangstypsnamn = @evenemangstypsnamn ";
    //                     insertStmt += "AND EvenemangsID = @evenemangsID";
    //                     con = new SqlConnection(connectionString);
    //                     com = new SqlCommand(insertStmt, con);
    //                     con.Open();
    //                     com.Parameters.Add("@evenemangstypsnamn", SqlDbType.VarChar);
    //                     com.Parameters.Add("@evenemangsID", SqlDbType.Int).Value = Convert.ToInt32(ToPublishGv.SelectedRow.Cells[3].Text);
    //                     foreach (string s in toDeleteItems)
    //                     {
    //                         com.Parameters["@evenemangstypsnamn"].Value = s.ToString();
    //                         com.ExecuteNonQuery();
    //                     }
    //                 }
    //             }
    //             catch (SqlException sqlE)
    //             {
    //                 logFourNet.Error(sqlE);
    //                 DbExceptionlbl.Text = "Databasproblem, kan inte hämta informationen!";
    //                 DbExceptionlbl.Visible = true;
    //             }
    //             finally
    //             {
    //                 con.Dispose();
    //             }
    //         }
  }

  public static function update_event_administration_unit() {
      // DropDownCheckBoxes oldUnitListbox = (DropDownCheckBoxes)HttpContext.Current.Session["presentEventUnit"];
      //       // Create Add and Delete lists.
      //       IList<string> toDeleteUnitItems = new List<string>();
      //       IList<string> toAddUnitItems = new List<string>();
      //       for (int i = 0; i < oldUnitListbox.Items.Count; i++)
      //       {
      //           if (oldUnitListbox.Items[i].Selected == true && UnitDropDownCheckBoxes.Items[i].Selected != true)
      //           {
      //               toDeleteUnitItems.Add(oldUnitListbox.Items[i].Value);
      //           }
      //           else if (oldUnitListbox.Items[i].Selected != true && UnitDropDownCheckBoxes.Items[i].Selected == true)
      //           {
      //               toAddUnitItems.Add(UnitDropDownCheckBoxes.Items[i].Value);
      //           }
      //       }
      //       // Update dataTable
      //       if (toAddUnitItems.Count > 0 || toDeleteUnitItems.Count > 0)
      //       {
      //           try
      //           {
      //               if (toAddUnitItems.Count > 0)
      //               {
      //                   string insertStmt = "INSERT INTO Happy_EvenemangsForvaltningsenheter(ForvaltningsenhetsID, EvenemangsID) ";
      //                   insertStmt += "VALUES (@forvaltningsenhetsID, @evenemangsID)";
      //                   con = new SqlConnection(connectionString);
      //                   com = new SqlCommand(insertStmt, con);
      //                   con.Open();
      //                   com.Parameters.Add("@forvaltningsenhetsID", SqlDbType.SmallInt);
      //                   com.Parameters.Add("@evenemangsID", SqlDbType.Int).Value = Convert.ToInt32(ToPublishGv.SelectedRow.Cells[3].Text);
      //                   foreach (string addedUnit in toAddUnitItems)
      //                   {
      //                       com.Parameters["@forvaltningsenhetsID"].Value = Convert.ToInt16(addedUnit.ToString());
      //                       com.ExecuteNonQuery();
      //                   }
      //               }
      //               if (toDeleteUnitItems.Count > 0)
      //               {
      //                   string insertStmt = "DELETE FROM Happy_EvenemangsForvaltningsenheter ";
      //                   insertStmt += "WHERE ForvaltningsenhetsID = @forvaltningsenhetsID ";
      //                   insertStmt += "AND EvenemangsID = @evenemangsID";
      //                   con = new SqlConnection(connectionString);
      //                   com = new SqlCommand(insertStmt, con);
      //                   con.Open();
      //                   com.Parameters.Add("@forvaltningsenhetsID", SqlDbType.SmallInt);
      //                   com.Parameters.Add("@evenemangsID", SqlDbType.Int).Value = Convert.ToInt32(ToPublishGv.SelectedRow.Cells[3].Text);
      //                   foreach (string deletedUnit in toDeleteUnitItems)
      //                   {
      //                       com.Parameters["@forvaltningsenhetsID"].Value = Convert.ToInt16(deletedUnit.ToString());
      //                       com.ExecuteNonQuery();
      //                   }
      //               }
      //           }
      //           catch (SqlException sqlE)
      //           {
      //               logFourNet.Error(sqlE);
      //               DbExceptionlbl.Text = "Databasproblem, kan inte hämta informationen!";
      //               DbExceptionlbl.Visible = true;
      //           }
      //           finally
      //           {
      //               con.Dispose();
      //           }
      //       }
  }

  public static function update_images() {
      // if ((bool)HttpContext.Current.Session["eventHasImage"] == true)
      //       {
      //           if (FileUploadControl.HasFile == true)
      //           {
      //               // Remove-Add the existing data of the current image in the DB and remove the file from the server folders.
      //               DeleteOldImageDataInDB(ToPublishGv.SelectedRow.Cells[3].Text);
      //               DeleteEventImageOnServers(ToPublishGv.SelectedRow.Cells[3].Text);
      //               InsertToHappy_Bilder(ToPublishGv.SelectedRow.Cells[3].Text);
      //               SaveEventImageOnServers(ToPublishGv.SelectedRow.Cells[3].Text);
      //           }
      //           else if (FileUploadControl.HasFile == false && CopyrightTbx.Text != (string)HttpContext.Current.Session["presentEventCopyright"])
      //           {
      //               // For renaming the image before updating the DB.
      //               string oldImageName = GetNewImageName(ToPublishGv.SelectedRow.Cells[3].Text) + ".jpg";
      //               UpDateCopyWrightHappy_Bilder(ToPublishGv.SelectedRow.Cells[3].Text);
      //               RenameEventImageOnServers(ToPublishGv.SelectedRow.Cells[3].Text, oldImageName);
      //           }
      //       }
      //       else
      //       {
      //           if (FileUploadControl.HasFile && (FileUploadControl.PostedFile.FileName.EndsWith(".jpg") || FileUploadControl.PostedFile.FileName.EndsWith(".JPG")))
      //           {
      //               // Insert-Add the image in the DB and the server folders.
      //               InsertToHappy_Bilder(ToPublishGv.SelectedRow.Cells[3].Text);
      //               SaveEventImageOnServers(ToPublishGv.SelectedRow.Cells[3].Text);
      //           }
      //           else if (FileUploadControl.HasFile == false && CopyrightTbx.Text != (string)HttpContext.Current.Session["presentEventCopyright"])
      //           {
      //               // For renaming the image before updating the DB.
      //               string oldImageName = GetNewImageName(ToPublishGv.SelectedRow.Cells[3].Text) + ".jpg";
      //               UpDateCopyWrightHappy_Bilder(ToPublishGv.SelectedRow.Cells[3].Text);
      //               RenameEventImageOnServers(ToPublishGv.SelectedRow.Cells[3].Text, oldImageName);
      //           }
      //       }
  }

  public static function insert_DB_happy_event_times() {
    //  string connetionString = ConfigurationManager.ConnectionStrings["EPiServerDB"].ConnectionString;
    //         SqlConnection con = new SqlConnection(connetionString);
    //         SqlCommand command2 = con.CreateCommand();
    //         string insertHappyEvenemangsTider = "INSERT INTO Happy_Evenemangstider(Datum, Tid, Pris, EvenemangsID) ";
    //         insertHappyEvenemangsTider += "VALUES (@datum, @tid, @pris, @evenemangsID)";
    //         command2.CommandText = insertHappyEvenemangsTider;
     //
    //         //Adding parameters for Happy_Evenemangstider
    //         command2.Parameters.AddWithValue("@datum", SqlDbType.VarChar);
    //         command2.Parameters.AddWithValue("@tid", SqlDbType.VarChar);
    //         command2.Parameters.AddWithValue("@pris", SqlDbType.SmallInt);
    //         command2.Parameters.AddWithValue("@evenemangsID", SqlDbType.Int);
     //
    //         //Adding parameters values
    //         command2.Parameters["@tid"].Value = TimeTextBox.Text;
    //         command2.Parameters["@pris"].Value = DBNull.Value;
    //         command2.Parameters["@evenemangsID"].Value = pEventID;
    //         try
    //         {
    //             con.Open();
    //             if (isPeriod)
    //             {
    //                 foreach (DateTime dateInRange in allDatesInPeriod)
    //                 {
    //                     command2.Parameters["@datum"].Value = dateInRange.ToShortDateString();
    //                     command2.ExecuteNonQuery();
    //                 }
    //             }
    //             else
    //             {
    //                 command2.Parameters["@datum"].Value = DateTextBox.Text;
    //                 command2.ExecuteNonQuery();
    //             }
    //         }
    //         catch (SqlException sqlE)
    //         {
    //             logFourNet.Error(sqlE);
    //             DbExceptionlbl.Text = "Databasproblem, kan inte lägga till informationen!";
    //             DbExceptionlbl.Visible = true;
    //         }
    //         finally
    //         {
    //             con.Dispose();
    //         }
  }

  public static function delete_DB_happy_event_times() {
      // string deleteHappyEvenemangsTider = "DELETE FROM Happy_Evenemangstider ";
      //       deleteHappyEvenemangsTider += "WHERE Datum >= convert(VARCHAR(10), GETDATE(), 120) ";
      //       deleteHappyEvenemangsTider += "AND EvenemangsID = " + pEventID;
      //       string connetionString = ConfigurationManager.ConnectionStrings["EPiServerDB"].ConnectionString;
      //       SqlConnection con = new SqlConnection(connetionString);
      //       SqlCommand deleteCommand = new SqlCommand(deleteHappyEvenemangsTider, con);
      //       try
      //       {
      //           con.Open();
      //           deleteCommand.ExecuteNonQuery();
      //       }
      //       catch (SqlException sqlE)
      //       {
      //           logFourNet.Error(sqlE);
      //           DbExceptionlbl.Text = "Databasproblem, kan inte ta bort informationen!";
      //           DbExceptionlbl.Visible = true;
      //       }
      //       finally
      //       {
      //           con.Dispose();
      //       }
  }
}

?>
