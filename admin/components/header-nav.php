 <!-- [ Header ] start -->
 <header class="navbar pcoded-header navbar-expand-lg navbar-light headerpos-fixed">
   <div class="m-header">
     <a class="mobile-menu" id="mobile-collapse1" href="#!"><span></span></a>
     <a href="dashboard" class="ml-3">

       <h3 class="text-white">
         Farmacia
       </h3>
     </a>
   </div>
   <a class="mobile-menu" id="mobile-header" href="#!">
     <i class="feather icon-more-horizontal"></i>
   </a>
   <div class="navbar-collapse" style="background-color: rgb(255 255 255);">
     <ul class="navbar-nav ml-auto">
       <li class="d-none">
         <div class="dropdown">
           <a class="dropdown-toggle" href="#" data-toggle="dropdown">
             <i class="icon feather icon-bell"></i>
           </a>
           <div class="dropdown-menu dropdown-menu-right notification">
             <div class="noti-head">
               <h6 class="d-inline-block m-b-0">Notifications</h6>
             </div>
             <ul class="noti-body">
               <li class="n-title">
                 <p class="m-b-0">NEW</p>
               </li>
               <li class="notification">
                 <div class="media">
                   <img class="img-radius" src="../assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                   <div class="media-body">
                     <p><strong>John Doe</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>5 min</span></p>
                     <p>New ticket Added</p>
                   </div>
                 </div>
               </li>
               <li class="n-title">
                 <p class="m-b-0">EARLIER</p>
               </li>
               <li class="notification">
                 <div class="media">
                   <img class="img-radius" src="../assets/images/user/avatar-2.jpg" alt="Generic placeholder image">
                   <div class="media-body">
                     <p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>10 min</span></p>
                     <p>Prchace New Theme and make payment</p>
                   </div>
                 </div>
               </li>
               <li class="notification">
                 <div class="media">
                   <img class="img-radius" src="../assets/images/user/avatar-3.jpg" alt="Generic placeholder image">
                   <div class="media-body">
                     <p><strong>Sara Soudein</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>12 min</span></p>
                     <p>currently login</p>
                   </div>
                 </div>
               </li>
               <li class="notification">
                 <div class="media">
                   <img class="img-radius" src="../assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                   <div class="media-body">
                     <p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>30 min</span></p>
                     <p>Prchace New Theme and make payment</p>
                   </div>
                 </div>
               </li>
               <li class="notification">
                 <div class="media">
                   <img class="img-radius" src="../assets/images/user/avatar-3.jpg" alt="Generic placeholder image">
                   <div class="media-body">
                     <p><strong>Sara Soudein</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>1 hour</span></p>
                     <p>currently login</p>
                   </div>
                 </div>
               </li>
               <li class="notification">
                 <div class="media">
                   <img class="img-radius" src="../assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                   <div class="media-body">
                     <p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>2 hour</span></p>
                     <p>Prchace New Theme and make payment</p>
                   </div>
                 </div>
               </li>
             </ul>
             <div class="noti-footer">
               <a href="#!">show all</a>
             </div>
           </div>
         </div>
       </li>

       <li>
         <div class="dropdown drp-user">
           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             <img src="<?= getAvatar($user->id) ?>" class="img-radius" alt="User-Profile-Image" style="width: 35px">
           </a>
           <div class="dropdown-menu dropdown-menu-right profile-notification">
             <div class="pro-head">
               <img src="<?= getAvatar($user->id) ?>" class="img-radius" alt="User-Profile-Image">
               <span><?= getFullName($user->id) ?></span>
               <a href="<?= $SERVER_NAME ?>/backend/nodes?action=logout&&location=admin" class="dud-logout" title="Logout">
                 <i class="feather icon-log-out"></i>
               </a>
             </div>
             <ul class="pro-body">
               <li><a href="./user-profile" class="dropdown-item"><i class="feather icon-user"></i> Profile</a></li>
               <li><a href="<?= $SERVER_NAME ?>/backend/nodes?action=lock_screen" class="dropdown-item"><i class="feather icon-lock"></i> Lock Screen</a></li>
             </ul>
           </div>
         </div>
       </li>
     </ul>
   </div>
 </header>
 <!-- [ Header ] end -->