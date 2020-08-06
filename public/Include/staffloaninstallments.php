  <?php

include( "plugin/php/DataTables.php" );

// Alias Editor classes so they are easy to use
use
  DataTables\Editor,
  DataTables\Editor\Field,
  DataTables\Editor\Format,
  DataTables\Editor\Mjoin,
  DataTables\Editor\Upload,
  DataTables\Editor\Validate;

// DataTables PHP library

  // Build our Editor instance and process the data coming from _POST
  $editor=Editor::inst( $db, 'staffloaninstallments' )
  	->fields(
        Field::inst( 'staffloaninstallments.Id' ),
        Field::inst( 'staffloaninstallments.StaffLoanId' ),
        Field::inst( 'staffloaninstallments.Payment_Date' ),
        Field::inst( 'staffloaninstallments.Amount' ),
        Field::inst( 'staffloaninstallments.Paid' ),
        Field::inst( 'staffloaninstallments.created_at' ),
        Field::inst( 'staffloaninstallments.updated_at' )
      )
      ->validator( function ( $editor, $action, $data ) use ($db) {
            if ( $action === Editor::ACTION_CREATE) {
              $count = $db->raw()
                 ->bind(':Id', $data['data'][0]['staffloaninstallments']['StaffLoanId'])
                 ->exec("SELECT Id FROM staffloaninstallments WHERE StaffLoanId = :Id")
                 ->count();

              if ($count >= 4) {
                return 'Cannot create more than 4 installment records';
              }
            }
      })
      ->on('preEdit', function ($editor, $id, $values) use ($db) {

          $db->raw()
             ->bind(':Id', $id)
             ->bind(':updated_at', date('Y-m-d H:i:s'))
             ->exec("UPDATE staffloaninstallments SET updated_at = :updated_at WHERE Id = :Id");


      })
      ->on('preCreate', function ($editor, $values) use ($db) {



      });

      if (isset( $_GET['Id'])) {
        $editor
        ->where('staffloaninstallments.StaffLoanId',$_GET['Id'] );
      }

      if (isset( $_POST['Id'])) {
        $editor
        ->where('staffloaninstallments.StaffLoanId',$_POST['Id'] );
      }

      $editor->process( $_POST )->json();
