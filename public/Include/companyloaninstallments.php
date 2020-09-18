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
  $editor=Editor::inst( $db, 'companyloaninstallments' )
  	->fields(
        Field::inst( 'companyloaninstallments.Id' ),
        Field::inst( 'companyloaninstallments.CompanyLoanId' ),
        Field::inst( 'companyloaninstallments.Payment_Date' ),
        Field::inst( 'companyloaninstallments.Amount' ),
        Field::inst( 'companyloaninstallments.Paid' ),
        Field::inst( 'companyloaninstallments.created_at' ),
        Field::inst( 'companyloaninstallments.updated_at' )
      )
      ->validator( function ( $editor, $action, $data ) use ($db) {
            if ( $action === Editor::ACTION_CREATE) {
              $count = $db->raw()
                 ->bind(':Id', $data['data'][0]['companyloaninstallments']['CompanyLoanId'])
                 ->exec("SELECT Id FROM companyloaninstallments WHERE CompanyLoanId = :Id")
                 ->count();

              if ($count >= 12) {
                return 'Cannot create more than 12 installment records';
              }
            }
      })
      ->on('preEdit', function ($editor, $id, $values) use ($db) {

          $db->raw()
             ->bind(':Id', $id)
             ->bind(':updated_at', date('Y-m-d H:i:s'))
             ->exec("UPDATE companyloaninstallments SET updated_at = :updated_at WHERE Id = :Id");


      })
      ->on('preCreate', function ($editor, $values) use ($db) {



      });

      if (isset( $_GET['Id'])) {
        $editor
        ->where('companyloaninstallments.CompanyLoanId',$_GET['Id'] );
      }

      if (isset( $_POST['Id'])) {
        $editor
        ->where('companyloaninstallments.CompanyLoanId',$_POST['Id'] );
      }

      $editor->process( $_POST )->json();
