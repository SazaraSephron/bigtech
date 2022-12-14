<?php

/**
 * handles admin list "with selected" submissions
 *
 * @package    WordPress
 * @subpackage Participants Database Plugin
 * @author     Roland Barker <webdesign@xnau.com>
 * @copyright  2020  xnau webdesign
 * @license    GPL3
 * @version    0.1
 * @link       http://xnau.com/wordpress-plugins/
 * @depends    
 */

namespace PDb_admin_list;

use \Participants_Db;

defined( 'ABSPATH' ) || exit;

class with_selected {

  /**
   * @var array of record ids
   */
  private $selected_ids;

  /**
   * @var string holds the query that was executed
   */
  private $last_query = '';

  /**
   * handles the chosen action
   * 
   * @param array $selected_ids array of record ids
   */
  public function __construct( $selected_ids )
  {
    $this->selected_ids = $selected_ids;
  }

  /**
   * executes the action
   * 
   * @param string $action
   * @return string the db query for the action
   */
  public function execute( $action )
  {
    if ( $this->id_count() > 0 ) {
      if ( method_exists( $this, $action ) ) {
        $this->{$action}();
      } else {
        $this->with_selected_action( $action );
      }
    }
    $this->clear_cache();
  }
  
  /**
   * clears the cache for the selected ids
   */
  protected function clear_cache()
  {
    \PDb_Participant_Cache::make_all_stale();
  }
  
  /**
   * provides the db query used for the action
   * 
   * @return string empty if no db action was performed
   */
  public function last_query()
  {
    return $this->last_query;
  }

  /**
   * handles record deletion
   * 
   * @global \wpdb $wpdb
   */
  private function delete()
  {
    $delete_files = filter_input( INPUT_POST, delete::file_delete_preference, FILTER_VALIDATE_BOOLEAN );
    \PDb_List_Admin::set_admin_user_setting(delete::file_delete_preference, $delete_files );
    delete::delete_records( $this->selected_ids, $delete_files);
  }

  /**
   * handles approve actions
   */
  private function approve()
  {
    $this->last_query = $this->approval( 'approve' );
  }

  /**
   * handles unapproval action
   */
  private function unapprove()
  {
    $this->last_query = $this->approval( 'unapprove' );
  }

  /**
   * resends the signup email
   */
  private function send_signup_email()
  {
    $email_limit = Participants_Db::apply_filters( 'mass_email_session_limit', Participants_Db::$mass_email_session_limit );
    $send_count = 0;

    foreach ( array_slice( $this->selected_ids, 0, $email_limit ) as $id ) {
      $data = Participants_Db::get_participant( $id );
      $recipient = $data[Participants_Db::plugin_setting( 'primary_email_address_field' )];

      $success = \PDb_Template_Email::send( array(
                  'to' => $recipient,
                  'subject' => Participants_Db::apply_filters( 'receipt_email_subject', Participants_Db::plugin_setting( 'signup_receipt_email_subject' ), $data ),
                  'template' => Participants_Db::apply_filters( 'receipt_email_template', Participants_Db::plugin_setting( 'signup_receipt_email_body' ), $data ),
                  'context' => __METHOD__,
                      ), $data );
      $send_count += (int) $success;
      do_action( 'pdb-list_admin_with_selected_send_signup_email', $data );
    }

    $message_type = $send_count > 0 ? 'success' : 'warning';
    $message = sprintf( _nx( '%d email was sent.', '%d emails were sent.', $send_count, 'number of emails sent', 'participants-database' ), $send_count );
    Participants_Db::set_admin_message( $message, $message_type );
  }

  /**
   * sends the resend link email
   */
  private function send_resend_link_email()
  {
    $email_limit = Participants_Db::apply_filters( 'mass_email_session_limit', Participants_Db::$mass_email_session_limit );
    $send_count = 0;
    
    foreach ( array_slice( $this->selected_ids, 0, $email_limit ) as $id ) {
      $data = Participants_Db::get_participant( $id );
      $recipient = $data[Participants_Db::plugin_setting( 'primary_email_address_field' )];

      $success = \PDb_Template_Email::send( array(
                  'to' => $recipient,
                  'subject' => Participants_Db::plugin_setting( 'retrieve_link_email_subject' ),
                  'template' => Participants_Db::plugin_setting( 'retrieve_link_email_body' ),
                  'context' => __METHOD__,
                      ), $data );
      $send_count += (int) $success;
      do_action( 'pdb-list_admin_with_selected_send_resend_link', $data );
    }
    
    $message_type = $send_count > 0 ? 'success' : 'warning';
    $message = sprintf( _nx( '%d email was sent.', '%d emails were sent.', $send_count, 'number of emails sent', 'participants-database' ), $send_count );
    Participants_Db::set_admin_message( $message, $message_type );
  }

