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
    <title>Student Come! | Clicker </title>
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

<body class="nav-md" ng-app="myapp" ng-controller="utilCtrl" ng-init="getTeacherSubject(); getUserName(); getStudentCome('day');">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="index.html" class="site_title"><i class="fa fa-thumbs-o-up"></i> <span>Clicker!</span></a>
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
                            <h3>General</h3>
                            <ul class="nav side-menu">
                                <li><a href="teacher-home.php"><i class="fa fa-home"></i> หน้าหลัก </a></li>
                                <li><a><i class="fa fa-table"></i> รายวิชา <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li ng-repeat="subject in teacher_subjects" ng-click="setSection(subject.id)"><a>{{subject.Subject_ID}} {{subject.Subject_Name}} ตอนเรียน {{subject.Section}}</a></li>
                                    </ul>
                                </li>
                                <li><a href="enroll-subject.php"><i class="fa fa-home"></i> รายชื่อนักศึกษาลงทะเบียน </a></li>
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
	            <div class="page-title">
	              <div class="title_left">
	                <h3>นักเรียนที่มาเรียน</h3>
                    <table>
                        <tr>
                            <td>
                                <div class="dropdown">
                                  <span style="font-size: 20px;">เลือกวิชา : </span>
                                  <button style="height: 30px;" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{subj}}
                                  <span class="caret"></span></button>
                                  <ul class="dropdown-menu">
                                    <li ng-repeat="subject in teacher_subjects" ng-click="setSubject($index)"><a href="#"><span style="font-size: 15px">{{subject.Subject_Name}} ตอนเรียน {{subject.Section}}</span></a></li>
                                  </ul>
                                </div>
                            </td>
                            <td>
                                <form id="dateForm">
                                  <span style="font-size: 20px;">ใส่วันที่: </span>
                                  <input type="date" name="day" ng-model="day">
                                  <button style="width: 60px; height: 30px;" type="button" class="btn btn-primary" ng-click="getStudentCome('day')">ตกลง</button>
                                </form>
                            </td>
                            <td>
                                <button style="height: 30px;" class="btn btn-primary dropdown-toggle" type="button" ng-click="getStudentCome('all')">ดูทั้งหมด</button>
                            </td>
                        </tr>
                    </table>
                    <br>
	              </div>
	            </div>
	            
	            <div class="clearfix"></div>
                
                <span ng-show="no_student_msg">{{no_student_msg}}</span>
	            <div class="row" ng-hide="no_student_msg">
	              <div class="col-md-8" ng-repeat="section in section_student_come | filter:subj">
	                <div class="x_panel">
	                  <div class="x_title">
	                    <h2>{{section.Subject_Name}} ตอนเรียน {{section.Section_ID}} วันที่ {{section.Date}}</h2>
	                    <div class="clearfix"></div>
	                  </div>
	                  <div class="x_content">

	                    <!-- start project list -->
	                    <table class="table table-striped projects">
	                      <thead>
	                        <tr>
	                          <th style="width: 10%">ลำดับ</th>
	                          <th style="width: 20%">รหัสนักศึกษา</th>
	                          <th style="width: 15%">ชื่อ
                              <th style="width: 30%">นามสกุล</th>
	                          <th style="width: 20%">สถานะ</th>
	                        </tr>
	                      </thead>
	                      <tbody>
	                        <tr ng-repeat="student in section.Student">
	                          <td>{{$index+1}}</td>
	                          <td>
	                            {{student.Student_ID}}
	                          </td>
	                          <td>
	                          	{{student.FirstName}} 
	                          </td>
                              <td>
                                {{student.LastName}}
                              </td>
	                          <td>
	                            <button ng-show="student.isCome" type="button" class="btn btn-success btn-xs">เข้าเรียน</button>
                                <button ng-hide="student.isCome" type="button" class="btn btn-danger btn-xs">ไม่เข้าเรียน</button>
	                          </td>
	                        </tr>
	                      </tbody>
	                    </table>
	                    <!-- end project list -->
                        <span style="font-size: 15px;">&nbsp;&nbsp;<b>รวม : </b>{{section.Student.length}} คน  </span><br>
                        <span style="font-size: 15px;">&nbsp;&nbsp;<b>นักเรียนที่มาเรียน : </b>{{section.student_come}} คน  </span><br>
                        <span style="font-size: 15px;">&nbsp;&nbsp;<b>นักเรียนที่ขาดเรียน : </b>{{section.Student.length - section.student_come}} คน  </span>
	                  </div>
	                </div>
	              </div>
	            </div>

                <div class="clearfix"></div>                
                <div class="row" ng-hide="no_student_msg">
                  <div class="col-md-8" ng-repeat="section in statistic_student_come | filter:subj">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>สถิติการมาเรียนวิชา {{section.Subject_Name}} ตอนเรียน {{section.Section_ID}}</h2>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content">

                        <!-- start project list -->
                        <table class="table table-striped projects">
                          <thead>
                            <tr>
                              <th style="width: 10%">ลำดับ</th>
                              <th style="width: 20%">รหัสนักศึกษา</th>
                              <th style="width: 15%">ชื่อ
                              <th style="width: 30%">นามสกุล</th>
                              <th style="width: 20%">มาเรียน/จำนวนวคาบเรียน</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr ng-repeat="student in section.Student">
                              <td>{{$index+1}}</td>
                              <td>
                                {{student.Student_ID}}
                              </td>
                              <td>
                                {{student.FirstName}} 
                              </td>
                              <td>
                                {{student.LastName}}
                              </td>
                              <td>
                                <center>{{student.Come}}/{{section.Period}}</center>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <!-- end project list -->
                        <span style="font-size: 15px;">&nbsp;&nbsp;รวม : {{section.Student.length}} คน</span>
                      </div>
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



