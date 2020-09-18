@extends('app')

@section('datatable-css')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/keytable/2.1.2/css/keyTable.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/autofill/2.1.2/css/autoFill.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/colreorder/1.3.2/css/colReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/css/editor.dataTables.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('/plugin/examples/resources/syntax/shCore.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/Plugin/examples/resources/demo.css') }}"> --}}

    <style type="text/css" class="init">
      a.buttons-collection {
        margin-left: 1em;
      }
      .container1{
              width: 1200px;
              margin-left: 50px;
              padding: 10px;
      }

      .select2 span{
        /*width:100%;*/
      }
      .table-bordered>tbody>tr>th{
        background-color: #dedcdc;
      }



      .has-error{
        font-style: italic;
      }



    </style>

@endsection

@section('datatable-js')

      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/autofill/2.1.2/js/dataTables.autoFill.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/colreorder/1.3.2/js/dataTables.colReorder.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/keytable/2.1.2/js/dataTables.keyTable.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.html5.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.print.min.js"></script>

      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/js/dataTables.editor.min.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/datatables/extensions/Select2/editor.select2.js') }}"></script>

      {{-- <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/syntax/shCore.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/demo.js') }}"></script>
      <script type="text/javascript" language="javascript" src="{{ asset('/plugin/examples/resources/editor-demo.js') }}"></script> --}}

      <script type="text/javascript" language="javascript" class="init">
          var vpntable;
          var vpneditor;
          var nastable;
          var naseditor;
          var domainmidascomtable;
          var domainmidascomeditor;
          var omavtable;
          var omaveditor;
          var domainmidascommytable;
          var domainmidascommyeditor;
          var emailautotable;
          var emailautoeditor;

          var domainhncccommytable;
          var domainhncccommyeditor;

          $(document).ready(function() {

                     vpneditor = new $.fn.dataTable.Editor( {
                            ajax: "{{ asset('/Include/vpn.php') }}",
                             table: "#vpntable",
                             idSrc: "vpn.Id",
                             fields: [
                                     {
                                            label: "Staff Name:",
                                            name: "vpn.UserId",
                                            type:  'select2',
                                            options: [
                                                { label :"", value: "0" },
                                                @foreach($users as $user)
                                                    { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                                @endforeach

                                            ],

                                     },{
                                             label: "User ID:",
                                             name: "vpn.User_ID"
                                     }, {
                                             label: "Password:",
                                             name: "vpn.Password"
                                     }
                             ]
                     } );

                     naseditor = new $.fn.dataTable.Editor( {
                            ajax: "{{ asset('/Include/nas.php') }}",
                             table: "#nastable",
                             idSrc: "nas.Id",
                             fields: [
                                     {
                                            label: "Staff Name:",
                                            name: "nas.UserId",
                                            type:  'select2',
                                            options: [
                                                { label :"", value: "0" },
                                                @foreach($users as $user)
                                                    { label :"{{$user->Name}}", value: "{{$user->Id}}" },
                                                @endforeach

                                            ],
                                     },{
                                             label: "PC Name:",
                                             name: "nas.PC_Name",
                                             type: "select2",
                                             opts: {
                                              tags: true,
                                              data: [
                                                @foreach($pcoptions as $pc)
                                                {
                                                  text: "{{$pc->Option}}", id: "{{$pc->Option}}"
                                                },
                                                @endforeach
                                              ]
                                             }
                                     },{
                                             label: "User ID:",
                                             name: "nas.User_ID"
                                     },{
                                             label: "Password:",
                                             name: "nas.Password"
                                     },{
                                             label: "Share Folders:",
                                             name: "nas.Share_Folders",
                                             type: "select2",
                                             opts: {
                                              tags: true,
                                              multiple: true,
                                              data: [
                                                @foreach($folderoptions as $folder)
                                                {
                                                  text: "{{$folder->Option}}", id: "{{$folder->Option}}"
                                                },
                                                @endforeach
                                              ]
                                             }
                                     }
                             ]
                     } );

                     naseditor.on('initEdit', function ( e, json, data ) {
                        // the list of users
                        var folders = [
                            @foreach($folderoptions as $folder)
                              "{{ $folder->Option }}",
                            @endforeach
                        ];

                        // the selected folders from edit
                        var selectedFolders = data['nas']['Share_Folders'].split(',').map(function(i) { return i.trim(); });

                        // combine list and selected
                        var combinedFolders = folders.concat(selectedFolders);

                        // remove the duplicates
                        var uniqueFolders = combinedFolders.filter(function(item, pos) {
                            return combinedFolders.indexOf(item) == pos;
                        });

                        naseditor.field('nas.Share_Folders')
                          .update(uniqueFolders)
                          .val(selectedFolders);

                        // the list of users
                        var pc = [
                            @foreach($pcoptions as $pc)
                              "{{ $pc->Option }}",
                            @endforeach
                        ];

                        // the selected folders from edit
                        var selectedPC = data['nas']['PC_Name'];

                        // combine list and selected
                        var combinedPC = pc.concat(selectedPC);

                        // remove the duplicates
                        var uniquePC = combinedPC.filter(function(item, pos) {
                            return combinedPC.indexOf(item) == pos;
                        });

                        naseditor.field('nas.PC_Name')
                          .update(uniquePC)
                          .val(selectedPC);
                     });

                     naseditor.on('preSubmit', function ( e, json, action, key ) {
                        if (action === 'create' || action === 'edit') {
                            $.each( json.data, function ( key, value ) {
                                 json.data[key].nas.Share_Folders = json.data[key].nas.Share_Folders.join(', ');
                            });
                        }

                     });


                     domainmidascomeditor = new $.fn.dataTable.Editor( {
                           ajax: {
                              "url": "{{ asset('/Include/domain.php') }}",
                              "data": {
                                  "company": "Midascomcommy"
                              }
                            },
                             table: "#domainmidascomtable",
                             idSrc: "domain.Id",
                             fields: [
                                     {
                                             label: "Company:",
                                             name: "domain.Company",
                                             type: "hidden",
                                             def: "Midascomcommy"

                                     },{
                                             label: "Email:",
                                             name: "domain.Email"
                                     },{
                                             label: "Password:",
                                             name: "domain.Password"
                                     },{
                                             label: "Created_On:",
                                             name: "domain.Created_On",
                                             type:   'datetime',
                                             format: 'DD-MMM-YYYY'
                                     },{
                                             label: "Request_By:",
                                             name: "domain.Request_By",
                                             type: "select2",
                                             opts: {
                                              tags: true,
                                              multiple: true,
                                              data: [
                                                @foreach($users as $user1)
                                                {
                                                  @if ($user1->Nick_Name == "")
                                                  text: "{{strtoupper($user1->Name)}}", id: "{{strtoupper($user1->Name)}}"
                                                  @else
                                                  text: "{{strtoupper($user1->Nick_Name)}}", id: "{{strtoupper($user1->Nick_Name)}}"
                                                  @endif
                                                },
                                                @endforeach
                                              ]
                                             }

                                     }
                             ]
                     } );

                     domainmidascomeditor.on('preSubmit', function ( e, json, action, key ) {
                        if (action === 'create' || action === 'edit') {
                            $.each( json.data, function ( key, value ) {
                                 json.data[key].domain.Request_By = json.data[key].domain.Request_By.join(', ').toUpperCase();
                            });
                        }

                     });

                     domainmidascomeditor.on('initEdit', function ( e, json, data ) {
                        // the list of users
                        var users = [
                            @foreach($users as $user2)
                              "{{ $user2->Nick_Name == '' ? $user2->Name : $user2->Nick_Name }}",
                            @endforeach
                        ];

                        // the selected users from edit
                        var selectedUsers = data['domain']['Request_By'].split(',').map(function(i) { return i.trim(); });

                        // combine list and selected
                        var combinedUsers = users.concat(selectedUsers);

                        // remove the duplicates
                        var uniqueUsers = combinedUsers.filter(function(item, pos) {
                            return combinedUsers.indexOf(item) == pos;
                        });

                        domainmidascomeditor.field('domain.Request_By')
                          .update(uniqueUsers)
                          .val(selectedUsers);
                     });

                     omaveditor = new $.fn.dataTable.Editor( {
                             ajax: {
                                "url": "{{ asset('/Include/domain.php') }}",
                                "data": {
                                    "company": "Omav"
                                }
                              },
                             table: "#omavtable",
                             idSrc: "domain.Id",
                             fields: [
                                     {
                                             label: "Company:",
                                             name: "domain.Company",
                                             type: "hidden"

                                     },{
                                             label: "Email:",
                                             name: "domain.Email"
                                     },{
                                             label: "Password:",
                                             name: "domain.Password"
                                     },{
                                             label: "Created_On:",
                                             name: "domain.Created_On",
                                             type:   'datetime',
                                             format: 'DD-MMM-YYYY'
                                     },{
                                             label: "Request_By:",
                                             name: "domain.Request_By",
                                             type: "select2",
                                             opts: {
                                              tags: true,
                                              multiple: true,
                                              data: [
                                                @foreach($users as $user1)
                                                {
                                                  @if ($user1->Nick_Name == "")
                                                  text: "{{strtoupper($user1->Name)}}", id: "{{strtoupper($user1->Name)}}"
                                                  @else
                                                  text: "{{strtoupper($user1->Nick_Name)}}", id: "{{strtoupper($user1->Nick_Name)}}"
                                                  @endif
                                                },
                                                @endforeach
                                              ]
                                             }

                                     }
                             ]
                     } );

                     omaveditor.on('preSubmit', function ( e, json, action, key ) {
                        if (action === 'create' || action === 'edit') {
                            $.each( json.data, function ( key, value ) {
                                 json.data[key].domain.Request_By = json.data[key].domain.Request_By.join(', ').toUpperCase();
                            });
                        }

                     });

                     omaveditor.on('initEdit', function ( e, json, data ) {
                        // the list of users
                        var users = [
                            @foreach($users as $user2)
                              "{{ $user2->Nick_Name == '' ? $user2->Name : $user2->Nick_Name }}",
                            @endforeach
                        ];

                        // the selected users from edit
                        var selectedUsers = data['domain']['Request_By'].split(',').map(function(i) { return i.trim(); });

                        // combine list and selected
                        var combinedUsers = users.concat(selectedUsers);

                        // remove the duplicates
                        var uniqueUsers = combinedUsers.filter(function(item, pos) {
                            return combinedUsers.indexOf(item) == pos;
                        });

                        omaveditor.field('domain.Request_By')
                          .update(uniqueUsers)
                          .val(selectedUsers);
                     });

                     domainmidascommyeditor = new $.fn.dataTable.Editor( {
                             ajax: {
                                "url": "{{ asset('/Include/domain.php') }}",
                                "data": {
                                    "company": "Midascommy"
                                }
                              },
                             table: "#domainmidascommytable",
                             idSrc: "domain.Id",
                             fields: [
                                     {
                                             label: "Company:",
                                             name: "domain.Company",
                                             type: "hidden"

                                     },{
                                             label: "Email:",
                                             name: "domain.Email"
                                     },{
                                             label: "Password:",
                                             name: "domain.Password"
                                     },{
                                             label: "NestFrom Password:",
                                             name: "domain.NestFrom_Password"
                                     },{
                                             label: "Created On:",
                                             name: "domain.Created_On",
                                             type:   'datetime',
                                             format: 'DD-MMM-YYYY'
                                     },{
                                             label: "Request By:",
                                             name: "domain.Request_By",
                                             type: "select2",
                                             opts: {
                                              tags: true,
                                              multiple: true,
                                              data: [
                                                @foreach($users as $user1)
                                                {
                                                  @if ($user1->Nick_Name == "")
                                                  text: "{{strtoupper($user1->Name)}}", id: "{{strtoupper($user1->Name)}}"
                                                  @else
                                                  text: "{{strtoupper($user1->Nick_Name)}}", id: "{{strtoupper($user1->Nick_Name)}}"
                                                  @endif
                                                },
                                                @endforeach
                                              ]
                                             }

                                     }
                             ]
                     } );

                     domainmidascommyeditor.on('preSubmit', function ( e, json, action, key ) {
                        if (action === 'create' || action === 'edit') {
                            $.each( json.data, function ( key, value ) {
                                 json.data[key].domain.Request_By = json.data[key].domain.Request_By.join(', ').toUpperCase();
                            });
                        }

                     });

                     domainmidascommyeditor.on('initEdit', function ( e, json, data ) {
                        // the list of users
                        var users = [
                            @foreach($users as $user2)
                              "{{ $user2->Nick_Name == '' ? $user2->Name : $user2->Nick_Name }}",
                            @endforeach
                        ];

                        // the selected users from edit
                        var selectedUsers = data['domain']['Request_By'].split(',').map(function(i) { return i.trim(); });

                        // combine list and selected
                        var combinedUsers = users.concat(selectedUsers);

                        // remove the duplicates
                        var uniqueUsers = combinedUsers.filter(function(item, pos) {
                            return combinedUsers.indexOf(item) == pos;
                        });

                        domainmidascommyeditor.field('domain.Request_By')
                          .update(uniqueUsers)
                          .val(selectedUsers);
                     });

                     domainhncccommyeditor = new $.fn.dataTable.Editor( {
                             ajax: {
                                "url": "{{ asset('/Include/domain.php') }}",
                                "data": {
                                    "company": "HNCC"
                                }
                              },
                             table: "#domainhncccommytable",
                             idSrc: "domain.Id",
                             fields: [
                                     {
                                             label: "Company:",
                                             name: "domain.Company",
                                             type: "hidden"

                                     },{
                                             label: "Email:",
                                             name: "domain.Email"
                                     },{
                                             label: "Password:",
                                             name: "domain.Password"
                                     },{
                                             label: "NestFrom Password:",
                                             name: "domain.NestFrom_Password"
                                     },{
                                             label: "Created On:",
                                             name: "domain.Created_On",
                                             type:   'datetime',
                                             format: 'DD-MMM-YYYY'
                                     },{
                                             label: "Request By:",
                                             name: "domain.Request_By",
                                             type: "select2",
                                             opts: {
                                              tags: true,
                                              multiple: true,
                                              data: [
                                                @foreach($users as $user1)
                                                {
                                                  @if ($user1->Nick_Name == "")
                                                  text: "{{strtoupper($user1->Name)}}", id: "{{strtoupper($user1->Name)}}"
                                                  @else
                                                  text: "{{strtoupper($user1->Nick_Name)}}", id: "{{strtoupper($user1->Nick_Name)}}"
                                                  @endif
                                                },
                                                @endforeach
                                              ]
                                             }

                                     }
                             ]
                     } );

                     domainhncccommyeditor.on('preSubmit', function ( e, json, action, key ) {
                        if (action === 'create' || action === 'edit') {
                            $.each( json.data, function ( key, value ) {
                                 json.data[key].domain.Request_By = json.data[key].domain.Request_By.join(', ').toUpperCase();
                            });
                        }

                     });

                     domainhncccommyeditor.on('initEdit', function ( e, json, data ) {
                        // the list of users
                        var users = [
                            @foreach($users as $user2)
                              "{{ $user2->Nick_Name == '' ? $user2->Name : $user2->Nick_Name }}",
                            @endforeach
                        ];

                        // the selected users from edit
                        var selectedUsers = data['domain']['Request_By'].split(',').map(function(i) { return i.trim(); });

                        // combine list and selected
                        var combinedUsers = users.concat(selectedUsers);

                        // remove the duplicates
                        var uniqueUsers = combinedUsers.filter(function(item, pos) {
                            return combinedUsers.indexOf(item) == pos;
                        });

                        domainhncccommyeditor.field('domain.Request_By')
                          .update(uniqueUsers)
                          .val(selectedUsers);
                     });

                     emailautoeditor = new $.fn.dataTable.Editor( {
                             ajax: {
                                "url": "{{ asset('/Include/emailautoforwarder.php') }}",
                                "data": {
                                    "domain": "{{$domain}}"
                                }

                              },
                             table: "#emailautotable",
                             idSrc: "emailautoforwarder.Id",
                             fields: [
                                     {
                                             label: "Domain:",
                                             name: "emailautoforwarder.Domain",
                                             type: "hidden",
                                             def: "{{$domain}}"
                                     },{
                                             label: "Group Email:",
                                             name: "emailautoforwarder.Group_Email"
                                     },{
                                             label: "User:",
                                             name: "emailautoforwarder.User",
                                             type: "select2",
                                             opts: {
                                              tags: true,
                                              multiple: true,
                                              data: [
                                                @foreach($users as $user1)
                                                {
                                                  @if ($user1->Nick_Name == "")
                                                  text: "{{strtoupper($user1->Name)}}", id: "{{strtoupper($user1->Name)}}"
                                                  @else
                                                  text: "{{strtoupper($user1->Nick_Name)}}", id: "{{strtoupper($user1->Nick_Name)}}"
                                                  @endif
                                                },
                                                @endforeach
                                              ]
                                             }

                                     }
                             ]
                     } );

                     vpntable=$('#vpntable').dataTable( {
                         ajax: {
                            "url": "{{ asset('/Include/vpn.php') }}"
                          },
                            columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            colReorder: false,
                            dom: "Blrtip",
                            bAutoWidth: true,
                            sScrollX: "100%",
                            sScrollY: "100%",
                            bScrollCollapse: true,
                            columns: [
                                    { data: null,"render":"", title:"No"},
                                    { data: "vpn.Id",title: "Id"},
                                    { data: "users.Name", editField: "vpn.UserId",title:'Staff_Name' },
                                    { data: "users.Position" ,title: "Position"},
                                    { data: "vpn.User_ID",title: "User ID" },
                                    { data: "vpn.Password",title: "Password" }


                            ],
                            autoFill: {
                               editor:  vpneditor
                           },
                            select: true,
                            buttons: [
                              // {
                              //   text: 'New',
                              //   action: function ( e, dt, node, config ) {
                              //       // clearing all select/input options
                              //       vpneditor
                              //          .create( false )
                              //          .submit();
                              //   },
                              // },
                              { extend: "create", text: 'New', editor: vpneditor },
                              { extend: "edit", editor: vpneditor },

                              { extend: "remove", editor: vpneditor },
                              {
                                      extend: 'collection',
                                      text: 'Export',
                                      buttons: [
                                              'excel',
                                              'csv',
                                              'pdf'
                                      ]
                              }
                            ],
                     });

                     // $('#vpntable').on( 'click', 'tbody td:not(:first-child)', function (e) {
                     //       vpneditor.inline( this, {
                     //            onBlur: 'submit'
                     //     } );
                     // } );

                     nastable=$('#nastable').dataTable( {
                         ajax: {
                            "url": "{{ asset('/Include/nas.php') }}"
                          },
                            columnDefs: [{ "visible": false, "targets": [1] },{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            colReorder: false,
                            dom: "Blrtip",
                            bAutoWidth: true,
                            sScrollX: "100%",
                            sScrollY: "100%",
                            bScrollCollapse: true,
                            columns: [
                                    { data: null,"render":"", title:"No"},
                                    { data: "nas.Id",title: "Id"},
                                    { data: "users.Name", editField: "nas.UserId",title:'Staff_Name' },
                                    { data: "users.Position",title: "Position" },
                                    { data: "nas.PC_Name",title: "PC Name" },
                                    { data: "nas.User_ID",title: "User ID" },
                                    { data: "nas.Password",title: "Password" },
                                    { data: "nas.Share_Folders",title: "Share Folders" }

                            ],
                            autoFill: {
                               editor:  naseditor
                           },
                            select: true,
                            buttons: [
                              // {
                              //   text: 'New',
                              //   action: function ( e, dt, node, config ) {
                              //       // clearing all select/input options
                              //       naseditor
                              //          .create( false )
                              //          .submit();
                              //   },
                              // },
                              { extend: "create", text: "New", editor: naseditor },
                              { extend: "edit", editor: naseditor },

                              { extend: "remove", editor: naseditor },
                              {
                                      extend: 'collection',
                                      text: 'Export',
                                      buttons: [
                                              'excel',
                                              'csv',
                                              'pdf'
                                      ]
                              }
                            ],
                     });

                     // $('#nastable').on( 'click', 'tbody td:not(:first-child)', function (e) {
                     //       naseditor.inline( this, {
                     //            onBlur: 'submit'
                     //     } );
                     // } );

                     domainmidascomtable=$('#domainmidascomtable').dataTable( {
                         ajax: {
                            "url": "{{ asset('/Include/domain.php') }}",
                            "data": {
                                "company": "Midascomcommy"
                            }
                          },
                            columnDefs: [{ "visible": false, "targets": [1,2,5] },{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            colReorder: false,
                            dom: "Blrtip",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            bAutoWidth: true,
                            bScrollCollapse: true,
                            columns: [
                                    { data: null,"render":"", title:"No"},
                                    { data: "domain.Id",title: "Id"},
                                    { data: "domain.Company",title: "Company"},
                                    { data: "domain.Email",title: "Email" },
                                    { data: "domain.Password",title: "Password" },
                                    { data: "domain.NestFrom_Password",title: "NestFrom Password" },
                                    { data: "domain.Created_On",title: "Created On" },
                                    { data: "domain.Request_By",title: "Request By" }

                            ],
                            autoFill: {
                               editor:  domainmidascomeditor
                           },
                            select: true,
                            buttons: [
                              // {
                              //   text: 'New',
                              //   action: function ( e, dt, node, config ) {
                              //       // clearing all select/input options
                              //       domainmidascomeditor
                              //          .create( false )
                              //          .set( 'domain.Company', "Midascomcommy" )
                              //          .submit();
                              //   },
                              // },
                              { extend: "create", text: "New", editor: domainmidascomeditor },
                              { extend: "edit", editor: domainmidascomeditor },

                              { extend: "remove", editor: domainmidascomeditor },
                              {
                                      extend: 'collection',
                                      text: 'Export',
                                      buttons: [
                                              'excel',
                                              'csv',
                                              'pdf'
                                      ]
                              }
                            ],
                     });

                     // $('#domainmidascomtable').on( 'click', 'tbody td:not(:first-child)', function (e) {
                     //       domainmidascomeditor.inline( this, {
                     //            onBlur: 'submit'
                     //     } );
                     // } );

                     omavtable=$('#omavtable').dataTable( {
                         ajax: {
                            "url": "{{ asset('/Include/domain.php') }}",
                            "data": {
                                "company": "Omav"
                            }
                          },
                            columnDefs: [{ "visible": false, "targets": [1,2,5] },{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            colReorder: false,
                            sScrollX: "100%",
                            sScrollY: "100%",
                            dom: "Blrtip",
                            bAutoWidth: true,
                            bScrollCollapse: true,
                            columns: [
                                    { data: null,"render":"", title:"No"},
                                    { data: "domain.Id",title: "Id"},
                                    { data: "domain.Company",title: "Company"},
                                    { data: "domain.Email",title: "Email" },
                                    { data: "domain.Password",title: "Password" },
                                    { data: "domain.NestFrom_Password",title: "NestFrom Password" },
                                    { data: "domain.Created_On",title: "Created On" },
                                    { data: "domain.Request_By",title: "Request By" }

                            ],
                            autoFill: {
                               editor:  omaveditor
                           },
                            select: true,
                            buttons: [
                              // {
                              //   text: 'New',
                              //   action: function ( e, dt, node, config ) {
                              //       // clearing all select/input options
                              //       omaveditor
                              //          .create( false )
                              //          .set( 'domain.Company', "Omav" )
                              //          .submit();
                              //   },
                              // },
                              { extend: "create", text: "New", editor: omaveditor },
                              { extend: "edit", editor: omaveditor },

                              { extend: "remove", editor: omaveditor },
                              {
                                      extend: 'collection',
                                      text: 'Export',
                                      buttons: [
                                              'excel',
                                              'csv',
                                              'pdf'
                                      ]
                              }
                            ],
                     });

                     // $('#omavtable').on( 'click', 'tbody td:not(:first-child)', function (e) {
                     //       omaveditor.inline( this, {
                     //            onBlur: 'submit'
                     //     } );
                     // } );

                     domainmidascommytable=$('#domainmidascommytable').dataTable( {
                         ajax: {
                            "url": "{{ asset('/Include/domain.php') }}",
                            "data": {
                                "company": "Midascommy"
                            }
                          },
                            columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            colReorder: false,
                            dom: "Blrtip",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            bAutoWidth: true,
                            bScrollCollapse: true,
                            columns: [
                                    { data: null,"render":"", title:"No"},
                                    { data: "domain.Id",title: "Id"},
                                    { data: "domain.Company",title: "Company"},
                                    { data: "domain.Email",title: "Email" },
                                    { data: "domain.Password",title: "Password" },
                                    { data: "domain.NestFrom_Password",title: "NestFrom Password" },
                                    { data: "domain.Created_On",title: "Created On" },
                                    { data: "domain.Request_By",title: "Request By" }

                            ],
                            autoFill: {
                               editor:  domainmidascommyeditor
                           },
                            select: true,
                            buttons: [
                              // {
                              //   text: 'New',
                              //   action: function ( e, dt, node, config ) {
                              //       // clearing all select/input options
                              //       domainmidascommyeditor
                              //          .create( false )
                              //          .set( 'domain.Company', "Midascommy" )
                              //          .submit();
                              //   },
                              // },
                              { extend: "create", text: "New", editor: domainmidascommyeditor },
                              { extend: "edit", editor: domainmidascommyeditor },

                              { extend: "remove", editor: domainmidascommyeditor },
                              {
                                      extend: 'collection',
                                      text: 'Export',
                                      buttons: [
                                              'excel',
                                              'csv',
                                              'pdf'
                                      ]
                              }
                            ],
                     });

                     // $('#domainmidascommytable').on( 'click', 'tbody td:not(:first-child)', function (e) {
                     //       domainmidascommyeditor.inline( this, {
                     //            onBlur: 'submit'
                     //     } );
                     // } );

                     domainhncccommytable=$('#domainhncccommytable').dataTable( {
                         ajax: {
                            "url": "{{ asset('/Include/domain.php') }}",
                            "data": {
                                "company": "HNCC"
                            }
                          },
                            columnDefs: [{ "visible": false, "targets": [1,2,5] },{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            colReorder: false,
                            dom: "Blrtip",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            bAutoWidth: true,
                            bScrollCollapse: true,
                            columns: [
                                    { data: null,"render":"", title:"No"},
                                    { data: "domain.Id",title: "Id"},
                                    { data: "domain.Company",title: "Company"},
                                    { data: "domain.Email",title: "Email" },
                                    { data: "domain.Password",title: "Password" },
                                    { data: "domain.NestFrom_Password",title: "NestFrom Password" },
                                    { data: "domain.Created_On",title: "Created On" },
                                    { data: "domain.Request_By",title: "Request By" }

                            ],
                            autoFill: {
                               editor:  domainhncccommyeditor
                           },
                            select: true,
                            buttons: [
                              // {
                              //   text: 'New',
                              //   action: function ( e, dt, node, config ) {
                              //       // clearing all select/input options
                              //       domainhncccommyeditor
                              //          .create( false )
                              //          .set( 'domain.Company', "HNCC" )
                              //          .submit();
                              //   },
                              // },
                              { extend: "create", text: "New", editor: domainhncccommyeditor },
                              { extend: "edit", editor: domainhncccommyeditor },

                              { extend: "remove", editor: domainhncccommyeditor },
                              {
                                      extend: 'collection',
                                      text: 'Export',
                                      buttons: [
                                              'excel',
                                              'csv',
                                              'pdf'
                                      ]
                              }
                            ],
                     });

                     // $('#domainhncccommytable').on( 'click', 'tbody td:not(:first-child)', function (e) {
                     //       domainhncccommyeditor.inline( this, {
                     //            onBlur: 'submit'
                     //     } );
                     // } );

                     emailautotable=$('#emailautotable').dataTable( {
                         ajax: {
                            "url": "{{ asset('/Include/emailautoforwarder.php') }}",
                            "data": {
                                "domain": "{{$domain}}"
                            }

                          },
                            columnDefs: [{ "visible": false, "targets": [1,2] },{"className": "dt-center", "targets": "_all"}],
                            responsive: false,
                            colReorder: false,
                            dom: "Blrtip",
                            sScrollX: "100%",
                            sScrollY: "100%",
                            bAutoWidth: true,
                            bScrollCollapse: true,
                            columns: [
                                    { data: null,"render":"", title:"No"},
                                    { data: "emailautoforwarder.Id",title: "Id"},
                                    { data: "emailautoforwarder.Domain" ,title: "Domain"},
                                    { data: "emailautoforwarder.Group_Email",title: "Group_Email" },
                                    { data: "emailautoforwarder.User",title: "User" },

                            ],
                            autoFill: {
                               editor:  emailautoeditor
                           },
                            select: true,
                            buttons: [
                              // {
                              //   text: 'New',
                              //   action: function ( e, dt, node, config ) {
                              //       // clearing all select/input options
                              //       emailautoeditor
                              //          .create( false )
                              //          .set( 'emailautoforwarder.Domain', "{{$domain}}" )
                              //          .submit();
                              //   },
                              // },
                              { extend: "create", text: "New", editor: emailautoeditor },
                              { extend: "edit", editor: emailautoeditor },

                              { extend: "remove", editor: emailautoeditor },
                              {
                                      extend: 'collection',
                                      text: 'Export',
                                      buttons: [
                                              'excel',
                                              'csv',
                                              'pdf'
                                      ]
                              }
                            ],
                     });

                     // $('#emailautotable').on( 'click', 'tbody td:not(:first-child)', function (e) {
                     //       emailautoeditor.inline( this, {
                     //            onBlur: 'submit'
                     //     } );
                     // } );
                     //
                     emailautoeditor.on('preSubmit', function ( e, json, action, key ) {
                        if (action === 'create') {
                                json.data[0].emailautoforwarder.User = json.data[0].emailautoforwarder.User.join(', ').toUpperCase();
                        } else if (action === 'edit') {
                                $.each( json.data, function ( key, value ) {
                                     json.data[key].emailautoforwarder.User = json.data[key].emailautoforwarder.User.join(', ').toUpperCase();
                                });
                        }

                     });

                     emailautoeditor.on('initEdit', function ( e, json, data ) {
                        console.log(data['emailautoforwarder']['User']);
                        console.log(data['emailautoforwarder']['User'].split(',').map(function(i) { return i.trim(); }));
                        emailautoeditor.field('emailautoforwarder.User')
                          .update([
                            @foreach($users as $user2)
                              "{{ $user2->Nick_Name == '' ? $user2->Name : $user2->Nick_Name }}",
                            @endforeach
                          ].concat(data['emailautoforwarder']['User'].split(',').map(function(i) { return i.trim(); })))
                          .val(data['emailautoforwarder']['User'].split(',').map(function(i) { return i.trim(); }));
                     });

                       //number ordering in first column
                     vpntable.api().on( 'order.dt search.dt', function () {
                         vpntable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     nastable.api().on( 'order.dt search.dt', function () {
                         nastable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     domainmidascomtable.api().on( 'order.dt search.dt', function () {
                         domainmidascomtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     omavtable.api().on( 'order.dt search.dt', function () {
                         omavtable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     domainmidascommytable.api().on( 'order.dt search.dt', function () {
                         domainmidascommytable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     domainhncccommytable.api().on( 'order.dt search.dt', function () {
                         domainhncccommytable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();

                     emailautotable.api().on( 'order.dt search.dt', function () {
                         emailautotable.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                             cell.innerHTML = i+1;
                         } );
                     } ).draw();



                     //column search

                     $(".vpntable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                             if ($('#vpntable').length > 0)
                             {

                                 var colnum=document.getElementById('vpntable').rows[0].cells.length;

                                 if (this.value=="[empty]")
                                 {

                                    vpntable.fnFilter( '^$', this.name,true,false );
                                 }
                                 else if (this.value=="[nonempty]")
                                 {

                                    vpntable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==true && this.value.length>1)
                                 {

                                    vpntable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==false)
                                 {

                                     vpntable.fnFilter( this.value, this.name,true,false );
                                 }
                             }

                     } );

                     $(".nastable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                             if ($('#nastable').length > 0)
                             {

                                 var colnum=document.getElementById('nastable').rows[0].cells.length;

                                 if (this.value=="[empty]")
                                 {

                                    nastable.fnFilter( '^$', this.name,true,false );
                                 }
                                 else if (this.value=="[nonempty]")
                                 {

                                    nastable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==true && this.value.length>1)
                                 {

                                    nastable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==false)
                                 {

                                     nastable.fnFilter( this.value, this.name,true,false );
                                 }
                             }

                     } );

                     $(".domainmidascomtable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                             if ($('#domainmidascomtable').length > 0)
                             {

                                 var colnum=document.getElementById('domainmidascomtable').rows[0].cells.length;

                                 if (this.value=="[empty]")
                                 {

                                    domainmidascomtable.fnFilter( '^$', this.name,true,false );
                                 }
                                 else if (this.value=="[nonempty]")
                                 {

                                    domainmidascomtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==true && this.value.length>1)
                                 {

                                    domainmidascomtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==false)
                                 {

                                     domainmidascomtable.fnFilter( this.value, this.name,true,false );
                                 }
                             }

                     } );

                     $(".omavtable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                             if ($('#omavtable').length > 0)
                             {

                                 var colnum=document.getElementById('omavtable').rows[0].cells.length;

                                 if (this.value=="[empty]")
                                 {

                                    omavtable.fnFilter( '^$', this.name,true,false );
                                 }
                                 else if (this.value=="[nonempty]")
                                 {

                                    omavtable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==true && this.value.length>1)
                                 {

                                    omavtable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==false)
                                 {

                                     omavtable.fnFilter( this.value, this.name,true,false );
                                 }
                             }

                     } );

                     $(".domainhncccommytable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                             if ($('#domainhncccommytable').length > 0)
                             {

                                 var colnum=document.getElementById('domainhncccommytable').rows[0].cells.length;

                                 if (this.value=="[empty]")
                                 {

                                    domainhncccommytable.fnFilter( '^$', this.name,true,false );
                                 }
                                 else if (this.value=="[nonempty]")
                                 {

                                    domainhncccommytable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==true && this.value.length>1)
                                 {

                                    domainhncccommytable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==false)
                                 {

                                     domainhncccommytable.fnFilter( this.value, this.name,true,false );
                                 }
                             }

                     } );

                     $(".domainmidascommytable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                             if ($('#domainmidascommytable').length > 0)
                             {

                                 var colnum=document.getElementById('domainmidascommytable').rows[0].cells.length;

                                 if (this.value=="[empty]")
                                 {

                                    domainmidascommytable.fnFilter( '^$', this.name,true,false );
                                 }
                                 else if (this.value=="[nonempty]")
                                 {

                                    domainmidascommytable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==true && this.value.length>1)
                                 {

                                    domainmidascommytable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==false)
                                 {

                                     domainmidascommytable.fnFilter( this.value, this.name,true,false );
                                 }
                             }

                     } );

                     $(".emailautotable thead input").keyup ( function () {

                             /* Filter on the column (the index) of this element */
                             if ($('#emailautotable').length > 0)
                             {

                                 var colnum=document.getElementById('emailautotable').rows[0].cells.length;

                                 if (this.value=="[empty]")
                                 {

                                    emailautotable.fnFilter( '^$', this.name,true,false );
                                 }
                                 else if (this.value=="[nonempty]")
                                 {

                                    emailautotable.fnFilter( '^(?=\\s*\\S).*$', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==true && this.value.length>1)
                                 {

                                    emailautotable.fnFilter( '^(?'+ this.value +').*', this.name,true,false );
                                 }
                                 else if (this.value.startsWith("!")==false)
                                 {

                                     emailautotable.fnFilter( this.value, this.name,true,false );
                                 }
                             }

                     } );


                     $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                       var target = $(e.target).attr("href") // activated tab

                         $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();

                     } );

          } );
      </script>

@endsection

@section('content')


    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        IT Services
        <small>IT Support</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">IT Support</a></li>
        <li class="active">IT Services</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- /.col -->
        <div class="col-md-12">

          <div id="update-alert" class="alert alert-success alert-dismissible" style="display:none;">
            <button type="button" class="close" onclick="$('#update-alert').hide()" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Alert!</h4>
            <ul>

            </ul>
          </div>

           <div id="warning-alert" class="alert alert-warning alert-dismissible" style="display:none;">
             <button type="button" class="close" onclick="$('#warning-alert').hide()">&times;</button>
             <h4><i class="icon fa fa-ban"></i> Alert!</h4>
             <ul>

             </ul>
           </div>

          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#emailauto" data-toggle="tab">Email Auto Forwader</a></li>
              <li><a href="#nas" data-toggle="tab">NAS User</a></li>
              <li><a href="#vpn" data-toggle="tab">VPN User</a></li>
              <li><a href="#domainmidascom" data-toggle="tab">DOMAIN - Midascom.com.my</a></li>
              <li><a href="#domainomav" data-toggle="tab">DOMAIN - omav.com.my</a></li>
              <li><a href="#domainmidasmy" data-toggle="tab">DOMAIN - Midascom.my</a></li>
              <li><a href="#domainhncccommy" data-toggle="tab">DOMAIN - hncc.com.my</a></li>

            </ul>

            <div class="tab-content">
              <div class="tab-pane" id="nas">
                <div class="box-body">
                  <table id="nastable" class="nastable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        @if($nasusers)
                        <tr class="search">
                          @foreach($nasusers as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0|| $i==1 )
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                  @endif

                                  <?php $i ++; ?>
                              @endforeach

                              <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                            @endif

                         @endforeach
                        </tr>
                        @endif

                          <tr>
                            @foreach($nasusers as $key=>$value)

                              @if ($key==0)
                                    <td></td>
                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>

                        <?php $i = 0; ?>
                        @foreach($nasusers as $nasuser)

                          <tr id="row_{{ $i }}">

                                <td></td>
                              @foreach($nasuser as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                        @endforeach

                    </tbody>
                      <tfoot></tfoot>
                  </table>
                </div>
              </div>


              <div class="tab-pane" id="vpn">

                <div class="box-body">
                  <table id="vpntable" class="vpntable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        @if($vpnusers)
                        <tr class="search">
                          @foreach($vpnusers as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0|| $i==1 )
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                  @endif

                                  <?php $i ++; ?>
                              @endforeach

                              <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                            @endif

                         @endforeach
                        </tr>
                        @endif

                          <tr>
                            @foreach($vpnusers as $key=>$value)

                              @if ($key==0)
                                    <td></td>
                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>

                        <?php $i = 0; ?>
                        @foreach($vpnusers as $vpnuser)

                          <tr id="row_{{ $i }}">

                                <td></td>
                              @foreach($vpnuser as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                        @endforeach

                    </tbody>
                      <tfoot></tfoot>
                  </table>
                </div>

              </div>

              <div class="tab-pane" id="domainmidascom">

                <div class="box-body">
                  <table id="domainmidascomtable" class="domainmidascomtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        @if($domains)
                        <tr class="search">
                          @foreach($domains as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0|| $i==1 )
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                  @endif

                                  <?php $i ++; ?>
                              @endforeach

                              <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                            @endif

                         @endforeach
                        </tr>
                        @endif

                          <tr>
                            @foreach($domains as $key=>$value)

                              @if ($key==0)
                                    <td></td>
                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>

                        <?php $i = 0; ?>
                        @foreach($domains as $domain2)

                          <tr id="row_{{ $i }}">

                                <td></td>
                              @foreach($domain2 as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                        @endforeach

                    </tbody>
                      <tfoot></tfoot>
                  </table>
                </div>

              </div>

              <div class="tab-pane" id="domainomav">

                <div class="box-body">
                  <table id="omavtable" class="omavtable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        @if($domains)
                        <tr class="search">
                          @foreach($domains as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0|| $i==1 )
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                  @endif

                                  <?php $i ++; ?>
                              @endforeach

                              <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                            @endif

                         @endforeach
                        </tr>
                        @endif

                          <tr>
                            @foreach($domains as $key=>$value)

                              @if ($key==0)
                                    <td></td>
                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>

                        <?php $i = 0; ?>
                        @foreach($domains as $domain2)

                          <tr id="row_{{ $i }}">

                                <td></td>
                              @foreach($domain2 as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                        @endforeach

                    </tbody>
                      <tfoot></tfoot>
                  </table>
                </div>

              </div>

              <div class="tab-pane" id="domainmidasmy">

                <div class="box-body">
                  <table id="domainmidascommytable" class="domainmidascommytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        @if($domains)
                        <tr class="search">
                          @foreach($domains as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0|| $i==1 )
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                  @endif

                                  <?php $i ++; ?>
                              @endforeach

                              <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                            @endif

                         @endforeach
                        </tr>
                        @endif

                          <tr>
                            @foreach($domains as $key=>$value)

                              @if ($key==0)
                                    <td></td>
                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>

                        <?php $i = 0; ?>
                        @foreach($domains as $domain2)

                          <tr id="row_{{ $i }}">

                                <td></td>
                              @foreach($domain2 as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                        @endforeach

                    </tbody>
                      <tfoot></tfoot>
                  </table>
                </div>

              </div>

              <div class="tab-pane" id="domainhncccommy">

                <div class="box-body">
                  <table id="domainhncccommytable" class="domainhncccommytable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        @if($domains)
                        <tr class="search">
                          @foreach($domains as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0|| $i==1 )
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                  @endif

                                  <?php $i ++; ?>
                              @endforeach

                              <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                            @endif

                         @endforeach
                        </tr>
                        @endif

                          <tr>
                            @foreach($domains as $key=>$value)

                              @if ($key==0)
                                    <td></td>
                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>

                        <?php $i = 0; ?>
                        @foreach($domains as $domain2)

                          <tr id="row_{{ $i }}">

                                <td></td>
                              @foreach($domain2 as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                        @endforeach

                    </tbody>
                      <tfoot></tfoot>
                  </table>
                </div>

              </div>

              <div class="active tab-pane" id="emailauto">

                <div class="box-body">

                  <div class="row">

                    <div class="col-md-2">
                      Select Domain:
                    </div>

                    <div class="col-md-4">
                       <select class="form-control" onchange="getval(this);">
                         <option value=""></option>
                         @foreach($typeoptions as $typeoption)
                             <option value="{{$typeoption->Option}}" {{ $typeoption->Option == $domain ? 'selected' : '' }}>{{$typeoption->Option}}</option>
                         @endforeach
                       </select>

                   </div>
                </div>
                <hr>

                  <table id="emailautotable" class="emailautotable" cellspacing="0" width="100%" padding="30px" style="font-size: 13px;">
                      <thead>
                        @if($emailauto)
                        <tr class="search">
                          @foreach($emailauto as $key=>$value)

                            @if ($key==0)
                              <?php $i = 0; ?>

                              @foreach($value as $field=>$a)
                                  @if ($i==0|| $i==1 )
                                    <th align='center'><input type='hidden' class='search_init' name='{{$i}}' placemark='{{$a}}'></th>
                                  @else
                                    <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>
                                  @endif

                                  <?php $i ++; ?>
                              @endforeach

                              <th align='center'><input type='text' class='search_init' name='{{$i}}'  placemark='{{$a}}'></th>

                            @endif

                         @endforeach
                        </tr>
                        @endif

                          <tr>
                            @foreach($emailauto as $key=>$value)

                              @if ($key==0)
                                    <td></td>
                                @foreach($value as $field=>$value)
                                    <td/>{{ $field }}</td>
                                @endforeach

                              @endif

                            @endforeach
                          </tr>
                      </thead>
                      <tbody>

                        <?php $i = 0; ?>
                        @foreach($emailauto as $email)

                          <tr id="row_{{ $i }}">

                                <td></td>
                              @foreach($email as $key=>$value)
                                <td>
                                  {{ $value }}
                                </td>
                              @endforeach
                          </tr>
                        @endforeach

                    </tbody>
                      <tfoot></tfoot>
                  </table>
                </div>

              </div>

              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
    reserved.
  </footer>

  <script>
    $(function () {

      $('#SelectValue').on('change', function() {
        if (this.value=="NRIC")
        {
          $("#nricfield").show();
          $("#passportfield").hide();
          $("#unionnofield").hide();

        }
        else if (this.value=="Passport No") {
          $("#passportfield").show();
          $("#unionnofield").hide();
          $("#nricfield").hide();

        }
        else if (this.value=="Union No"){
          $("#unionnofield").show();
          $("#passportfield").hide();
          $("#nricfield").hide();
        }
        else{

        }

      });

  });
    function changepassword()
    {
      currentpassword=$('[name="CurrentPassword"]').val();
      password=$('[name="Password"]').val();
      confirmpassword=$('[name="ConfirmPassword"]').val();
      if (password!=confirmpassword)
      {
        $("#exist-alert").show();
        $("#changepasswordmessage").html("Password and Confirm Password mismatch!");
      }
      else if (password=="")
      {
        $("#exist-alert").show();
        $("#changepasswordmessage").html("Password cannot be empty!");
      }
      // else if (checkPasswordComplexity(password)!=true)
      // {
      //   $("#exist-alert").show();
      //   $("#changepasswordmessage").html(checkPasswordComplexity(password));
      // }
      else {
        $("#exist-alert").hide();
        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
                    url: "{{ url('/user/changepassword2') }}",
                    method: "POST",
                    data: {UserId:{{$me->UserId}},
                    Password:password,
                    CurrentPassword:currentpassword},
                    success: function(response){
                      if (response==1)
                      {
                        var message="Password Changed!";
                        $('#ChangePassword').modal('hide')
                        $("#update-alert ul").html(message);
                        $("#update-alert").show();

                        setTimeout(function() {
                          $("#update-alert").fadeOut();
                        }, 6000);
                      }
                      else {
                        $("#exist-alert").show();
                        $("#changepasswordmessage").html("Your current password is incorrect!");
                      }
            }
        });
      }
    }
    function checkPasswordComplexity(password) {
      errors="";
      var pattern = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
      if (password.length < 8) {
          errors=errors +"Your password must be at least 8 characters.<br>";
      }
      if (password.search(/[a-zA-Z]/i) < 0) {
          errors=errors +"Your password must contain at least one letter.<br>";
      }
      if (password.search(/[0-9]/) < 0) {
          errors=errors +"Your password must contain at least one digit.<br>";
      }
      if (!pattern.test(password)){
          errors=errors +"Your password must contain at least one symbol.<br>";
      }
      if (errors.length == 0) {
          return true;
      }
      return errors;
  }
  function updateprofile() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });


      userid=$('[name="UserId"]').val();
      staffid=$('[name="StaffId"]').val();
      // usertype=$('[name="User_Type"]').val();
      name=$('[name="Name"]').val();
      nickname=$('[name="Nick_Name"]').val();
      contactno1=$('[name="Contact_No_1"]').val();
      contactno2=$('[name="Contact_No_2"]').val();
      housephoneno=$('[name="House_Phone_No"]').val();
      companyemail=$('[name="Company_Email"]').val();
      personalemail=$('[name="Personal_Email"]').val();
      nationality=$('[name="Nationality"]').val();
      gender=$('[name="Gender"]').val();
      maritalstatus=$('[name="Marital_Status"]').val();
      permanentaddress=$('[name="Permanent_Address"]').val();
      // currentaddress=$('[name="Current_Address"]').val();
      dob=$('[name="DOB"]').val();
      placebirth=$('[name="Place_Of_Birth"]').val();
      race=$('[name="Race"]').val();
      religion=$('[name="Religion"]').val();
      bankname=$('[name="Bank_Name"]').val();
      bankaccountno=$('[name="Bank_Account_No"]').val();
      epfno=$('[name="EPF_No"]').val();
      socsono=$('[name="SOCSO_No"]').val();
      incometaxno=$('[name="Income_Tax_No"]').val();
      nric=$('[name="NRIC"]').val();
      passportno=$('[name="Passport_No"]').val();
      unionno=$('[name="Union_No"]').val();
      position=$('[name="Position"]').val();
      // grade=$('[name="Grade"]').val();
      // superior=$('[name="Superior"]').val();
      emergencycontactperson=$('[name="Emergency_Contact_Person"]').val();
      emergencycontactrelationship=$('[name="Emergency_Contact_Relationship"]').val();
      emergencycontactno=$('[name="Emergency_Contact_No"]').val();
      emergencycontactperson2=$('[name="Emergency_Contact_Person_2"]').val();
      emergencycontactrelationship2=$('[name="Emergency_Contact_Relationship_2"]').val();
      emergencycontactno2=$('[name="Emergency_Contact_No_2"]').val();
      joiningdate=$('[name="Joining_Date"]').val();
      $.ajax({
                  url: "{{ url('/user/updateprofile') }}",
                  method: "POST",
                  data: {
                    UserId:userid,
                    Name:name,
                    StaffId:staffid,
                    Contact_No_1:contactno1,
                    Contact_No_2:contactno2,
                    House_Phone_No:housephoneno,
                    Company_Email:companyemail,
                    Personal_Email:personalemail,
                    Nationality:nationality,
                    Gender:gender,
                    Marital_Status:maritalstatus,
                    Permanent_Address:permanentaddress,
                    DOB:dob,
                    Place_Of_Birth:placebirth,
                    Race:race,
                    Religion:religion,
                    Bank_Name:bankname,
                    Bank_Account_No:bankaccountno,
                    EPF_No:epfno,
                    SOCSO_No:socsono,
                    Income_Tax_No:incometaxno,
                    NRIC:nric,
                    Passport_No:passportno,
                    Union_No:unionno,
                    Position:position,
                    Emergency_Contact_Person:emergencycontactperson,
                    Emergency_Contact_Relationship:emergencycontactrelationship,
                    Emergency_Contact_No:emergencycontactno,
                    Emergency_Contact_Person_2:emergencycontactperson2,
                    Emergency_Contact_Relationship_2:emergencycontactrelationship2,
                    Emergency_Contact_No_2:emergencycontactno2,
                    Joining_Date:joiningdate,
                  },
                  success: function(response){
                    if (response==0)
                    {
                      var message ="No update on profile!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();
                      $('#UpdateProfile').modal('hide')

                      setTimeout(function() {
                        $("#warning-alert").fadeOut();
                      }, 6000);

                      formHasChanged=false;
                    }
                    else {
                      var message ="Profile updated!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").show();

                      setTimeout(function() {
                        $("#update-alert").fadeOut();
                        window.location.reload();
                      }, 6000);

                      $("#Template option:selected").val(response).change();
                      //$("#Template").val(response).change();
                      $("#exist-alert").hide();
                      $('#UpdateProfile').modal('hide')

                      formHasChanged=false;
                    }
          }
      });
  }
  function updateprofilepicture() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      $.ajax({
                  url: "{{ url('/user/updateprofilepicture') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form")[0]),
                  success: function(response){
                    if (response==0)
                    {
                      var message ="No update on profile picture!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();
                      $('#UpdateProfilePicture').modal('hide')

                      setTimeout(function() {
                        $("#warning-alert").fadeOut();
                      }, 6000);
                    }
                    else {
                      var message ="Profile picture updated!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").show();
                      $("#Template option:selected").val(response).change();
                      //$("#Template").val(response).change();
                      $("#exist-alert").hide();
                      $('#UpdateProfilePicture').modal('hide')
                      $('#profileimage').attr('src',response);
                      $("#profilepicture").val("");

                      setTimeout(function() {
                        $("#update-alert").fadeOut();
                      }, 6000);
                    }
          }
      });
  }
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
    //Date picker
    $('#DOB').datepicker({
      format: 'dd-M-yyyy',
      autoclose: true
    });
    $('#DOB1').datepicker({
      format: 'dd-M-yyyy',
      autoclose: true
    });

    $('#Joining_Date').datepicker({
      format: 'dd-M-yyyy',
      autoclose: true
    });
  });

  function getval(sel)
  {
    window.location.href ="{{ url("/ITservice") }}/"+sel.value;
  }
  function uploadresume() {
      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      $.ajax({
                  url: "{{ url('/user/uploadresume') }}",
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data:new FormData($("#upload_form2")[0]),
                  success: function(response){
                    if (response==0)
                    {
                      var message ="Failed to upload resume!";
                      $("#warning-alert ul").html(message);
                      $("#warning-alert").show();

                      setTimeout(function() {
                        $("#warning-alert").fadeOut();
                      }, 6000);
                    }
                    else {
                      var message ="Resume uploaded!";
                      $("#update-alert ul").html(message);
                      $("#update-alert").show();

                      setTimeout(function() {
                        $("#update-alert").fadeOut();
                      }, 6000);
                      //$("#Template").val(response).change();
                      $("#exist-alert").hide();
                      $("#resume").val("");

                      var sub=response.split("|");

                      var html="<div id='resume"+sub[0]+"'>";
                      html+=sub[2];
                      html+="</a>";
                      html+="<a download='"+sub[1]+"' href="+sub[1]+"' title='Download'>";
                      html+="<button type='button' class='btn btn-primary btn-xs'>Download</button>";
                      html+="</a>";
                      html+="</div>";

                      $("#resumediv").append(html);

                    }
          }
      });
  }

  </script>

@endsection