  /**
   * handles an approval/unapproval action
   * 
   * @global \wpdb $wpdb
   * @param string $selected_action "approve" or 'unapprove'
   * @return string the db query used
   */
  private function approval( $selected_action )
  {
    global $wpdb;
    
    $approval_field_name = Participants_Db::apply_filters( 'approval_field', 'approved' );
    $approval_field = Participants_Db::$fields[$approval_field_name];
    /* @var $approval_field \PDb_Form_Field_Def */
    
    list ( $yes, $no ) = $approval_field->option_values();
    $set_value = $selected_action === 'approve' ? $yes : $no;
    
    // get a list of ids that will be changed
    $ids_to_update = $this->id_set_to_change( $approval_field_name, $set_value, $this->selected_ids );

    $result = false;
    
    if ( count( $ids_to_update ) > 0 ) {
      $pattern = count( $ids_to_update ) > 1 ? 'IN ( ' . trim( str_repeat( '%s,', count( $ids_to_update ) ), ',' ) . ' )' : '= "%s"';

      $sql = "UPDATE " . Participants_Db::$participants_table . " SET `$approval_field_name` = '$set_value' WHERE id $pattern";
      $result = $wpdb->query( $wpdb->prepare( $sql, $ids_to_update ) );
      $last_query = $wpdb->last_query;
    } else {
      $result = 0;
      $last_query = '';
    }
    
    if ( $result !== false ) {
      
      do_action( 'pdb-list_admin_with_selected_' . $selected_action, $ids_to_update );
      
      $unchanged = $this->id_count() - $result;
      $message = array();
      if ( $result > 0 ) {
        $message[] = sprintf( _nx( 'Approval status for %d record has been updated.', 'Approval status for %d records has been updated.', $result, 'number of records with approval statuses set', 'participants-database' ), $result );
      }
      if ( $unchanged > 0 ) {
        $message[] = ' ' . sprintf( _n( 'Approval status for %d record was unchanged.', 'Approval status for %d records was unchanged.', $unchanged, 'participants-database' ), $unchanged );
      }
      
      Participants_Db::set_admin_message( implode( ' ', $message ), 'updated' );
      
      /**
       * this posts any additional messages added by other plugins
       */
      $added_message = Participants_Db::apply_filters( 'admin_list_action_feedback', '' );
      if ( !empty( $added_message ) ) {
        Participants_Db::set_admin_message( $added_message, 'updated' );
      }
    }
    
    return $last_query;
  }
  
  /**
   * provides a set of record ids from a set with the specified column value
   * 
   * this is designed to remove record ids from the set if they won't be changed
   * 
   * @global \wpdb $wpdb
   * @param string $column name of the column to check
   * @param string $test_value value to look for
   * @param array $set of record ids to check
   * @return number of records with the tested value
   */
  private function id_set_to_change( $column, $test_value, $set )
  {
    global $wpdb;
    
    $sql = 'SELECT p.id FROM ' . Participants_Db::$participants_table . ' p WHERE p.id IN (' . implode( ', ', $set ) . ') AND p.' . $column . ' <> %s';
    
    $result = $wpdb->get_col( $wpdb->prepare( $sql, $test_value ) );
    
    return $result;
  }

  /**
   * handles an externally-defined action
   * 
   * @param string $action name of the action
   */
  private function with_selected_action( $action )
  {
    /**
     * @action pdb_admin_list_with_selected/{$selected_action}
     * 
     * this action is executed if none of the default actions were selected 
     * so that a custom action can be performed
     * 
     * @param array of selected record ids
     */
    do_action( 'pdb_admin_list_with_selected/' . $action, $this->selected_ids );

    /**
     * @filter pdb-admin_list_action_feedback
     * 
     * @param string feedback to show after the action has been performed
     */
    $message = Participants_Db::apply_filters( 'admin_list_action_feedback', '' );
    
    if ( !empty( $message ) ) {
      Participants_Db::set_admin_message( $message, 'updated' );
    }
  }

  /**
   * provides the number of ids to process
   * 
   * @return int
   */
  private function id_count()
  {
    return is_countable( $this->selected_ids ) ? count( $this->selected_ids ) : 0;
  }

}
