<?php
    require_once('php/functions.inc.php');
    session_is_set();

    if(check_login_status()){
        if(!check_status()){
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
    <title>Student | Home</title>
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

<body class="nav-md" ng-app="myapp" ng-controller="utilCtrl" ng-init="setYear(); getUserName(); show=false">
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
                            <h3>Student</h3>
                            <ul class="nav side-menu">
                                <li><a href="student-home.php"><i class="fa fa-home"></i> หน้าหลัก </a> 
                                </li>
                                <li><a><i class="fa fa-table"></i> เทอม <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a ng-click="getSubject(1,year)">1st year ungraduate subjects</a></li>
                                        <li><a ng-click="getSubject(2,year)">2nd year ungraduate subjects</a></li>
                                        <li><a ng-click="getSubject(3,year)">3rd year ungraduate subjects</a></li>
                                        <li><a ng-click="getSubject(4,year)">4th year ungraduate subjects</a></li>
                                        <li><a ng-click="getSubject(5,year)">Graduated subjects</a></li>
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
                                    <li><a href="student-profile.php"><i class="fa fa-user pull-right"></i> โปรไฟล์</a></li>
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
                         <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="x_panel" ng-hide="show">
                                <div class="x_title">
                                    <h2>ประเภทของรายวิชา</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <table class="countries_list">
                                        <tbody>
                                            <tr class="subject" ng-click="getSubject(1,year)">
                                                <td>1st year ungraduate subjects</td>
                                                <!-- <td class="fs15 fw700 text-right">33%</td> -->
                                            </tr>
                                            <tr class="subject" ng-click="getSubject(2,year)">
                                                <td>2nd year ungraduate subjects</td>
                                                <!-- <td class="fs15 fw700 text-right">27%</td> -->
                                            </tr>
                                            <tr class="subject" ng-click="getSubject(3,year)">
                                                <td>3th year ungraduate subjects</td>
                                                <!-- <td class="fs15 fw700 text-right">16%</td> -->
                                            </tr>
                                            <tr class="subject" ng-click="getSubject(4,year)">
                                                <td>4th year ungraduate subjects</td>
                                                <!-- <td class="fs15 fw700 text-right">11%</td> -->
                                            </tr>
                                            <tr class="subject" ng-click="getSubject(5,year)">
                                                <td>Graduated subjects</td>
                                                <!-- <td class="fs15 fw700 text-right">10%</td> -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!--x_panel-->
                            <div class="x_panel" ng-show="show">
                              <div class="x_title">
                                <h2>รายวิชาที่เปิดสอน</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                  <li><a href="#" ng-click="display()"><i class="fa fa-chevron-left"> กลับ</i></a>
                                  </li>
                                </ul>
                                <div class="clearfix"></div>
                              </div>
                              <div class="x_content">
                                <ul class="list-unstyled msg_list">
                                  <li ng-repeat="subject in subjects">
                                    <a>
                                      <span class="image">
                                        <img ng-src="{{subject.Image}}" alt="img" />
                                      </span>
                                      <span>
                                        <span>รหัสวิชา : {{subject.Subject_ID}}</span>
                                        <span class="time"><button type="button" class="btn btn-default" ng-hide="subject.IsRegist" ng-click="register($index)">ลงทะเบียน</button><span ng-show="subject.IsRegist">ลงทะเบียนแล้ว</span></span>
                                        <br><span>ชื่อวิชา : {{subject.Subject_Name}}</span>
                                        <br><span>อาจารย์ : {{subject.FirstName}}  {{subject.LastName}}</span>
                                        <br><span>ห้องเรียน : {{subject.Classroom}}</span>
                                        <br><span>ตอนเรียน : {{subject.Section}}</span>
                                        <br><span>วัน/เวลา : {{subject.Day}} เริ่ม {{subject.TimeStart}} สิ้นสุด {{subject.TimeEnd}}</span>
                                        <br><br><span><button type="button" class="btn btn-primary" ng-click="setDataFetchScore(subject.id, subject.Subject_ID, subject.Subject_Name)">ดูคะแนน</button></span>
                                      </span>
                                      <!--<span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where you met the producers that
                                      </span>
                                      <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where you met the producers that
                                      </span>-->
                                    </a>
                                  </li>
                                </ul>
                              </div>
                          </div><!--x_panel-->
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
