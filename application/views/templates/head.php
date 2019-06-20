<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Digistudent FTI UNTAR</title>
  <!-- Tell the browser to be responsive to screen width -->
  <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon-icon.ico');?>" type="image/x-icon">
  <link rel="icon" href="<?php echo base_url('assets/images/favicon-icon.ico');?>" type="image/x-icon">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url('bower_components/bootstrap/dist/css/bootstrap.min.css')?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('bower_components/font-awesome/css/font-awesome.min.css')?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url('bower_components/Ionicons/css/ionicons.min.css')?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('dist/css/AdminLTE.min.css')?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url('dist/css/skins/_all-skins.min.css')?>">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo base_url('bower_components/morris.js/morris.css')?>">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo base_url('bower_components/jvectormap/jquery-jvectormap.css')?>">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo base_url('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')?>">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url('bower_components/bootstrap-daterangepicker/daterangepicker.css')?>">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')?>">

  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/timepicker/bootstrap-timepicker.min.css')?>">
   <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')?>">


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- jQuery 3 -->
  <script src="<?php echo base_url('bower_components/jquery/dist/jquery.min.js')?>"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="<?php echo base_url('bower_components/jquery-ui/jquery-ui.min.js')?>"></script>
  
  <link rel="stylesheet" href="<?php echo base_url('bower_components/select2/dist/css/select2.min.css')?>">
  <script src="<?php echo base_url('bower_components/select2/dist/js/select2.full.min.js')?>"></script>
 
 <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')?>">
  <script src="<?php echo base_url('bower_components/datatables.net/js/jquery.dataTables.min.js')?>"></script>
  <script src="<?php echo base_url('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')?>"></script>
  <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css" rel="stylesheet" />
  <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>
  
  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css" rel="stylesheet"/>
  
  <!-- initial Onesignal Function -->
  <link rel="manifest" href="/manifest.json" />
  <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
 
  <script>
      var OneSignal = window.OneSignal || [];
      OneSignal.push(["init", {
          appId: "<?php echo APP_ID?>",
          autoRegister: true,
          //allowLocalhostAsSecureOrigin: true,
          promptOptions: {
              /* These prompt options values configure both the HTTP prompt and the HTTP popup. */
              /* actionMessage limited to 90 characters */
              actionMessage: "We'd like to show you notifications for the latest news.",
              /* acceptButtonText limited to 15 characters */
              acceptButtonText: "ALLOW",
              /* cancelButtonText limited to 15 characters */
              cancelButtonText: "NO THANKS"
          }
      }]);
  </script>
 
  <script>
      function subscribe() {
          // OneSignal.push(["registerForPushNotifications"]);
          OneSignal.push(["registerForPushNotifications"]);
          event.preventDefault();
      }
      function unsubscribe(){
          OneSignal.setSubscription(true);
      }

      var OneSignal = OneSignal || [];
      OneSignal.push(function() {
          /* These examples are all valid */
          // Occurs when the user's subscription changes to a new value.
          OneSignal.on('subscriptionChange', function (isSubscribed) {
              console.log("The user's subscription state is now:", isSubscribed);
              OneSignal.sendTags({
                  UUID: '<?php echo $this->session->userdata('UUID');?>',
                  Level:'<?php echo $this->session->userdata('Level');?>'
              }, function(tagsSent) {
                  // Callback called when tags have finished sending
                  console.log(tagsSent);
              });
          });

          var isPushSupported = OneSignal.isPushNotificationsSupported();
          if (isPushSupported)
          {
              // Push notifications are supported
              OneSignal.isPushNotificationsEnabled().then(function(isEnabled)
              {
                  if (isEnabled)
                  {
                      console.log("Push notifications are enabled!");

                  } else {
                      OneSignal.showHttpPrompt();
                      console.log("Push notifications are not enabled yet.");
                  }
              });

          } else {
              console.log("Push notifications are not supported.");
          }

          OneSignal.on('notificationDisplay', function(event) {
            console.warn('OneSignal notification displayed:', event);
          });
      });


  </script>
 
    <script>
    //FUngsi reload notifikasi
        function Checker(){
            $.ajax({
                type: "POST",
                url: '<?php echo site_url('Notifikasi/CheckStatus'); ?>',
                dataType: 'JSON',
                success: function (resp) {
                    console.log('ajax reload');
                }
            });
        }

        $(document).ready(function() {
            
            setInterval(function() {
                Checker()
            }, 60000);
        });
    </script>
 
 
</head>