<?php
    require_once('php/functions.inc.php');
    session_is_set();

    if(check_login_status()){
        if(check_status()){
            redirect('page_403.html');
        }
    }else{ redirect('login.html'); }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homepage | Clicker</title>
    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">

    <link href="css/util.css" rel="stylesheet">

    <script type="text/javascript" src="js/angular.min.js"></script>
    <script type="text/javascript" src="js/angular-cookies.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
    <script type="text/javascript" src="js/util.js"></script>
</head>

<body class="nav-md" ng-app="myapp" ng-controller="utilCtrl" ng-init="getTeacherSubject(); getUserName(); show=false">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="teacher-home.php" class="site_title"><i class="fa fa-thumbs-o-up"></i> <span>Clicker!</span></a>
                    </div>
                    <div class="clearfix"></div>
                    <!-- menu profile quick info -->
                    <div class="profile">
                        <div class="profile_pic">
                            <img ng-src="{{user_img}}" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Welcome,</span>
                            <h2>{{username}}</h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->
                    <br />
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <h3>Teacher</h3>
                            <ul class="nav side-menu">
                                <li><a href="teacher-home.php"><i class="fa fa-home"></i> หน้าหลัก <span class=""></span></a></li>
                                <li><a><i class="fa fa-table"></i> รายวิชา <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li ng-repeat="subject in teacher_subjects" ng-click="setSection(subject.id, subject.Subject_Name)"><a>{{subject.Subject_ID}} {{subject.Subject_Name}} ตอนเรียน {{subject.Section}}</a></li>
                                    </ul>
                         
                         
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->
                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Settings">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Lock">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Logout">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>
            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <img ng-src="{{user_img}}" alt="">{{username}}
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li><a href="teacher-profile.php"><i class="fa fa-user pull-right"></i> Profile</a></li>
                                    <li><a href="php/logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>  
            <!-- /top navigation -->
            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="clearfix"></div>
                        <!-- <div class="col-md-6 col-sm-6 col-xs-6"> -->
                        <div class="">
                            <div class="x_panel" ng-hide="show">
                                <div class="x_title">
                                    <h2>รายวิชาทั้งหมด</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <table class="countries_list">
                                        <tbody>
                                            <tr class="subject" ng-repeat="subject in teacher_subjects">
                                                <td ng-click="setSection(subject.id, subject.Subject_Name)">
                                                รหัสวิชา {{subject.Subject_ID}} ชื่อวิชา {{subject.Subject_Name}} ตอนเรียน {{subject.Section}}
                                                </td>
                                                <td class="fs15 fw700 text-right">
                                                    <a ng-click="setStudentCheck(subject.id, subject.Subject_Name)" href="#" class="btn btn-success btn-xs"><i class="fa fa-check" ></i> เช็คชื่อ </a>
                                                    <a ng-click="setEnroll(subject.id)" href="#" class="btn btn-warning btn-xs"><i class="fa fa-registered" ></i> นักศึกษาที่ลงทะเบียน </a>
                                                    <a ng-click="editSubject(subject)" href="#" class="btn btn-info btn-xs"><i class="fa fa-pencil" ></i> แก้ไข </a>
                                                    <a href="#" class="btn btn-danger btn-xs" ng-click="deleteSubject(subject)"><i class="fa fa-trash-o"></i> ลบ </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!--x_panel-->
                           <button type="button" class="btn btn-default" ng-click="goto('create_subject.php')">สร้างวิชา</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->
        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
    </div>
    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
</body>

</html>
